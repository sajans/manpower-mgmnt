<?php

class Admin_StaffController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index', 'login');
        }
    }

    public function indexAction() {
        $staffModel = new Admin_Model_Staff();
        $this->view->result = $staffModel->getAll();
    }

    public function addAction() {
        $form = new Admin_Form_StaffForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                unset($formData['submit']);
                unset($formData["staff_id"]);
                try {
                    $staffModel = new Admin_Model_Staff();
                    $staffModel->add($formData);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Staff added"));
                    $this->_helper->redirector('list');
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
                }
            }
            print_r($form->getErrorMessages());
            exit;
        }
    }

    public function editAction() {

        $form = new Admin_Form_StaffForm();
        $form->submit->setLabel("Save");
        $staffModel = new Admin_Model_Staff();
        $id = $this->_getParam('id', 0);
        $data = $staffModel->getDetailById($id);
        $form->populate($data);
        $this->view->form = $form;
        try {
            if ($this->getRequest()->isPost()) {
                if ($form->Valid($this->getRequest()->getPost())) {
                    $formData = $this->getRequest()->getPost();
                    $id = $formData['staff_id'];
                    unset($formData['staff_id']);
                    unset($formData['submit']);

                    $staffModel->update($formData, $id);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Staff edited"));
                    $this->_helper->redirector('list');
                }
            }
        } catch (Exception $e) {
            $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
        }
    }

    public function deleteAction() {
        $id = $this->_getParam('id', 0);
        $staffModel = new Admin_Model_Staff();
        $this->view->id = $id;
        if ($this->getRequest()->isPost()) {
            try {
                $delete = $this->_getParam('delete');
                if ('Yes' == $delete) {
                    $staffModel->delete($id);
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
        $detailColumn = new Bvb_Grid_Extra_Column();
        $detailColumn->setPosition('right')->setName('Detail')->setDecorator("<a href=\"$baseUrl/admin/staff/detail/id/{{staff_id}}\">Detail</a><input class=\"address-id\" name=\"address_id[]\" type=\"hidden\" value=\"{{staff_id}}\"/>");
        $grid->addExtraColumns($detailColumn);
        $grid->updateColumn('staff_id', array('hidden' => true));
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
        $menuModel = new Admin_Model_Staff();
        $allMenus = $menuModel->listAll();

        foreach ($allMenus as $menu):
            $data = array();
            $data['SNo'] = $i++;
            $data['staff_id'] = $menu['staff_id'];
            $data['name'] = $menu['name'];
            $data['post'] = $menu['post'];
            $data['phone'] = $menu['phone'];
            $data['address'] = $menu['address'];
            $menus[] = $data;
        endforeach;
        return $menus;
    }

    public function detailAction() {
        $id = $this->_getParam('id', 0);
        $staffModel = new Admin_Model_Staff();
        $data = $staffModel->getDetailById($id);
        $this->view->result = $data;
    }

}

?>
