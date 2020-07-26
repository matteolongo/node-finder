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

    public function getAllTree($language)
    {
        $query = "SELECT 
node_names.nodeName, 
children.level, 
ROUND((children.iRight - children.iLeft -1) / 2) as children_number,
children.idNode, 
children.iLeft, 
children.iRight 

FROM node_tree parent JOIN node_tree children ON children.iLeft BETWEEN parent.iLeft AND parent.iRight 
JOIN node_tree_names node_names on node_names.idNode = children.idNode

WHERE node_names.language = '$language' group by children.iLeft order by children.iLeft;";
        $mysqlResult = $this->dataSource->query($query);

        return mysqli_fetch_all($mysqlResult, MYSQLI_ASSOC);

    }

    public function get($idNode, $language)
    {
        /*
        $query = "SELECT node.name, node.lft, node.rgt
                    FROM nested_category AS node,
                    nested_category AS parent
                    WHERE node.lft BETWEEN parent.lft AND parent.rgt
                    AND parent.name = 'ELECTRONICS'
                ORDER BY node.lft;";
        */
        $query = "SELECT 
node_names.nodeName, 
children.level, 
ROUND((children.iRight - children.iLeft -1) / 2) as children_number,
children.idNode, 
children.iLeft, 
children.iRight 

FROM node_tree parent JOIN node_tree children ON children.iLeft BETWEEN parent.iLeft AND parent.iRight 
JOIN node_tree_names node_names on node_names.idNode = children.idNode

WHERE parent.idNode = $idNode and node_names.language = '$language' group by children.iLeft order by children.iLeft;";
        $result = $this->dataSource->query($query);
        $ret = $this->sanitizeUtf8(mysqli_fetch_all($result, MYSQLI_ASSOC));
        return $ret;
    }

    protected function sanitizeUtf8($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->sanitizeUtf8($value);
            }
        } elseif (is_string($mixed)) {
            return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
        }
        return $mixed;
    }
}