<?php

class Student {

    public $id;
    public $name;
    public $phone;
    public $email;
    public $image;
    public $courses = [];

    function __construct($name, $phone, $email, $image) {
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->image = $image;
    }

}
