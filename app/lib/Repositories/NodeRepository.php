<?php
/**
 * Gracefully crafted by LongoDB
 * 26/07/2020 03:48
 */

namespace Repositories;

use Exceptions\NodeIdException;

class NodeRepository
{
    public $dataSource;

    public function __construct(\Datasources\MysqlDatasource $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    // Get full node tree structure by language
    public function getAllNodes($language)
    {
        $query = "SELECT 
                node_names.nodeName as name, 
                children.level, 
                ROUND((children.iRight - children.iLeft -1) / 2) as children_number,
                children.idNode as node_id 
                -- children.iLeft, 
                -- children.iRight 
                FROM node_tree parent JOIN node_tree children ON children.iLeft BETWEEN parent.iLeft AND parent.iRight 
                JOIN node_tree_names node_names on node_names.idNode = children.idNode
                WHERE node_names.language = '$language' group by children.iLeft order by children.iLeft;";

        $mysqlResult = $this->dataSource->query($query);

        return mysqli_fetch_all($mysqlResult, MYSQLI_ASSOC);
    }

    // Get nodes by parent id, language
    public function get($idNode, $language, $limit = 100, $offset = 0, $keyword = null)
    {
        $query = "SELECT 
                node_names.nodeName as name, 
                children.level, 
                ROUND((children.iRight - children.iLeft -1) / 2) as children_count,
                children.idNode as node_id
                -- children.iLeft, 
                -- children.iRight 
                FROM node_tree parent JOIN node_tree children ON children.iLeft BETWEEN parent.iLeft AND parent.iRight 
                JOIN node_tree_names node_names on node_names.idNode = children.idNode
                WHERE parent.idNode = ? and node_names.language = ? " .
            ($keyword ? " and node_names.nodeName LIKE ?" : '') .
            " group by children.iLeft order by children.iLeft LIMIT ?, ?;";


        // Prepare statement in order to prevent SQL injection and optimize query execution
        $stmt = $this->dataSource->getConnection()->prepare($query);
        if($keyword){
            $keywordParam = "%{$keyword}%";
            $stmt->bind_param("issii", $idNode, $language, $keywordParam, $offset, $limit);
        } else {
            $stmt->bind_param("isii", $idNode, $language, $offset, $limit);
        }
        $stmt->execute();

        $result = $stmt->get_result();

        // If 0 results doesn't depend on not found keyword
        // or pagination, the node id doesn't exists:
        // it could've been done with a query in the beginning,
        // but with this method we save a query (if the conditions are correct)
        if($keyword === null && $limit === 100 && $offset === 0 && $result->num_rows === 0){
            throw new NodeIdException("Invalid node id: $idNode");
        }
        return $this->sanitizeUtf8(mysqli_fetch_all($result, MYSQLI_ASSOC));
    }

    // Sanitize non utf8 chars to prevent breaking json_encode function
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