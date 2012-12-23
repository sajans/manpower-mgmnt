<?php

class Admin_AgentController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index', 'login');
        }
    }

    public function indexAction() {
        $agentModel = new Admin_Model_Agent();
        $this->view->result = $agentModel->getAll();
    }

    public function addAction() {
        $form = new Admin_Form_AgentForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                unset($formData['submit']);
                unset($formData["agent_id"]);
                try {
                    $agentModel = new Admin_Model_Agent();
                    $agentModel->add($formData);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Agent added"));
                    $this->_helper->redirector('list');
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
                }
            }
        }
    }

    public function editAction() {

        $form = new Admin_Form_AgentForm();
        $form->submit->setLabel("Save");
        $agentModel = new Admin_Model_Agent();
        $id = $this->_getParam('id', 0);
        $data = $agentModel->getDetailById($id);
        $form->populate($data);
        $this->view->form = $form;
        try {
            if ($this->getRequest()->isPost()) {
                if ($form->Valid($this->getRequest()->getPost())) {
                    $formData = $this->getRequest()->getPost();
                    $id = $formData['agent_id'];
                    unset($formData['agent_id']);
                    unset($formData['submit']);

                    $agentModel->update($formData, $id);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Agent edited"));
                    $this->_helper->redirector('list');
                }
            }
        } catch (Exception $e) {
            $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
        }
    }

    public function deleteAction() {
        $id = $this->_getParam('id', 0);
        $agentModel = new Admin_Model_Agent();
        $this->view->id = $id;
        if ($this->getRequest()->isPost()) {
            try {
                $delete = $this->_getParam('delete');
                if ('Yes' == $delete) {
                    $agentModel->delete($id);
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
        $detailColumn->setPosition('right')->setName('Detail')->setDecorator("<a href=\"$baseUrl/admin/agent/detail/id/{{agent_id}}\">Detail</a><input class=\"address-id\" name=\"address_id[]\" type=\"hidden\" value=\"{{agent_id}}\"/>");
        $accountColumn = new Bvb_Grid_Extra_Column();
        $accountColumn->setPosition('right')->setName('Account')->setDecorator("<a href=\"$baseUrl/admin/agent/account/id/{{agent_id}}\">Account</a>");
        $grid->addExtraColumns($accountColumn, $detailColumn);
        $grid->updateColumn('agent_id', array('hidden' => true));
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
        $menuModel = new Admin_Model_Agent();
        $allMenus = $menuModel->listAll();

        foreach ($allMenus as $menu):
            $data = array();
            $data['SNo'] = $i++ ;
            $data['agent_id'] = $menu['agent_id'];
            $data['name'] = $menu['name'];
            $data['phone'] = $menu['phone'];
            $data['phone1'] = $menu['phone1'];
            $data ['address'] = $menu['address'];
            $menus[] = $data;
        endforeach;
        return $menus;
    }

    public function detailAction() {
        $id = $this->_getParam('id', 0);
        $agentModel = new Admin_Model_Agent();
        $data = $agentModel->getDetailById($id);
        $this->view->result = $data;
    }

    public function accountAction() {
        $agentId = $this->_getParam("id", 0);
        $candidateModel = new Admin_Model_Candidate();
        $this->view->results = $candidateModel->getCandidatesByAgentId($agentId);
        $accountModel = new Admin_Model_Account();
        $this->view->accounts = $accountModel->getAllByAgentId($agentId);
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            $accountID = $formData['account_id'];
            unset($formData['account_id']);
            unset($formData['submit']);
            unset($formData["amountvalue"]);
            unset($formData["due_amount"]);
            unset($formData["total_amount"]);
            $detail = $accountModel->getDetailById($accountID);
            $newDueAmount = $detail['due_amount'] - $formData['amount'];
            $newAmount = $detail['amount'] + $formData['amount'];
            $formData['amount'] = $newAmount;
            $formData['due_amount'] = $newDueAmount;
            $formData['total_amount'] = $detail['total_amount'];
            $formData['agent_id'] = $detail['agent_id'];
            $formData['candidate_id'] = $detail['candidate_id'];
            try {
                $agentModel = new Admin_Model_Account();
                $agentModel->update($formData, $accountID);
                $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Agent added"));
                $this->_helper->redirector('list');
            } catch (Exception $e) {
                $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
            }
        }
    }

}
?>

