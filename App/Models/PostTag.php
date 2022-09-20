<?php

namespace SYRADEV\App\Models;

class PostTag {
    public $id;
    public $id_post;
    public $id_tag;

    public function __construct($posttags) {
        $this->id = NULL;
        $this->id_post = (int)$posttags['idpost'];
        $this->id_tag = (int)$posttags['idtag'];
    }
}