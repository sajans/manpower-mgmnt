<?php

class Admin_CompanyNameController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index', 'login');
        }
    }

    public function indexAction() {
        $companynameModel = new Admin_Model_CompanyName();
        $this->view->result = $companynameModel->getAll();
    }

    public function addAction() {
        $form = new Admin_Form_CompanyNameForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                unset($formData['submit']);
                unset($formData["add_company_id"]);
                try {
                    $companynameModel = new Admin_Model_CompanyName();
                    $companynameModel->add($formData);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Company added"));
                    $this->_helper->redirector('index');
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
                }
            }
        }
    }

    public function editAction() {

        $form = new Admin_Form_CompanyNameForm();
        $form->submit->setLabel("Save");
        $companynameModel = new Admin_Model_CompanyName();
        $id = $this->_getParam('id', 0);
        $data = $companynameModel->getDetailById($id);
        $form->populate($data);
        $this->view->form = $form;
        try {
            if ($this->getRequest()->isPost()) {
                if ($form->Valid($this->getRequest()->getPost())) {
                    $formData = $this->getRequest()->getPost();
                    $id = $formData['add_company_id'];
                    unset($formData['add_company_id']);
                    unset($formData['submit']);
                    $companynameModel->update($formData, $id);
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
        $companynameModel = new Admin_Model_CompanyName();
        $this->view->id = $id;
        if ($this->getRequest()->isPost()) {
            try {
                $delete = $this->_getParam('delete');
                if ('Yes' == $delete) {
                    $companynameModel->delete($id);
                }$this->_helper->redirector("index");
            } catch (Exception $e) {
                $this->view->message = $e->getMessage();
            }
        }
    }

}

?>