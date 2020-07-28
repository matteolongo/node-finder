<?php
/**
 * Gracefully crafted by LongoDB
 * 28/07/2020 22:44
 */

namespace Exceptions;

class NodeIdException extends \Exception
{
    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, $code);
    }
}