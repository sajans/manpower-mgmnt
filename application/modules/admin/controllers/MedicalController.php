<?php

class Admin_MedicalController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index', 'login');
        }
    }

    public function indexAction() {
        $medicalModel = new Admin_Model_Medical();
        $this->view->result = $medicalModel->getAll();
    }

    public function addAction() {
        $candidatemodel = new Admin_Model_Candidate();
        $option = $candidatemodel->getKeysAndValues();
        $form = new Admin_Form_MedicalForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                unset($formData['submit']);
                unset($formData["medical_id"]);
                unset($formData["passport_no"]);
                $var = explode("::", $formData['candidate_id']);
                $vars = explode("::", $formData['add_company_id']);
                $formData['candidate_id'] = $var[0];
                $formData['add_company_id'] = $vars[0];
                unset($formData["ccat_id"]);
                unset($formData["visa_group"]);
                try {
                   // var_dump($formData);exit;
                    $medicalModel = new Admin_Model_Medical();
                    $medicalModel->add($formData);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Medical added"));
                    $this->_helper->redirector('list');
                } catch (Exception $e) {
                    var_dump($e->getMessage());
                    $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
                }
            }
        }
    }

    public function editAction() {

        $form = new Admin_Form_MedicalForm();
        $form->submit->setLabel("Save");
        $medicalModel = new Admin_Model_Medical();
        $id = $this->_getParam('id', 0);
        $data = $medicalModel->getDetailById($id);
        $data['candidate_id'] = $data['candidate_id'] . "::" . $data['candidate_name'] . "::" . $data['passport_no'];
        $data['add_company_id'] = $data['add_company_id'] . "::" . $data['id_number'];
        $form->populate($data);
        $this->view->form = $form;
        try {
            if ($this->getRequest()->isPost()) {
                if ($form->Valid($this->getRequest()->getPost())) {
                    $formData = $this->getRequest()->getPost();
                    $vars = explode("::", $formData['candidate_id']);
                    $var = explode("::", $formData['add_company_id']);
                    $formData['candidate_id'] = $vars[0];
                    $formData['add_company_id'] = $var[0];
                    unset($formData['medical_id']);
                    unset($formData['passport_no']);
                    unset($formData['submit']);
                    $medicalModel->update($formData, $id);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Medical edited"));
                    $this->_helper->redirector('list');
                }
            }
        } catch (Exception $e) {
            $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
        }
    }

    public function deleteAction() {
        $id = $this->_getParam('id', 0);
        $medicalModel = new Admin_Model_Medical();
        $this->view->id = $id;
        if ($this->getRequest()->isPost()) {
            try {
                $delete = $this->_getParam('delete');
                if ('Yes' == $delete) {
                    $medicalModel->delete($id);
                }$this->_helper->redirector("index");
            } catch (Exception $e) {
                $this->view->message = $e->getMessage();
            }
        }
    }

    public function listAction() {
        $config = new Zend_Config_Ini(BASE_PATH . DIRECTORY_SEPARATOR . "configs" . DIRECTORY_SEPARATOR . "grid.ini", 'production');
        $grid = Bvb_Grid::factory('Table', $config);
        $data = $this->_listdata();
        $source = new Bvb_Grid_Source_Array($data);
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $grid->setSource($source);
        $grid->setImagesUrl("$baseUrl/grid/");
        $editColumn = new Bvb_Grid_Extra_Column();
        $editColumn->setPosition('right')->setName('Edit')->setDecorator("<a href=\"$baseUrl/admin/medical/edit/id/{{medical_id}}\">Edit</a><input class=\"address-id\" name=\"address_id[]\" type=\"hidden\" value=\"{{medical_id}}\"/>");
        $deleteColumn = new Bvb_Grid_Extra_Column();
        $deleteColumn->setPosition('right')->setName('Delete')->setDecorator("<a class=\"delete-data\" href=\"$baseUrl/admin/medical/delete/id/{{medical_id}}\">Delete</a>");
        $grid->addExtraColumns($editColumn, $deleteColumn);
        $grid->updateColumn('medical_id', array('hidden' => true));
        $grid->updateColumn('del', array('hidden' => true));
        $grid->setRecordsPerPage(20);
        $grid->setPaginationInterval(array(
            5 => 5,
            10 => 10,
            20 => 20,
            30 => 30,
            40 => 40,
            50 => 50,
            100 => 100
        ));
        $grid->setExport(array('print', 'word', 'csv', 'excel', 'pdf'));
        $this->view->grid = $grid->deploy();
    }

    public function _listdata() {
        $i = 1;
        $menus = array();
        $menuModel = new Admin_Model_Medical();
        $allMenus = $menuModel->listAll();

        foreach ($allMenus as $menu):
            $data = array();
            $data['SNo'] = $i++;
            $data['medical_id'] = $menu['medical_id'];
            $data['candidate_name'] = $menu['candidate_name'];
            $data['passport_no'] = $menu['passport_no'];
            $data['mofa_no'] = $menu['mofa_no'];
            $data['gamca_no'] = $menu['gamca_no'];
            $data['medical_center'] = $menu['medical_center'];
//            $data['add_company_id'] = $menu['add_company_id'];
            $data['id_number'] = $menu['id_number'];
            $data['visa_no'] = $menu['visa_no'];
            $menus[] = $data;
        endforeach;
        return $menus;
    }

}

