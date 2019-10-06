<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Node extends Model
{

    /**
     *
     * @var integer
     */
    public $id_node;

    /**
     *
     * @var integer
     */
    public $value_node;

    /**
     *
     * @var integer
     */
    public $id_parent;

    /**
     *
     * @var integer
     */
    public $id_tree;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }
}
