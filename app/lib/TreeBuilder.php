<?php
/**
 * Gracefully crafted by LongoDB
 * 26/07/2020 03:46
 */


class TreeBuilder
{
    private $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function getTree()
    {
        // Initialize depth
        $currDepth = -1;
        // Initilialize empty result
        $result = [];
        // Create path structure for depths
        $path = [];
        // Create 'root' node
        $oldItem = ['children'=> &$result];

        // Loop nodes to build the tree array
        foreach($this->items as $item){
            if($item['level'] > $currDepth){
                // Remove old reference (old depth of other branch)
                if(isset($path[$item['level']])) unset($path[$item['level']]);

                // Make sure we have an array entry
                if(!isset($oldItem['children'])) $oldItem['children'] = array();

                // Get target
                $path[$item['level']] = &$oldItem['children'];
            }

            if($item['level'] != $currDepth) unset($oldItem);
            // Set target
            $currDepth = $item['level'];

            // Add item
            $path[$currDepth][] = &$item;

            // Copy & remove reference
            $oldItem = &$item;
            unset($item);
        }

        // Clean up references
        unset($path);
        unset($oldItem);

        return $result;
    }
}