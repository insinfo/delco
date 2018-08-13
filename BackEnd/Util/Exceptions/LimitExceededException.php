<?php

/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 08/08/2017
 * Time: 10:43
 */
namespace Delco\Util\Exceptions;
use \Exception;


/** limite de uso do serviço/servidor excedeu**/
class LimitExceededException extends Exception
{
    // Redefine a exceção de forma que a mensagem não seja opcional
    public function __construct($message='RBO: limite de uso excedido, tente mais tarde!', $code=408, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    // personaliza a apresentação do objeto como string
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}