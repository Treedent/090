<?php

namespace SYRADEV\App\Models;

class Comment {
    public $id;
    public $post_id;
    public $date_comment;
    public $author;
    public $content;

    public function __construct($comment_post) {
        $this->id = NULL;
        $this->post_id = (int)$comment_post['post_id'];
        $this->date_comment = (int)time();
        $this->author = (int)$comment_post['author'];
        $this->content = (string)$comment_post['content'];
    }
}