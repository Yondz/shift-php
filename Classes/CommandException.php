<?php

namespace ShiftPHP\Classes;

/**
 * CommandException
 * @author Yondz
 * @package ShiftPHP
 */
class CommandException extends \Exception
{
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}