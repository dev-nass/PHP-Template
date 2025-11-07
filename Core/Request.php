<?php

namespace Core;

class Request
{

    protected $request;

    public function __construct()
    {
        $this->request = array_merge($_GET, $_POST);
    }


    /**
     * Used for getting a specific value within
     * $this->request; Equilvalent to $var = $_GET['name']
     */
    public function request($key, $default = null)
    {

        if (isset($this->request[$key])) {
            $value = $this->request[$key] || $default;
            return is_string($value) ? htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8') : $value;
        }

        return $this->request;
    }

    public function validate($rules = [])
    {

        $data = $this->request;

        $errors = [];

        foreach ($rules as $input_name => $rule_set) {

            $mod_input_name = str_replace('_', ' ', $input_name);
            $validator = new Validator($data, $input_name, $mod_input_name);

            foreach (explode('|', $rule_set) as $rule) {

                if ($rule === 'required') {
                    $result = $validator->required();
                    if ($result !== true)
                        $errors[$input_name][] = $result;
                }

                if ($rule === 'email') {
                    $result = $validator->email();
                    if ($result !== true)
                        $errors[$input_name][] = $validator->email();
                }

                if ($rule === 'confirmed') {
                    $result = $validator->confirmed();
                    if ($result !== true)
                        $errors[$input_name][] = $validator->confirmed();
                }
            }
        }


        $flashData = [
            // old value
            'errors' => $errors
        ];

        Session::set('__flash', $flashData);


        if (is_array($errors) && count($errors) > 0) {
            return redirect($_SERVER['HTTP_REFERER']);
        }


        return $data;
    }
}
