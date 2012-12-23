<?php

class Admin_Form_AmountForm extends Zend_Form {

    public function init() {
        $accountID = new Zend_Form_Element_Hidden("account_id");
        $candidateID = new Zend_Form_Element_Hidden("candidate_id");
        $agentID = new Zend_Form_Element_Hidden("agent_id");

        $amount = new Zend_Form_Element_Text("amount");
        $amount->setLabel("Advance Amount")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->addValidator('digits')
                ->setRequired(true);
        $totalAmount = new Zend_Form_Element_Text("total_amount");
        $totalAmount->setLabel("Total Amount")
                ->setAttribs(array('size' => 30, 'class' => 'form-text','id'=>'total'))
                ->addValidator('digits')
                ->setRequired(true);
        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit");

        $this->addElements(array(
            $accountID,
            $candidateID,
            $agentID,
            $amount,
            $totalAmount,
            $submit));
    }

}

