<?php

namespace SYRADEV\App\Models;

class Tag {
    public $id;
    public $name;

    public function __construct($tag) {
        $this->id = NULL;
        $this->name = (string)$tag;
    }
}