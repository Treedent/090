<?php

namespace SYRADEV\App\Models;

class Post {
    public $id;
    public $blog_id;
    public $title;
    public $date_post;
    public $author;
    public $content;

    public function __construct($post) {
        $this->id = NULL;
        $this->blog_id = (int)$post['blogid'];
        $this->title = (string)$post['title'];
        $this->date_post = (int)time();
        $this->author = (int)$post['author'];
        $this->content = (string)$post['content'];
    }
}