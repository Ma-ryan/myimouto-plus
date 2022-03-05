<?php
class TagSubscription extends Rails\ActiveRecord\Base
{
    protected function associations()
    {
        return [
            'belongs_to' => [
                'user'
            ]
        ];
    }

    protected function callbacks()
    {
        return [
            'before_save' => [
                'normalize_name'
            ],
            'before_create' => [
                'initialize_post_ids'
            ]
        ];
    }

    protected function scopes()
    {
        return [
            'visible' => function() {
                $this->where(['is_visible_on_profile' => true]);
            }
        ];
    }

    public function normalize_name()
    {
        /**
         * MI: \P{Word} doesn't exist in PHP so I used \P{L}, which matches a single letter.
         * In order for \P{L} to work, I had to use mb_conver_encoding
         * which splits a single mb character into two, thus the second replace.
         */
        $this->name = preg_replace(['/\P{L}/', '/_{2,}/'], '_', mb_convert_encoding($this->name, 'ISO-8859-2'));
    }

    public function initialize_post_ids()
    {
        if ($this->user->is_privileged_or_higher()) {
            $this->cached_post_ids = join(',', array_unique(Post::find_by_tags($this->tag_query, ['limit' => ceil(CONFIG()->tag_subscription_post_limit / 3), 'select' => 'p.id', 'order' => 'p.id desc'])->getAttributes('id')));
        }
    }

    static public function find_post_ids($user_id, $name = null, $limit = null)
    {
        $posts = self::find_posts($user_id, $name, $limit);
        foreach ($posts as $post) { $ids[] = $post->id; }
        return $ids;
    }

    static public function find_posts($user_id, $name = null, $limit = null)
    {
        if (!$limit) { $limit = CONFIG()->tag_subscription_post_limit; }

        $sub = self::select('tag_query')->where(['user_id' => $user_id, 'name' => $name])->first();
        $tags = $sub ? $sub->tag_query : '';

        $q = Tag::parse_query($tags);
        list ($sql, $params) = Post::generate_sql($q, ['original_query' => $tags, 'order' => "p.id DESC", 'limit' => $limit]);

        return Post::findBySql($sql, $params);
    }

    static public function process_all()
    {
        foreach (self::all() as $tag_subscription) {
            if ($tag_subscription->user->is_privileged_or_higher()) {
                try {
                    self::transaction(function() use ($tag_subscription) {
                        $tags = preg_split('/\s+/', $tag_subscription->tag_query);
                        $post_ids = [];
                        foreach ($tags as $tag) {
                            $post_ids = array_merge(
                                $post_ids,
                                Post::find_by_tags(
                                    $tag,
                                    [
                                        'limit' => ceil(CONFIG()->tag_subscription_post_limit / 3),
                                        'select' => 'p.id',
                                        'order' => 'p.id desc'
                                    ]
                                )->getAttributes('id')
                            );
                        }
                        sort($post_ids);
                        $tag_subscription->updateAttribute('cached_post_ids', join(',', array_unique(array_slice(array_reverse($post_ids), 0, CONFIG()->tag_subscription_post_limit))));
                    });
                } catch (Exception $e) {
                    # fail silently
                    Rails::log()->exception($e);
                }
                sleep(1);
            }
        }
    }
}
