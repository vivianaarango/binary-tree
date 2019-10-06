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
                    "conditions" => "value_node = ?1",
                    "bind" => array(1 => $dataRequest->node_1)
                ));
                
                $nodeb = Node::findFirst(array(
                    "conditions" => "value_node = ?1",
                    "bind" => array(1 => $dataRequest->node_2)
                ));

                $i = $nodea->id_parent;
                $j = $nodeb->id_parent;
                
                //$count=0;
                while ($i != $j) {  
                    //$count++;              
                    $nodea = Node::findFirst(array(
                        "conditions" => "id_node = ?1",
                        "bind" => array(1 => $i),
                        'order' => "id_node ASC"
                    ));

                    $nodeb = Node::findFirst(array(
                        "conditions" => "id_node = ?1",
                        "bind" => array(1 => $j),
                        'order' => "id_node ASC"
                    ));

                    if ( $nodea->id_parent == 0 || $nodeb->id_parent == 0 ) {
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

                $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                    "return" => true,
                    "message" => sprintf(BinaryTreeConstants::LOWEST_COMMON_ANCESTOR, $ancestor->value_node),
                    "status" => ControllerBase::SUCCESS
                ));


            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }
}