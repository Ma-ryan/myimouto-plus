<?php
class CommentController extends ApplicationController
{
    protected function init()
    {
        $this->helper('Avatar', 'Post');
    }
    
    protected function filters()
    {
        return array(
            'before' => [
                'member_only' => ['only' => array('create', 'destroy', 'update')],
                'janitor_only' => ['only' => array('moderate')]
            ]
        );
    }

    public function edit()
    {
        $this->comment = Comment::find($this->params()->id);
    }

    public function update()
    {
        $comment = Comment::find($this->params()->id);
        if (current_user()->has_permission($comment)) {
            $comment->updateAttributes(array_merge($this->params()->comment, ['updater_ip_addr' => $this->request()->remoteIp()]));
            $this->respond_to_success("Comment updated", '#index');
        } else {
            $this->access_denied();
        }
    }

    public function destroy()
    {
        $comment = Comment::find($this->params()->id);
        if (current_user()->has_permission($comment)) {
            $comment->destroy();
            $this->respond_to_success("Comment deleted", array('post#show', 'id' => $comment->post_id));
        } else {
            $this->access_denied();
        }
    }

    public function create()
    {
        $user = current_user();

         if ($user->is_member_or_lower() && $this->params()->commit == "Post") {
                if (Comment::where("user_id = ? AND created_at > SUBDATE(NOW(), INTERVAL 1 HOUR)", current_user()->id)->count() >= CONFIG()->member_comment_limit)
                {
                    $this->respond_to_error("Hourly limit exceeded", '#index', array('status' => 421));
                    return;
                }
                else if (time() - strtotime($user->created_at) < CONFIG()->new_member_comment_period * 86400)
                {
                    $this->respond_to_error('New users must wait ' . CONFIG()->new_member_comment_period . ' days before commenting.', '#index');
                    return;
                }
        }

        $user_id = $user->id;
        
        $comment = new Comment(array_merge($this->params()->comment, array('ip_addr' => $this->request()->remoteIp(), 'user_id' => $user_id)));
        if ($this->params()->commit == "Post without bumping") {
            $comment->do_not_bump_post = true;
        }
        
        if ($comment->save()) {
            $this->respond_to_success("Comment created", '#index');
        } else {
            $this->respond_to_error($comment, '#index');
        }
    }

    public function show()
    {
        $this->set_title('Comment');
        $this->comment = Comment::find($this->params()->id);
        $this->respondTo([
            'html',
            'xml',
            'json' => function() { $this->render(['json' => $this->comment->toJson() ]); }
        ]);
    }

    public function index()
    {
        $this->set_title('Comments');
        
        if ($this->request()->format() == "json" || $this->request()->format() == "xml") {
            $limit = $this->params()->limit;
            $limit = is_numeric($limit) ? intval($limit) : 25;
            $this->comments = Comment::generate_sql($this->params()->all())->order("id DESC")->paginate($this->page_number(), $limit);
            $this->respondTo([
                'html',
                'xml',
                'json' => function() { $this->render(['json' => $this->comments->toJson() ]); }
            ]);
        } else {
            $this->posts = Post::where("last_commented_at IS NOT NULL")->order("last_commented_at DESC")->paginate($this->page_number(), 10);

            $comments = new Rails\ActiveRecord\Collection();
            $this->posts->each(function($post)use($comments){$comments->merge($post->recent_comments());});

            $newest_comment = $comments->max(function($a, $b){return $a->created_at > $b->created_at ? $a : $b;});
            if (!current_user()->is_anonymous() && $newest_comment && current_user()->last_comment_read_at < $newest_comment->created_at) {
                current_user()->updateAttribute('last_comment_read_at', $newest_comment->created_at);
            }

            $this->posts->deleteIf(function($x){return !$x->can_be_seen_by(current_user(), array('show_deleted' => true));});
        }
    }

    public function search()
    {
        $query = Comment::order('id desc');        
        $params = $this->params()->query;
        preg_match_all('/\buser:([^\s]+)/u', $params, $matches, PREG_OFFSET_CAPTURE);

        $where = '1=1';
        $wparams = [];

        if (isset($matches[1]))
        {
            $offset = $matches[0][0][1];
            $length = strlen($matches[0][0][0]);
            $params = substr($params, 0, $offset) . substr($params, $offset + $length);
            $query->joins('JOIN `users` ON `users`.`id` = `comments`.`user_id`');
            $where .= ' AND `users`.`name` = ?';
            $wparams[] = $matches[1][0][0];
        }

        // TODO: comment search using LIKE will be very slow...
        // we really need a full text index on `body` column to handle this correctly
        $params = trim($params);
        if ($params !== '')
        {
            $where .= ' AND `body` LIKE ?';
            $params = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $params);
            $wparams[] = '%' . $params . '%';
        }

        $query->where($where, ...$wparams);

        $this->comments = $query->paginate($this->page_number(), 30);
        $this->respond_to_list("comments");
    }

    public function moderate()
    {
        $this->set_title('Moderate Comments');
        if ($this->request()->isPost()) {
            $ids = array_keys($this->params()->c);
            $coms = Comment::where("id IN (?)", $ids)->take();

            if ($this->params()->commit == "Delete") {
                $coms->each('destroy');
            } elseif ($this->params()->commit == "Approve") {
                $coms->each('updateAttribute', array('is_spam', false));
            }

            $this->redirectTo('#moderate');
        } else {
            $this->comments = Comment::where("is_spam = TRUE")->order("id DESC")->take();
        }
    }

    public function markAsSpam()
    {
        $this->comment = Comment::find($this->params()->id);
        $this->comment->updateAttributes(array('is_spam' => true));
        $this->respond_to_success("Comment marked as spam", '#index');
    }
}
