<?php

namespace Core;

class Validator
{

    public $errors;
    public $data; // hold the array of data ['first_name' => 'John', 'last_name' => 'Doe']
    public $input_name;
    public $mod_field_name; // used for showing error messages; So contact_number will be 'Contact number incorrect'


    public function __construct($data, $input_name, $mod_field_name)
    {
        $this->data = $data;
        $this->input_name = $input_name;
        $this->mod_field_name = $mod_field_name;
    }

    public function required()
    {
        if (!isset($this->data[$this->input_name]) || $this->data[$this->input_name] === '') {
            return
                $this->errors[$this->input_name][] = ucfirst("$this->mod_field_name is required");
        }

        return true;
    }


    public function email()
    {

        $value = $this->data[$this->input_name] ?? null;

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return ucfirst("{$this->mod_field_name} must be a valid email");
        }

        return true;
    }


    public function confirmed()
    {

        if ($this->data['password'] !== $this->data['password_confirmation']) {
            return ucfirst("Password does not match");
        }

        return true;
    }


    public function min($min_length)
    {
        if (strlen($this->data[$this->input_name]) < $min_length) {
            return $this->errors[$this->input_name][] = ucfirst("$this->mod_field_name must be at least $min_length characters");
        }

        return;
    }

    public function max($max_length)
    {
        if (strlen($this->data[$this->input_name]) > $max_length) {
            return $this->errors[$this->input_name][] = ucfirst("$this->mod_field_name must not exceed $max_length characters");
        }

        return;
    }
}