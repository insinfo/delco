<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 08/08/2017
 * Time: 10:46
 */

namespace Delco\Util\Exceptions;
use \Exception;


/** pagina/serviço/servidor não encontrado **/
class NotFoundException extends Exception
{
    public function __construct($message='Not Found: não encontrado!',$code=404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}