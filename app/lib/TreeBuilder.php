<?php
/**
 * Gracefully crafted by LongoDB
 * 26/07/2020 03:46
 */


class TreeBuilder
{

    private $mysql_result;
    private $peeked = false;
    private $last_peek;

    public function __construct($mysql_result) {
        $this->mysql_result = $mysql_result;
    }

    public function getTree() {
        $root = $this->consume();
        $root["children"] = $this->getSubTree($root["rgt"]);
        return $root;
    }

    private function getSubTree($stop_at) {
        $nodes = array();
        $node = $this->peek();
        while ($node["rgt"] < $stop_at) {
            $node = $this->consume();
            $node["children"] = $this->getSubTree($node["rgt"]);
            $nodes[] = $node;
            $node = $this->peek();
            if (false === $node) {
                break;
            }
        }
        return $nodes;
    }

    private function peek() {
        if (false === $this->peeked) {
            $this->peeked = true;
            $this->last_peek = mysql_fetch_assoc($this->mysql_result);
        }
        return $this->last_peek;
    }

    private function consume() {
        if (false === $this->peeked) {
            return mysql_fetch_assoc($this->mysql_result);
        } else {
            $this->peeked = false;
            return $this->last_peek;
        }
    }
}