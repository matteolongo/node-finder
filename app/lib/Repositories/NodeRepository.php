<?php
/**
 * Gracefully crafted by LongoDB
 * 26/07/2020 03:48
 */

namespace Repositories;

class NodeRepository
{
    public $dataSource;

    public function __construct(\Datasources\MysqlDatasource $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    public function get()
    {
        /*
        $query = "SELECT node.name, node.lft, node.rgt
                    FROM nested_category AS node,
                    nested_category AS parent
                    WHERE node.lft BETWEEN parent.lft AND parent.rgt
                    AND parent.name = 'ELECTRONICS'
                ORDER BY node.lft;";
        */
        $query = "SELECT * FROM test";
        $result = $this->dataSource->query($query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}