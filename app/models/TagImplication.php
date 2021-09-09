<?php
class TagImplication extends Rails\ActiveRecord\Base
{
    private $predicate = '';    // input predicate string
    private $consequent = '';   // input consequent string
    private $predtag = null;    // memoized predicate tag
    private $constag = null;    // memoized consequent tag



    static public function with_implied($tags)
    {
        if (!$tags)
            return array();
        
        $all = array();

        foreach ($tags as $tag) {
            $all[] = $tag;
            $results = array($tag);

            foreach(range(1, 10) as $i) {
                $results = self::connection()->selectValues('
                SELECT
                    t1.name 
                    FROM tags t1, tags t2, tag_implications ti 
                    WHERE ti.predicate_id = t2.id 
                    AND ti.consequent_id = t1.id 
                    AND t2.name IN (?)
                    AND ti.is_pending = FALSE
                ', $results);
                
                if ($results) {
                    $all = array_merge($all, $results);
                } else {
                    break;
                }
            }
        }
        
        return $all;
    }
    
    public function destroy_and_notify($current_user, $reason)
    {
        # TODO:
        if (!empty($this->creator_id) && $this->creator_id != $current_user->id) {
            $msg = "A tag implication you submitted (".$this->predicate->name." &rarr; ".$this->consequent->name.") was deleted for the following reason: ".$reason;
            Dmail::create(array('from_id' => current_user()->id, 'to_id' => $this->creator_id, 'title' => "One of your tag implications was deleted", 'body' => $msg));
        }
        
        $this->destroy();
    }



    public function setPredicate($name)
    {
        $this->predicate = $name;
    }



    public function getPredicate()
    {
        return $this->predtag != null ? $this->predtag
            : ($this->predtag = Tag::find($this->predicate_id));
    }



    public function setConsequent($name)
    {
        $this->consequent = $name;
    }



    public function getConsequent()
    {
        return $this->constag != null ? $this->constag
            : ($this->constag = Tag::find($this->consequent_id));
    }



    public function approve($user_id, $ip_addr)
    {
        self::connection()->executeSql("UPDATE tag_implications SET is_pending = FALSE WHERE id = " . $this->id);
        
        $t = Tag::find($this->predicate_id);
        $implied_tags = implode(' ', self::with_implied(array($t->name)));
        
        foreach (Post::where("id IN (SELECT pt.post_id FROM posts_tags pt WHERE pt.tag_id = ?)", $t->id)->take() as $post) {
            $post->updateAttributes(array('tags' => $post->cached_tags . " " . $implied_tags, 'updater_user_id' => $user_id, 'updater_ip_addr' => $ip_addr));
        }
    }
    
    // protected function associations()
    // {
        // return array(
            // 'belongs_to' => array(
                // 'predicate' => array('class_name' => 'Tag', 'foreign_key' => 'predicate_id'),
                // 'consequent' => array('class_name' => 'Tag', 'foreign_key' => 'consequent_id')
            // )
        // );
    // }
    
    protected function callbacks()
    {
        return array(
            'before_create' => array('validate_input', 'prepare_create', 'validate_uniqueness')
        );
    }



    protected function validate_input()
    {
        $err = $this->errors();

        // verify strings are actually valid UTF-8, otherwise it is malicious binary input
        if (!mb_check_encoding($this->predicate, 'UTF-8')) { $err->add('predicate', 'invalid or empty'); return false; }
        if (!mb_check_encoding($this->consequent, 'UTF-8')) { $err->add('consequent', 'invalid or empty'); return false; }
        if (!mb_check_encoding($this->reason, 'UTF-8')) { $err->add('reason', 'invalid'); return false; }

        $this->predicate = Tag::validate_tag_name($this->predicate);
        $this->consequent = Tag::validate_tag_name($this->consequent);

        if (!$this->predicate) { $err->add('predicate', 'invalid or empty'); return false; }
        if (!$this->consequent) { $err->add('consequent', 'invalid or empty'); return false; }
    }



    protected function prepare_create()
    {
        if (!($this->predtag = Tag::find_or_create_by_name($this->predicate))) {
            $this->errors()->addToBase("Failed to create tag {$this->predicate}");
            return false;
        }

        if (!($this->constag = Tag::find_or_create_by_name($this->consequent))) {
            $this->errors()->addToBase("Failed to create tag {$this->consequent}");
            return false;
        }

        $this->predicate_id = $this->predtag->id;
        $this->consequent_id = $this->constag->id;
    }



    protected function validate_uniqueness()
    {
        // $this->predicate_is($this->new_predicate);
        // $this->consequent_is($this->new_consequent);
        
        if (self::where("(predicate_id = ? AND consequent_id = ?) OR (predicate_id = ? AND consequent_id = ?)", $this->predicate_id, $this->consequent_id, $this->consequent_id, $this->predicate_id)->first()) {
            $this->errors()->addToBase("Tag implication already exists");
            return false;
        }
    }
    
    // private function predicate_is($name)
    // {
        // $t = Tag::find_or_create_by_name($name);
        // $this->predicate_id = $t->id;
    // }

    // private function consequent_is($name)
    // {
        // $t = Tag::find_or_create_by_name($name);
        // $this->consequent_id = $t->id;
    // }
    
}
