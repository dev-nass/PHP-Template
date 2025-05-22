<?php

namespace Core;

use Core\Validator;

abstract class Controller
{

    protected $request; // input fields

    public function __construct()
    {
        $this->request = array_merge($_GET, $_POST);
    }

    /**
     * Used for getting a specific value within
     * $this->request; Equilvalent to $var = $_GET['name']
     */
    protected function request($key = NULL, $default = NULL)
    {

        // for getting only one input value
        if (isset($key)) {
            $value = $this->request[$key] ?? $default;
            return is_string($value) ? htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8') : $value;
        }

        // otherwise we get all 
        return $this;
    }

    public function validate($rules = [])
    {

        $data = $this->request;

        $errors = [];

        // key - $field (string)
        // value - $rule (string)
        foreach ($rules as $field_name => $rules_set) {
            foreach (explode('|', $rules_set) as $rule) {

                // added for the 'contact_number' to be 'contact number'
                $mod_field_name = str_replace('_', ' ', $field_name);
                $validator = new Validator($data, $field_name, $mod_field_name);

                if ($rule === 'required') {
                    $errors[$field_name][] = $validator->required();
                }

                if ($rule === 'email') {
                    $errors[$field_name][] = $validator->email();
                }

                if (strpos($rule, 'min:') === 0) {
                    $minLength = (int) substr($rule, 4);
                    $errors[$field_name][] = $validator->min($minLength);
                }

                if (strpos($rule, 'max:') === 0) {
                    $minLength = (int) substr($rule, 4);
                    $errors[$field_name][] = $validator->max($minLength);
                }

                if (strpos($rule, 'unique:') === 0) {
                    [$table, $column, $id] = explode(',', $rule);
                    $errors[$field_name][] = $validator->unique($table, $column, $id);
                }

                if (strpos($rule, 'confirmed') === 0) {
                    $errors[$field_name][] = $validator->confirmed();
                }
            }
        }

        // So everytime we validate inputs it is automatically on session
        $flashData = [
            'old' => $data,
            'errors' => $errors,
        ];

        Session::set('__flash', 'data', $flashData);

        if (isset($errors)) {
            $this->redirect(Session::get('__url', 'last_url'));
        }

        return $errors;
    }

    public function view($path, $attribute = [])
    {

        extract($attribute);

        $viewPath = base_path("resources/views/{$path}");

        if (!file_exists($viewPath)) {
            $this->respond(['error' => 'View not found'], 500);
        }

        require base_path("resources/views/{$path}");
    }

    public function redirect($path)
    {
        header("location: {$path}");
        exit();
    }

    /**
     * Shows a responsive message in the form of JSON
     * on the screen. Mostly, erros msgs.
     * 
     * Also used for sending PHP errors to a JS file
     */
    protected function respond($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
