<?php

class Course {

    public $id;
    public $name;
    public $description;
    public $image;
    public $students = [];

    function __construct($name, $description, $image) {
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
    }
}
