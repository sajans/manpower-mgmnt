<?php

class Admin_CandidateController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index', 'login');
        }
    }

    public function indexAction() {
        $candidateModel = new Admin_Model_Candidate();
        $this->view->result = $candidateModel->getAllCandidate();
    }

    public function addAction() {
        $form = new Admin_Form_CandidateForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                unset($formData['submit']);
                unset($formData["candidate_id"]);
                unset($formData["MAX_FILE_SIZE"]);
                $image = $formData["image_name"] = $form->image_name->getFileName();
                $exp = explode(DIRECTORY_SEPARATOR, $image);
                $originalFilename = $formData['image_name'] = $exp[sizeof($exp) - 1];
                $newFilename = $formData['image_name'] = time() . $formData['image_name'];
                $form->image_name->addFilter('Rename', $newFilename);
                try {
                    $form->image_name->receive();
//upload complete!
                    $file = new Zend_File_Transfer();
//                    echo "<pre>";
//                    print_r($file);exit;
//  $element = new Zend_Form_Element();
                    $file
                            ->setActualFilename($newFilename);
                    $file->save();
                } catch (Exception $e) {
//error: file couldn't be received, or saved (one of the two)
                }
                try {
                    $candidateModel = new Admin_Model_Candidate();
                    $candidateId = $candidateModel->add($formData);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Candidate added"));
                    $this->_helper->redirector('edit-account', "candidate", "admin", array('id' => $candidateId));
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
                }
            }
        }
    }

    public function editAction() {
        $form = new Admin_Form_CandidateForm();
        $form->submit->setLabel("Save");
        $candidateModel = new Admin_Model_Candidate();
        $id = $this->_getParam('id', 0);
        $data = $candidateModel->getDetailById($id);
        $imageName = $data["image_name"];
        $form->populate($data);
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $formData = $this->getRequest()->getPost();
                $id = $formData['candidate_id'];
                unset($formData['candidate_id']);
                unset($formData['submit']);
                unset($formData["MAX_FILE_SIZE"]);
                $image = $formData["image_name"] = $form->image_name->getFileName();
                $exp = explode(DIRECTORY_SEPARATOR, $image);
                $originalFilename = $formData['image_name'] = $exp[sizeof($exp) - 1];
                $newFilename = $formData['image_name'] = time() . $formData['image_name'];
                $form->image_name->addFilter('Rename', $newFilename);
                try {
                    $form->image_name->receive();
//upload complete!
                    $file = new Zend_File_Transfer();
                    $file->setDisplayFilename($originalFilename['basename'])
                            ->setActualFilename($newFilename);
                    $file->save();
                } catch (Exception $e) {
//error: file couldn't be received, or saved (one of the two)
                }
                try {
                    $candidateModel->update($formData, $id);
                    $path = BASE_PATH . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "destiny" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $imageName;
                    unlink($path);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Candidate edited"));
                    $this->_helper->redirector('edit-account', "candidate", "admin", array('id' => $id));
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
                }
            }
        }
    }

    public function deleteAction() {
        $id = $this->_getParam('id', 0);
        $candidateModel = new Admin_Model_Candidate();
        $this->view->id = $id;
        $data = $candidateModel->getDetailById($id);
        $imageName = $data["image_name"];
        if ($this->getRequest()->isPost()) {
            try {
                $delete = $this->_getParam('delete');
                if ('Yes' == $delete) {
                    $candidateModel->delete($id);
                    $path = BASE_PATH . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "destiny" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $imageName;
                    unlink($path);
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
        $furtherProcessingColumn = new Bvb_Grid_Extra_Column();
        $furtherProcessingColumn->setPosition('right')->setName('Processing')->setDecorator("<a href=\"$baseUrl/admin/candidate/processing/id/{{candidate_id}}\">Processing</a><input class=\"address-id\" name=\"address_id[]\" type=\"hidden\" value=\"{{candidate_id}}\"/>");
        $editColumn = new Bvb_Grid_Extra_Column();
        $editColumn->setPosition('right')->setName('Edit')->setDecorator("<a href=\"$baseUrl/admin/candidate/edit/id/{{candidate_id}}\">Edit</a><input class=\"address-id\" name=\"address_id[]\" type=\"hidden\" value=\"{{candidate_id}}\"/>");
        $detailColumn = new Bvb_Grid_Extra_Column();
        $detailColumn->setPosition('right')->setName('Detail')->setDecorator("<a href=\"$baseUrl/admin/candidate/detail/id/{{candidate_id}}\">Detail</a><input class=\"address-id\" name=\"address_id[]\" type=\"hidden\" value=\"{{candidate_id}}\"/>");
        $deleteColumn = new Bvb_Grid_Extra_Column();
        $deleteColumn->setPosition('right')->setName('Delete')->setDecorator("<a class=\"delete-data\" href=\"$baseUrl/admin/candidate/delete/id/{{candidate_id}}\">Delete</a>");
        $grid->addExtraColumns($detailColumn, $editColumn, $deleteColumn, $furtherProcessingColumn);
        $grid->updateColumn('candidate_id', array('hidden' => true));
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
        $menuModel = new Admin_Model_Candidate();
        $allMenus = $menuModel->listAll();

        foreach ($allMenus as $menu):
            $data = array();
            $data['SNo'] = $i++;
            $data['candidate_id'] = $menu['candidate_id'];
            $data['name'] = $menu['name'];
            $data['passport_no'] = $menu['passport_no'];
            $data ['agent_name'] = $menu['agent_name'];
            $data ['applied_country'] = $menu['applied_country'];
            $data ['profession'] = $menu['profession'];
            $menus[] = $data;
        endforeach;
        return $menus;
    }

    public function detailAction() {
        $id = $this->_getParam('id', 0);
        $candidateModel = new Admin_Model_Candidate();
        $data = $candidateModel->getAllById($id);
        $this->view->result = $data;
    }

    public function editAccountAction() {
        $id = $this->_getParam("id");
        $amountForm = new Admin_Form_AmountForm();
        $candidateModel = new Admin_Model_Candidate();
        $data = $candidateModel->getDetailById($id);
        $agentID = $data['agent_id'];
        $accountModel = new Admin_Model_Account();
        $account = $accountModel->getDetailByCandidate($id, $agentID);
        $accountID = $account['account_id'];
        $amountForm->populate($account);
        $this->view->form = $amountForm;
        try {
            if ($this->getRequest()->isPost()) {
                if ($amountForm->Valid($this->getRequest()->getPost())) {
                    $formData = $this->getRequest()->getPost();
                    unset($formData['account_id']);
                    unset($formData['submit']);
//                    if($formData['submit']=="Yes"){
//                        $this->_helper->redirector("action1");
//                    }else{
//                        
//                    }
                    $formData['due_amount'] = $formData['total_amount'] - $formData['amount'];
                    $accountModel->update($formData, $accountID);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Candidate edited"));
                    $this->_helper->redirector('list');
                }
            }
        } catch (Exception $e) {
            $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
        }
    }

    public function interviewAction() {
        $id = $this->_getParam("id");
        $interviewForm = new Admin_Form_InterviewForm();
        $this->view->form = $interviewForm;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($interviewForm->isValid($formData)) {
                unset($formData['interview_id']);
                unset($formData['submit']);
                $formData['candidate_id'] = $id;
                try {
                    $interviewModel = new Admin_Model_Interview();
                    $lastId = $interviewModel->add($formData);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Candidate Status added"));
                    if ($formData['status'] == 'P') {
                        $this->_helper->redirector('add', "medical", "admin", array('id' => $id));
                    } elseif ($formData['status'] == "F") {
                        $this->_helper->redirector('fail', "candidate", "admin", array('id' => $id));
                    } elseif ($formData['status'] == "H") {
                        $this->_helper->redirector('hold', "candidate", "admin", array('id' => $id));
                    }
                } catch (Exception $e) {

                    $this->_helper->FlashMessenger->addMessage(array("error" => "You have already added the result for this candidate"));
                    $this->_helper->redirector("list");
                }
            }
        }
    }

    public function processingAction() {
        $id = $this->_getParam('id', 0);
        $candidateModel = new Admin_Model_Candidate();
        $data = $candidateModel->getAllById($id);
        $this->view->result = $data;
    }

    public function failAction() {
        $id = $this->_getParam('id', 0);
        $candidateModel = new Admin_Model_Interview();
        $data = $candidateModel->getAllFail();
        $this->view->result = $data;
    }

    public function holdAction() {
        $id = $this->_getParam('id', 0);
        $candidateModel = new Admin_Model_Interview();
        $data = $candidateModel->getAllHold();
        $this->view->result = $data;
    }

    public function editHoldAction() {
        $id = $this->_getParam('id', 0);
        $this->view->id = $id;
        if ($this->getRequest()->isPost()) {
            try {
                $edit = $this->_getParam('edit');
                if ('Yes' == $edit) {
                    $data = $this->getRequest()->getPost();
                    unset($data['edit']);
                    unset($data['id']);
                    $interviewModel = new Admin_Model_Interview();
                    $interviewId = $interviewModel->update($id);
                    $this->_helper->redirector('add', "medical", "admin", array('id' => $id));
                }$this->_helper->redirector("hold");
            } catch (Exception $e) {
                $this->view->message = $e->getMessage();
            }
        }
    }

    public function noProcessingAction() {
        $id = $this->_getParam('id', 0);
        $formData = array();
        $formData['candidate_id'] = $id;
        $formData['status'] = 'P';
        try {
//            var_dump($formData);exit;
            $interviewModel = new Admin_Model_Interview();
            $lastId = $interviewModel->add($formData);
            $this->_helper->redirector('add', "medical", "admin", array('id' => $id));
        } catch (Exception $e) {
           $this->_helper->FlashMessenger->addMessage(array("error" => "It seems this candidate is already processed."));
            $this->_helper->redirector('processing', "candidate", "admin", array('id' => $id));
        }
    }

}

?>