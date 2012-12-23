<?php

class Admin_CompanyController extends Zend_Controller_Action {

    public function init() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('filter-profession', 'json')
                ->addActionContext('filter-visagroup', 'json')
                ->initContext();
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index', 'login');
        }
    }

    public function indexAction() {
        $companyModel = new Admin_Model_Company();
        $this->view->result = $companyModel->getAll();
    }

    public function addAction() {
        $form = new Admin_Form_CompanyForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {
                unset($formData['submit']);
                unset($formData['ccat_id']);
                $var = explode("::", $formData['add_company_id']);
                $formData['add_company_id'] = $var[0];
                unset($formData["company_id"]);
                try {
                    $companyModel = new Admin_Model_Company();
                    $companyModel->add($formData);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Company added"));
                    $this->_helper->redirector('index');
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
                }
            }
        }
    }

    public function editAction() {

        $form = new Admin_Form_CompanyForm();
        $form->submit->setLabel("Save");
        $companyModel = new Admin_Model_Company();
        $id = $this->_getParam('id', 0);
        $data = $companyModel->getDetailById($id);
        $form->populate($data);
        $this->view->form = $form;
        try {
            if ($this->getRequest()->isPost()) {
                if ($form->Valid($this->getRequest()->getPost())) {
                    $formData = $this->getRequest()->getPost();
                    $id = $formData['company_id'];
                    unset($formData['company_id']);
                    unset($formData['submit']);
                    $companyModel->update($formData, $id);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Company edited"));
                    $this->_helper->redirector('index');
                }
            }
        } catch (Exception $e) {
            $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
        }
    }

    public function deleteAction() {
        $id = $this->_getParam('id', 0);
        $companyModel = new Admin_Model_Company();
        $this->view->id = $id;
        if ($this->getRequest()->isPost()) {
            try {
                $delete = $this->_getParam('delete');
                if ('Yes' == $delete) {
                    $companyModel->delete($id);
                }$this->_helper->redirector("index");
            } catch (Exception $e) {
                $this->view->message = $e->getMessage();
            }
        }
    }

    public function filterProfessionAction() {
        $visaNumber = $this->_getParam("id");
        $companyModel = new Admin_Model_Company();
        $this->view->results = $companyModel->filterProfession($visaNumber);
        $this->view->html = $this->view->render("company/filter-profession.phtml");
    }

    public function filterVisagroupAction() {
        $companyId = $this->_getParam("id");
        $companyModel = new Admin_Model_Company();
        $this->view->results = $companyModel->filterVisaGroup($companyId);
        $this->view->html = $this->view->render("company/filter-visagroup.phtml");
    }

}

?>