<?php

namespace Core;

use Core\Database;

class Validator
{

    public $errors = [];
    public $data = [];
    public $field = '';
    public $mod_field_name = ''; // used for column seperated with '_' such as contact_number


    public function __construct($data, $field, $mod_field_name)
    {
        $this->data = $data;
        $this->field = $field;
        $this->mod_field_name = $mod_field_name;
    }

    public function required()
    {

        if (! isset($this->data)) {
            $this->errors[$this->field][] = ucfirst("$this->mod_field_name is required");
            return false;
        }

        return;
    }

    public function email()
    {

        if (! filter_var($this->data[$this->field], FILTER_VALIDATE_EMAIL)) {
            return $this->errors[$this->field][] = ucfirst("$this->mod_field_name must be a valid email");
        }

        return;
    }

    public function min($min_length)
    {
        if (strlen($this->data[$this->field]) < $min_length) {
            return $this->errors[$this->field][] = ucfirst("$this->mod_field_name must be at least $min_length characters");
        }

        return;
    }

    public function max($max_length)
    {
        if (strlen($this->data[$this->field]) > $max_length) {
            return $this->errors[$this->field][] = ucfirst("$this->mod_field_name must not exceed $max_length characters");
        }

        return;
    }

    public function unique($table, $column, $id)
    {

        $db = new Database;
        
        $tableSingular = substr($table, 0, -1);

        $doesExist = $db->query("SELECT * FROM $table WHERE $column = :value AND NOT {$tableSingular}_id = :id", [
            "value" => $this->data[$this->field],
            "id" => $id
        ])->find();

        return $doesExist ? true : $this->errors[$this->field][] = ucfirst("{$this->mod_field_name} already exists");;
    }

    /**
     * Always looks for $input_confirmation
     */
    public function confirmed()
    {
        if ($this->data[$this->field] !== $this->data["{$this->field}_confirmation"]) {
            return $this->errors[$this->field][] = ucfirst("$this->mod_field_name should match");
        }

        return;
    }
}
