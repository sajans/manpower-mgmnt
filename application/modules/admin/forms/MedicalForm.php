<?php

class Admin_Form_MedicalForm extends Zend_Form {

    public function init() {
        $medicalid = new Zend_Form_Element_Hidden("medical_id");

        $candidatemodel = new Admin_Model_Interview();
        $option = $candidatemodel->getKeysAndValues();
//        var_dump($option);exit;

        $candidate_name = new Zend_Form_Element_Select("candidate_id");
        $candidate_name->setLabel("Candidate Name")
                ->addMultiOptions($option)
                ->setAttribs(array('class' => 'form-select', 'id' => 'candidate_id'));
        $visaGroup = new Zend_Form_Element_Select("visa_group");
        $visaGroup->setLabel("Visa Group")
                ->setAttribs(array('class' => 'form-select'))
                ->addMultiOptions(array("" => "Select Company First"))
                ->setRegisterInArrayValidator(false)
                ->setRequired(true);

        $passportno = new Zend_Form_Element_Text("passport_no");
        $passportno->setLabel("Passport No:")
                ->setAttribs(array('size' => 10, 'id' => 'passport', 'readonly' => 'readonly'));

        $mofa_no = new Zend_Form_Element_Text("mofa_no");
        $mofa_no->setLabel("Mofa No.")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $gamca_no = new Zend_Form_Element_Text("gamca_no");
        $gamca_no->setLabel("Gamca No.")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $medical = new Zend_Form_Element_Text('medical_center');
        $medical->setLabel("Medical Center")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $companynamemodel = new Admin_Model_CompanyName();
        $options = $companynamemodel->getKeysAndValues();

        $company_name = new Zend_Form_Element_Select("add_company_id");
        $company_name->setLabel("Company Name")
                ->addMultiOptions($options)
                ->setAttribs(array('class' => 'form-select'));

        $id_number = new Zend_Form_Element_Text("id_number");
        $id_number->setLabel("ID Number")
                ->setAttribs(array('size' => 30, 'class' => 'form-text', 'readonly' => 'readonly'))
                ->setRequired(true);

        $visa_no = new Zend_Form_Element_Text('visa_no');
        $visa_no->setLabel("Visa No.")
                ->setAttribs(array('size' => 30, 'class' => 'form-text', 'readonly' => 'readonly'))
                ->setRequired(true);
        $profession = new Zend_Form_Element_Select("ccat_id");
        $profession->setLabel("Select Profession")
                ->setAttribs(array('class' => 'form-select'))
                ->addMultiOptions(array("" => "Visa Group First"))
                ->setRegisterInArrayValidator(false)
                ->setRequired(true);
        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit");

        $formElements = array(
            $medicalid,
            $candidate_name,
            $passportno,
            $mofa_no,
            $gamca_no,
            $medical,
            $company_name,
            $id_number,
            $visaGroup,
            $visa_no,
            $profession,
            $submit);
        $this->addElements($formElements);
        $this->setElementDecorators(array(
            'viewHelper',
            'Errors',
        ));
        $submit->removeDecorator("label");
    }

}

