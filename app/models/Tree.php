<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Tree extends Model
{

    /**
     *
     * @var integer
     */
    public $id_tree;

    /**
     *
     * @var string
     */
    public $name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }


}
