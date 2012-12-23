<?php

class Admin_Form_CandidateForm extends Zend_Form {

    public function init() {
        $this->setAttrib('enctype', 'multipart/form-data');

        $candidate = new Zend_Form_Element_Hidden("candidate_id");

        $name = new Zend_Form_Element_Text("name");
        $name->setLabel("Full Name")
                ->setAttribs(array('class' => 'form-text'))
                ->setRequired(true);

        $passport = new Zend_Form_Element_Text("passport_no");
        $passport->setLabel("PassPort Number")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $p_issue_date = new Zend_Form_Element_Text("passport_issued_date");
        $p_issue_date->setLabel("Issued Date")
                ->setAttribs(array('class' => 'form-text'))
                ->setRequired(true);

        $p_expiry_date = new Zend_Form_Element_Text("passport_expiry_date");
        $p_expiry_date->setLabel("Expiry Date")
                ->setAttribs(array('class' => 'form-text'))
                ->setRequired(true);

        $bearer_name = new Zend_Form_Element_Text("bearer_name");
        $bearer_name->setLabel("Bearer Name")
                ->setAttribs(array('class' => 'form-text'))
                ->setRequired(true);

        $imagename = new Zend_Form_Element_File("image_name");
        $imagename->setLabel("Image")
                ->setAttribs(array('class' => 'add-form-file', 'size' => '29'))
                ->setDestination(UPLOAD_PATH)
                ->setRequired(true);

        $dob = new Zend_Form_Element_Text("dob");
        $dob->setLabel("Date of Birth")
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

        $guardain = new Zend_Form_Element_Text("guardain_name");
        $guardain->setLabel("Guardain Name")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'))
                ->setRequired(true);

        $maritalOption = array(
            'Single' => 'Single',
            'Married' => 'Married',
            'Divorced' => 'Divorced',
            'Widow' => 'Window',
            'Other' => 'Other'
        );

        $marital = new Zend_Form_Element_Select("marital_status");
        $marital->setLabel("Marital Status")
                ->addMultiOptions($maritalOption)
                ->setRequired(true);

        $religionOption = array(
            'Hindu' => 'Hindu',
            'Muslim' => 'Muslim',
            'Christian' => 'Christian',
            'Boze' => 'Boze',
            'Honods' => 'Honods',
            'Sick' => 'Sick',
            'Others' => 'Others');

        $religion = new Zend_Form_Element_Select("religion");
        $religion->setLabel("Religion")
                ->addMultiOptions($religionOption)
                ->setRequired(true);

        $agentmodel = new Admin_Model_Agent();
        $agentOption = $agentmodel->getKeysAndValues();

        $agent = new Zend_Form_Element_Select("agent_id");
        $agent->setLabel("Agent")
                ->addMultiOptions($agentOption)
                ->setAttribs(array('class' => 'form-select'));

        $districtmodel = new Admin_Model_District();
        $options = $districtmodel->getKeysAndValues();

        $district_name = new Zend_Form_Element_Select("address");
        $district_name->setLabel("Address")
                ->addMultiOptions($options)
                ->setAttribs(array('class' => 'form-select'));

        $acountryOption = array(
            'UAE' => 'UAE',
            'Malaysia' => 'Malaysia',
            'Qatar' => 'Qatar',
            'Dubai' => 'Dubai',
            'Other' => 'Other');

        $acountry = new Zend_Form_Element_Select("applied_country");
        $acountry->setLabel("Applied Country")
                ->addMultiOptions($acountryOption)
                ->setRequired(true);

        $profession = new Zend_Form_Element_Text("profession");
        $profession->setLabel("Profession")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'));

        $salary = new Zend_Form_Element_Text('salary');
        $salary->setLabel('Salary')
                ->setAttribs(array('size' => '30', 'class' => 'form-text'));

        $duty = new Zend_Form_Element_Text('duty_hour');
        $duty->setLabel('Duty Hour')
                ->setAttribs(array('size' => '30', 'class' => 'form-text'));
        $companyModel = new Admin_Model_Company();
        $companyOptions = $companyModel->getCompanies();
        $company = new Zend_Form_Element_Select("company_name");
        $company->setLabel("Company Name")
                ->setAttribs(array('class' => 'form-select'))
                ->addMultiOptions($companyOptions);
        $education = new Zend_Form_Element_Text("educational_background");
        $education->setLabel("Educational Background")
                ->setAttribs(array('size' => 30, 'class' => 'form-text'));

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Submit")
                ->setAttribs(array('class' => 'submit', 'id' => 'candidate-submit'));
        $formElements = array(
            $candidate,
            $name,
            $passport,
            $p_issue_date,
            $p_expiry_date,
            $bearer_name,
            $imagename,
            $dob,
            $sex,
            $guardain,
            $marital,
            $religion,
            $agent,
            $district_name,
            $company,
            $profession,
            $salary,
            $duty,
            $education,
            $submit);
        $this->addElements($formElements);
        $this->setElementDecorators(array(
            'viewHelper',
            'Errors',
        ));
        $this->getElement('image_name')->setDecorators(
                array(
                    'File',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tag' => 'td')),
                    array('Label', array('tag' => 'th')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
                )
        );
        $submit->removeDecorator("label");
        $imagename->removeDecorator("label");
    }

}

