<?php

class Admin_OrientationController extends Zend_Controller_Action {

     public function init() {
        /* Initialize action controller here */
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index', 'login');
        }
    }


    public function indexAction() {
        $orientationModel = new Admin_Model_Orientation();
        $this->view->result = $orientationModel->getAll();
    }

    public function addAction() {
        $form = new Admin_Form_OrientationForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                unset($formData['submit']);
                unset($formData["orientation_id"]);
                try {
                    $orientationModel = new Admin_Model_Orientation();
                    $orientationModel->add($formData);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Orientation added"));
                    $this->_helper->redirector('list');
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
                }
            }
        }
    }

    public function editAction() {

        $form = new Admin_Form_OrientationForm();
        $form->submit->setLabel("Save");
        $orientationModel = new Admin_Model_Orientation();
        $id = $this->_getParam('id', 0);
        $data = $orientationModel->getDetailById($id);
        $form->populate($data);
        $this->view->form = $form;
        try {
            if ($this->getRequest()->isPost()) {
                if ($form->Valid($this->getRequest()->getPost())) {
                    $formData = $this->getRequest()->getPost();
                    $id = $formData['orientation_id'];
                    unset($formData['orientation_id']);
                    unset($formData['submit']);

                    $orientationModel->update($formData, $id);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Orientation edited"));
                    $this->_helper->redirector('list');
                }
            }
        } catch (Exception $e) {
            $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
        }
    }

    public function deleteAction() {
        $id = $this->_getParam('id', 0);
        $orientationModel = new Admin_Model_Orientation();
        $this->view->id = $id;
        if ($this->getRequest()->isPost()) {
            try {
                $delete = $this->_getParam('delete');
                if ('Yes' == $delete) {
                    $orientationModel->delete($id);
                }$this->_helper->redirector("list");
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
        $editColumn->setPosition('right')->setName('Edit')->setDecorator("<a href=\"$baseUrl/admin/orientation/edit/id/{{orientation_id}}\">Edit</a><input class=\"address-id\" name=\"address_id[]\" type=\"hidden\" value=\"{{orientation_id}}\"/>");
        $deleteColumn = new Bvb_Grid_Extra_Column();
        $deleteColumn->setPosition('right')->setName('Delete')->setDecorator("<a class=\"delete-data\" href=\"$baseUrl/admin/orientation/delete/id/{{orientation_id}}\">Delete</a>");
        $grid->addExtraColumns($editColumn, $deleteColumn);
        $grid->updateColumn('orientation_id', array('hidden' => true));
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
        $menuModel = new Admin_Model_Orientation();
        $allMenus = $menuModel->listAll();

        foreach ($allMenus as $menu):
            $data = array();
            $data['SNo'] = $i++;
            $data['orientation_id'] = $menu['orientation_id'];
            $data['candidate_name'] = $menu['candidate_name'];
            $data['amount'] = $menu['amount'];
            $data['paid'] = $menu['paid'];
            $data['orientation_company'] = $menu['orientation_company'];
            $menus[] = $data;
        endforeach;
        return $menus;
    }

}

