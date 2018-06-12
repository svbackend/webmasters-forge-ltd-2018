<?php
declare(strict_types=1);

namespace Base\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ValidatorService
{
    private $data = [];
    private $errors = [];
    private $rules = [];

    private function login($field, $rule): void
    {
        /**
         * @var $min
         * @var $max
         * @var $message
         */
        extract($rule);
        $login = $this->getValue($field);
        if (preg_match('/^[A-Za-z][A-Za-z0-9_]$/', $login)) {
            $this->addError($field, $message);
        }

        $this->string($field, $rule);
    }

    private function file($field, $rule): void
    {
        /**
         * @var $ext array
         * @var $message
         */
        extract($rule);
        /** @var $file UploadedFile */
        $file = $this->getValue($field);
        // if file not selected
        if ($file->getError() == 4) return;
        #$fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileExt = $file->getClientOriginalExtension();

        if (in_array($fileExt, $ext) === false) {
            $this->addError($field, $message);
            return;
        }
    }

    private function email($field, $rule): void
    {
        /**
         * @var $message
         */
        extract($rule);
        if (!filter_var($this->getValue($field), FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, $message);
        }
    }

    /**
     * @param $field
     * @param $rule
     */
    private function string($field, $rule): void
    {
        /**
         * @var $min
         * @var $max
         * @var $message
         */
        extract($rule);
        $stringLength = strlen($this->getValue($field));
        if ($stringLength < $min || (is_int($max) && $stringLength > $max)) {
            $this->addError($field, $message);
        }
    }

    private function getValue($field)
    {
        return $this->data[$field];
    }

    public function addRule(string $field, array $rule): void
    {
        if (!isset($this->rules[$field])) {
            $this->rules[$field] = [];
        }
        $this->rules[$field][] = $rule;
    }

    public function addError($field, $message): void
    {
        if (!isset($this->errors[$field])) {
            // if it first error -- create field's error array
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    public function validate(): bool
    {
        foreach ($this->rules as $field => $rules) {
            foreach ($rules as $rule) {
                // Call validation method (rule name = method name)
                $this->{$rule['name']}($field, $rule);
            }
        }
        return count($this->errors) ? false : true;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }
}