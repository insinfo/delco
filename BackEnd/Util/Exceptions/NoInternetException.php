<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 08/08/2017
 * Time: 10:47
 */

namespace Delco\Util\Exceptions;
use \Exception;

/** sem acesso a internet **/
class NoInternetException extends Exception
{
    public function __construct($message='Failed to connect to host: é possível que você esteja sem internet ou o site fora do ar ou mesmo um firewall esta impedindo a conexão!',$code=7, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}