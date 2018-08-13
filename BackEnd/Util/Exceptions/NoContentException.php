<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 08/08/2017
 * Time: 10:45
 */

namespace Delco\Util\Exceptions;
use \Exception;

/** dados não encontrados ou não estão presentes na base de dados **/
class NoContentException extends Exception
{
    public function __construct($message='No Content: dados não encontrados ou inexistente!',$code=204, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}