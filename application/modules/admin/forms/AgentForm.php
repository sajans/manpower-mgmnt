<?php

class Admin_Form_AgentForm extends Zend_Form {

    public function init() {
        $agentid = new Zend_Form_Element_Hidden("agent_id");

        $name = new Zend_Form_Element_Text("name");
        $name->setLabel("Full Name")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $option = array(
            'Male' => 'Male',
            'Female' => 'Female',
        );

        $sex = new Zend_Form_Element_Select('sex');
        $sex->setLabel('Sex')
                ->setAttribs(array('class' => 'form-select'))
                ->addMultiOptions($option)
                ->setRequired(true);

        $email = new Zend_Form_Element_Text("email");
        $email->setLabel("E-mail")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $phone = new Zend_Form_Element_Text("phone");
        $phone->setLabel("Phone")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $phone1 = new Zend_Form_Element_Text("phone1");
        $phone1->setLabel("Phone 2")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $address = new Zend_Form_Element_Text("address");
        $address->setLabel("Address")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);


        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit");

        $formElements = (array(
            $agentid,
            $name,
            $sex,
            $email,
            $phone,
            $phone1,
            $address,
            $submit));
        $this->addElements($formElements);
        $this->setElementDecorators(array(
            'viewHelper',
            'Errors',
        ));
        $submit->removeDecorator("label");
    }

}

