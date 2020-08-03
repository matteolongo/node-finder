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
        return intval($data) !== false && 0 < $data && $data < 1000;
    }
}