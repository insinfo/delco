<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 31/08/2017
 * Time: 11:09
 */

namespace Delco\Util\Exceptions;

use \Exception;

class ArgumentNullOrEmptyException extends Exception
{
    public function __construct($message='NullOrEmpty: argumento não pode ser nulo ou vazio!',$code=400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}