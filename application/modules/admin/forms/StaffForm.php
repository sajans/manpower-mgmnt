<?php

class Admin_Form_StaffForm extends Zend_Form {

    public function init() {
        $staffid = new Zend_Form_Element_Hidden("staff_id");

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

        $post = new Zend_Form_Element_Text("post");
        $post->setLabel("Position")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $phone = new Zend_Form_Element_Text("phone");
        $phone->setLabel("Phone")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $address = new Zend_Form_Element_Text("address");
        $address->setLabel("Address")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);


        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit")
                ->setAttribs(array('class' => 'submit', 'id' => 'candidate-submit'));

        $formElements = array(
            $staffid,
            $name,
            $sex,
            $email,
            $post,
            $phone,
            $address,
            $submit);
        $this->addElements($formElements);
        $this->setElementDecorators(array(
            'viewHelper',
            'Errors',
        ));
        $submit->removeDecorator("label");
    }

}

