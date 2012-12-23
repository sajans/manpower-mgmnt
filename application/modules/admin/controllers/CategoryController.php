<?php

class Admin_CategoryController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index', 'login');
        }
    }

    public function indexAction() {
        $categoryModel = new Admin_Model_Category();
        $this->view->result = $categoryModel->getAll();
    }

    public function addAction() {
        $form = new Admin_Form_CategoryForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                unset($formData['submit']);
                unset($formData["ccat_id"]);
                try {
                    $categoryModel = new Admin_Model_Category();
                    $categoryModel->add($formData);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Category added"));
                    $this->_helper->redirector('index');
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
                }
            }
        }
    }

    public function editAction() {

        $form = new Admin_Form_CategoryForm();
        $form->submit->setLabel("Save");
        $categoryModel = new Admin_Model_Category();
        $id = $this->_getParam('id', 0);
        $data = $categoryModel->getDetailById($id);
        $form->populate($data);
        $this->view->form = $form;
        try {
            if ($this->getRequest()->isPost()) {
                if ($form->Valid($this->getRequest()->getPost())) {
                    $formData = $this->getRequest()->getPost();
                    $id = $formData['ccat_id'];
                    unset($formData['ccat_id']);
                    unset($formData['submit']);

                    $categoryModel->update($formData, $id);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Category edited"));
                    $this->_helper->redirector('index');
                }
            }
        } catch (Exception $e) {
            $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
        }
    }

    public function deleteAction() {
        $id = $this->_getParam('id', 0);
        $categoryModel = new Admin_Model_Category();
        $this->view->id = $id;
        if ($this->getRequest()->isPost()) {
            try {
                $delete = $this->_getParam('delete');
                if ('Yes' == $delete) {
                    $categoryModel->delete($id);
                }$this->_helper->redirector("index");
            } catch (Exception $e) {
                $this->view->message = $e->getMessage();
            }
        }
    }

}

?>