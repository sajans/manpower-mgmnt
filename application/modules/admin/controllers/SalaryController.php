<?php

class Admin_SalaryController extends Zend_Controller_Action {

     public function init() {
        /* Initialize action controller here */
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index', 'login');
        }
    }


    public function indexAction() {
        $salaryModel = new Admin_Model_Salary();
        $this->view->result = $salaryModel->getAll();
    }

    public function addAction() {
        $form = new Admin_Form_SalaryForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                unset($formData['submit']);
                unset($formData["salary_id"]);
                try {
                    $salaryModel = new Admin_Model_Salary();
                    $salaryModel->add($formData);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Salary added"));
                    $this->_helper->redirector('index');
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
                }
            }
        }
    }

    public function editAction() {

        $form = new Admin_Form_SalaryForm();
        $form->submit->setLabel("Save");
        $salaryModel = new Admin_Model_Salary();
        $id = $this->_getParam('id', 0);
        $data = $salaryModel->getDetailById($id);
        $form->populate($data);
        $this->view->form = $form;
        try {
            if ($this->getRequest()->isPost()) {
                if ($form->Valid($this->getRequest()->getPost())) {
                    $formData = $this->getRequest()->getPost();
                    $id = $formData['salary_id'];
                    unset($formData['salary_id']);
                    unset($formData['submit']);

                    $salaryModel->update($formData, $id);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Salary edited"));
                    $this->_helper->redirector('index');
                }
            }
        } catch (Exception $e) {
            $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
        }
    }

    public function deleteAction() {
        $id = $this->_getParam('id', 0);
        $salaryModel = new Admin_Model_Salary();
        $this->view->id = $id;
        if ($this->getRequest()->isPost()) {
            try {
                $delete = $this->_getParam('delete');
                if ('Yes' == $delete) {
                    $salaryModel->delete($id);
                }$this->_helper->redirector("index");
            } catch (Exception $e) {
                $this->view->message = $e->getMessage();
            }
        }
    }

}

