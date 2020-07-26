<?php
/**
 * Gracefully crafted by LongoDB
 * 26/07/2020 03:49
 */

namespace Datasources;

interface IDatasource
{
    public function getConnection();
    public function query($query);
}