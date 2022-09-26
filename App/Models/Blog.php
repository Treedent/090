<?php

namespace SYRADEV\App\Models;

class Blog {
    public int $id;
    public string $title;
    public string $subtitle;
    public string $description;
    public $logo;

    public function __construct($blog) {
        $this->id = NULL;
        $this->title = (string)$blog['title'];
        $this->subtitle = (string)$blog['subtitle'];
        $this->description = (string)$blog['desciption'];;
        $this->logo = $blog['logo'];
    }
}