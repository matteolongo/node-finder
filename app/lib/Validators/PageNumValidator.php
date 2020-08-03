<?php
/**
 * Gracefully crafted by LongoDB
 * 29/07/2020 18:56
 */


namespace Validators;


class PageNumValidator implements IValidator
{
    public static function validate($data)
    {
        return preg_match('#^[0-9][0-9]*$#', $data);
    }
}