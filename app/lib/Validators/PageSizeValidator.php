<?php
/**
 * Gracefully crafted by LongoDB
 * 03/08/2020 13:10
 */


namespace Validators;


class PageSizeValidator implements IValidator
{

    public static function validate($data)
    {
        return (bool)preg_match('#^[1-9][0-9]?[0-9]?$|^1000$#', $data);
    }
}