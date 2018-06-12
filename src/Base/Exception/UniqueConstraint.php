<?php

namespace Base\Exception;

class UniqueConstraint extends \Exception
{
    private $field;

    public function __construct(string $duplicateErrorMessage)
    {
        if (preg_match("/Duplicate entry '(.*)' for key '(.*)'/", $duplicateErrorMessage, $matchArray) === 1) {
            $this->setField($matchArray[2]);
        }
    }
    
    public function setField(string $field): self
    {
        $this->field = $field;
        return $this;
    }

    public function getField(): ?string
    {
        return $this->field;
    }
}