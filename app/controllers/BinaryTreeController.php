<?php


class BinaryTreeController extends ControllerBase {

    
    /*
    * Return Lowest Common Ancestor
    */ 
    public function lowestCommonAncestorAction() {

        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "id_tree",
            "node_1",
            "node_2"
        );

        if ($this->_checkFields($dataRequest, $fields)) {

            try {
                
                $nodea = Node::findFirst(array(
                    "conditions" => "value_node = ?1 and id_tree = ?2",
                    "bind" => array(1 => $dataRequest->node_1,
                                    2 => $dataRequest->id_tree)
                ));
                
                $nodeb = Node::findFirst(array(
                    "conditions" => "value_node = ?1 and id_tree = ?2",
                    "bind" => array(1 => $dataRequest->node_2,
                                    2 => $dataRequest->id_tree)
                ));

                if (isset($nodea->id_parent) && isset($nodeb->id_parent)) {
                    $i = $nodea->id_parent;
                    $j = $nodeb->id_parent;

                    if ( $i == 0 ) {
                        $ancestor_value = $nodea->value_node;
                    } else if ( $j == 0 ) {
                        $ancestor_value = $nodeb->value_node;
                    } else {
                        //$count=0;
                        while ($i != $j) {  
                            //$count++;              
                            $nodea = Node::findFirst(array(
                                "conditions" => "id_node = ?1 and id_tree = ?2",
                                "bind" => array(1 => $i,
                                                2 => $dataRequest->id_tree),
                                'order' => "id_node ASC"
                            ));

                            $nodeb = Node::findFirst(array(
                                "conditions" => "id_node = ?1 and id_tree = ?2",
                                "bind" => array(1 => $j,
                                                2 => $dataRequest->id_tree),
                                'order' => "id_node ASC"
                            ));
                            
                            if ( ($nodea->id_parent == 0) || ($nodeb->id_parent == 0) ) {
                                $i = $j;
                            } else {
                                $i = $nodea->id_parent;
                                $j = $nodeb->id_parent;
                            }
                        }

                        $ancestor = Node::findFirst(array(
                            "conditions" => "id_node = ?1",
                            "bind" => array(1 => $i)
                        ));
                        $ancestor_value = $ancestor->value_node;
                    }

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "message" => sprintf(BinaryTreeConstants::LOWEST_COMMON_ANCESTOR, $ancestor_value),
                        "status" => ControllerBase::SUCCESS
                    ));
                } else {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
						"return" => false,
						"message" => BinaryTreeConstants::UNDEFINED_NODE,
						"status" => ControllerBase::FAILED
					));
                }
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }


    /*
    * Create Binary Tree
    */ 
    public function createAction() {

        $dataRequest = $this->request->getJsonRawBody();
	
        $fields = array(
			"tree_name",
			"nodes"
		);

        if ($this->_checkFields($dataRequest, $fields)) {

            try {

                $tree = new Tree;
                $tree->name = $dataRequest->tree_name;

                if ($tree->save()) {
                    $node_add = array();
                    $node_fail = array();
                    if (count($dataRequest->nodes) > 0 ){
                        foreach ($dataRequest->nodes as $item) {

                            if ($item->parent == '' || !isset($item->parent)){
                                $item->parent = 0;
                                $node = new Node;
                                $node->value_node = $item->value;
                                $node->id_parent =  $item->parent;
                                $node->id_tree = $tree->id_tree;
                                
                                if ($node->save()){
                                    array_push($node_add, (int)$item->value.': Nodo agregado');
                                }
                            } else {
                                $validate_parent = Node::findFirst(array(
                                    "conditions" => "value_node = ?1 and id_tree = ?2",
                                    "bind" => array(1 => $item->parent,
                                                    2 => $tree->id_tree)
                                ));

                                if (isset($validate_parent->id_node)){
                                    $count_parent = Node::find(array(
                                        "conditions" => "id_parent = ?1 and id_tree = ?2",
                                        "bind" => array(1 => $validate_parent->id_node,
                                                        2 => $tree->id_tree)
                                    ));

                                    if (count($count_parent) > 1){
                                        array_push($node_fail, (int)$item->value.': Nodo padre con maximo de hijos');
                                    } else {
                                        $node = new Node;
                                        $node->value_node = $item->value;
                                        $node->id_parent = $validate_parent->id_node;
                                        $node->id_tree = $tree->id_tree;
                                        
                                        if ($node->save()){
                                            array_push($node_add, (int)$item->value.': Nodo agregado');
                                        }
                                    }
                                } else {
                                    array_push($node_fail, (int)$item->value.': Nodo padre no encontrado');
                                }
                            }    
                        }
                    }

                    $data['id_tree'] = $tree->id_tree;
                    $data['name'] = $tree->name;
                    $data['add'] = $node_add;
                    $data['fail'] = $node_fail;

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "data" => $data,
                        "message" => BinaryTreeConstants::BINARY_TREE_SUCCESS,
                        "status" => ControllerBase::SUCCESS
                    ));

                } else {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
						"return" => false,
						"message" => BinaryTreeConstants::BINARY_TREE_FAILURE,
						"status" => ControllerBase::FAILED
					));
                }

            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }
}