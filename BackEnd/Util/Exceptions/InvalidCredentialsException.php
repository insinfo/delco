<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 31/08/2017
 * Time: 12:08
 */

namespace Delco\Util\Exceptions;

use \Exception;

class InvalidCredentialsException extends Exception
{
    public function __construct($message='InvalidCredentials: Nome de usúario ou senha invalido!',$code=400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}