<?php

namespace DemoProject\App\Services;

class InputValidator
{
    public function validateRules()
    {
        return [
            'name'       => 'required',
            'rows'       => 'required|max:5|min:1',
            'email'      => 'required'
        ];
    }

    public function validate($data)
    {
        $rules = $this->validateRules();
        $data = $this->recursiveValidate($data, $rules);

        if(!empty($data)) {
            throw new \Exception(json_encode($data));
        }
    }

    function recursiveValidate($array, &$rules)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = $this->recursiveValidate($value, $rules);
            } else {
                $rule = isset($rules[$key]) ? $rules[$key] : '';
                $value = $this->ruleChecker($key, $value, $rule);
                if(!empty($value)) {
                    $value = __(str_replace('_', ' ', $value), 'wpsmarttable');
                } else {
                    if(isset($array[$key])) {
                        unset($array[$key]);
                    }
                }
            }

            if(isset($array[$key]) && empty($array[$key])) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    public function ruleChecker($key, $value, $ruleStr)
    {
        $ruleArr = explode('|', $ruleStr);
        $message = "";
        foreach($ruleArr as $rule) {
            $comparerVal = null;
            if (strpos($rule, ':') !== false) {
                $args = explode(':', $rule, 2);

                $rule = isset($args[0]) ? $args[0] : '';
                $comparerVal = isset($args[1]) ? $args[1] : '';
            }

            $message = $this->getErrorMessage($rule, $key, $value, $comparerVal);
            if(!empty($message)) {
                return $message;
            }
        }

        return $message;
    }

    public function getErrorMessage($rule, $key, $value, $comparerVal)
    {
        $message = "";
        switch ($rule) {
            case 'required':
                $message = empty($value) ? $key . " field is required!" : "";
                break;
            case 'integer':
                $message = !is_numeric($value) ? $key . " field must be integer!" : "";
                break;
            case 'max':
                $message = ((int)$value > (int)$comparerVal) ? $key . " should be at most " . $comparerVal . "!" : "";
                break;
            case 'min':
                $message = ((int)$value < (int)$comparerVal) ? $key . " should be at least " . $comparerVal . "!" : "";
                break;
        }

        return $message;
    }
}

