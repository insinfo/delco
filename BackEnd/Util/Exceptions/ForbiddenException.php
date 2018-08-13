<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 08/08/2017
 * Time: 10:46
 */

namespace Delco\Util\Exceptions;
use \Exception;


/** acesso probido a um serviço/servidor **/
class ForbiddenException extends Exception
{
    public function __construct($message='Forbidden: proibido!',$code=403, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}