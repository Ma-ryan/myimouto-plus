<?php
class TagAlias extends Rails\ActiveRecord\Base
{
    static public function tableName()
    {
        return 'tag_aliases';
    }
    
    private $alias_name = null; // input aliased tag name
    private $tag = null;        // memoized tag
    private $alias_tag = null;  // memoized alias tag
    
    protected function callbacks()
    {
        return array(
            'before_create' => array(
                'validate_input',
                'prepare_create',
                'validate_uniqueness',
            ),
            'after_destroy' => [
                'expire_tag_cache_after_deletion'
            ]
        );
    }
    
    static public function to_aliased($tags)
    {
        !is_array($tags) && $tags = array($tags);
        $aliased_tags = array();
        foreach ($tags as $tag_name)
            $aliased_tags[] = self::to_aliased_helper($tag_name);
        
        return $aliased_tags;
    }
    
    static public function to_aliased_helper($tag_name)
    {
        # TODO: add memcached support
        $tag = self::where("tag_aliases.name = ? AND tag_aliases.is_pending = FALSE", $tag_name)
                    ->select("tags.name AS name")
                    ->joins("JOIN tags ON tags.id = tag_aliases.alias_id")
                    ->first();
        return $tag ? $tag->name : $tag_name;
    }
    
    protected function validate_input()
    {
        if (!($this->name = Tag::validate_tag_name($this->name))) {
            $this->errors()->add('name', 'invalid or empty');
            return false;
        }

        if (!($this->alias_name = Tag::validate_tag_name($this->alias_name))) {
            $this->errors()->add('alias', 'invalid or empty');
            return false;
        }
    }


    protected function prepare_create()
    {
        if (!($this->tag = Tag::find_or_create_by_name($this->name))) {
            $this->errors()->addToBase("Failed to create tag {$this->name}");
            return false;
        }

        if (!($this->alias_tag = Tag::find_or_create_by_name($this->alias_name)))
        {
            $this->errors()->addToBase("Failed to create tag {$this->alias_name}");
            return false;
        }

        if ($this->tag->tag_type != $this->alias_tag->tag_type) {
            $this->tag->updateAttribute('tag_type', $this->alias_tag->tag_type);
        }

        $this->alias_id = $this->alias_tag->id;
    }

    # Makes sure the alias does not conflict with any other aliases.
    public function validate_uniqueness()
    {
        // lets do this in one query instead of three... and without an inefficient subquery
        $conflict = self::where('name = ? or name = ? or alias_id = ?',
            $this->name, $this->alias_name, $this->tag->id)->first();

        if ($conflict != null)
        {
            $cname = ($conflict->name == $this->alias_name ? $this->alias_name : $this->name);
            $this->errors()->addToBase("{$cname} is already aliased to something");
            return false;
        }
    }

    
    public function getAlias()
    {
        if ($this->alias_name == null) {
            if ($this->alias_tag == null) {
                $this->alias_tag = Tag::find($this->alias_id);
            }

            $this->alias_name = $this->alias_tag->name;
        }

        return $this->alias_name;
    }


    public function setAlias($name)
    {
        $this->alias_name = $name;
    }
    
    public function alias_name()
    {
        return $this->getAlias();
    }


    public function alias_tag()
    {
        return $this->tag != null ? $this->tag
            : ($this->tag = Tag::find_or_create_by_name($this->name));
    }
    
    # Destroys the alias and sends a message to the alias's creator.
    #TODO:
    public function destroy_and_notify($current_user, $reason)
    {
        if ($this->creator_id && $this->creator_id != $current_user->id) {
            $msg = "A tag alias you submitted (".$this->name." &rarr; " . $this->alias_name() . ") was deleted for the following reason: ".$reason;
            Dmail::create(array('from_id' => current_user()->id, 'to_id' => $this->creator_id, 'title' => "One of your tag aliases was deleted", 'body' => $msg));
        }
        
        $this->destroy();
    }
    
    public function approve($user_id, $ip_addr)
    {
        self::connection()->executeSql("UPDATE tag_aliases SET is_pending = FALSE WHERE id = ?", $this->id);
        
        Post::select('posts.*')
                ->joins('JOIN posts_tags pt ON posts.id = pt.post_id JOIN tags ON pt.tag_id = tags.id')
                ->where("tags.name LIKE ?", $this->name)
                ->take()->each(function($post) use ($user_id, $ip_addr) {
            $post->reload();
            $post->updateAttributes(['tags' => $post->cached_tags, 'updater_user_id' => $user_id, 'updater_ip_addr' => $ip_addr]);
        });

        Moebooru\CacheHelper::expire_tag_version();
    }
    
    public function expire_tag_cache_after_deletion()
    {
        Moebooru\CacheHelper::expire_tag_version();
    }
}
