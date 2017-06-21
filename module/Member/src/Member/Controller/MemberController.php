<?php
namespace Member\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Member\Model\Member;
use Member\Model\BusinessClassification;
use Member\Form\MemberForm;

class MemberController extends AbstractActionController
{
    protected $memberTable;
    protected $business_classificationTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'members' => $this->getMemberTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new MemberForm();
        $form->get('submit')->setValue('登録');
        $postData = $this->params()->fromPost();
        if(!empty($postData)) {
          $form->setData($postData);
        }
        $bc_ary = $this->getArrayBusinessClassification();
        return array(
            'form' => $form,
            'bc_ary' => $bc_ary,
            'postData' => $postData
        );
    }

    public function confirmAction()
    {
        $form = new MemberForm();
        $form->get('submit')->setValue('登録');
        $postData = $this->params()->fromPost();
        $business_classification = $this->getBusinessClassificationTable()->getBusinessClassification($postData["business_classification_id"]);
        $request = $this->getRequest();
        $member = new Member();
        $form->setInputFilter($member->getInputFilter());
        $form->setData($request->getPost());
        if ($form->isValid()) {
            // $member->exchangeArray($form->getData());
            // $this->getMemberTable()->saveMember($member);
            return array(
                'form' => $form,
                'postData' => $postData,
                'business_classification' => $business_classification
            );
        } else {
            $bc_ary = $this->getArrayBusinessClassification();
            $view = new ViewModel([
                'form' => $form,
                'business_classification' => $business_classification,
                'bc_ary' => $bc_ary
            ]);
            $view->setTemplate('member/member/add.phtml');
            return $view;
        }
        return ['form' => $form];
    }

    public function completeAction() {
        $postData = $this->params()->fromPost();
        return ['postData' => $postData];
    }
    // public function editAction()
    // {
    //     $id = (int) $this->params()->fromRoute('id', 0);
    //     if (!$id) {
    //         return $this->redirect()->toRoute('member', array(
    //             'action' => 'add'
    //         ));
    //     }
    //
    //     // Get the Member with the specified id.  An exception is thrown
    //     // if it cannot be found, in which case go to the index page.
    //     try {
    //         $member = $this->getMemberTable()->getMember($id);
    //     }
    //     catch (\Exception $ex) {
    //         return $this->redirect()->toRoute('member', array(
    //             'action' => 'index'
    //         ));
    //     }
    //
    //     $form  = new MemberForm();
    //     $form->bind($member);
    //     $form->get('submit')->setAttribute('value', 'Edit');
    //
    //     $request = $this->getRequest();
    //     if ($request->isPost()) {
    //         $form->setInputFilter($member->getInputFilter());
    //         $form->setData($request->getPost());
    //
    //         if ($form->isValid()) {
    //             $this->getMemberTable()->saveMember($member);
    //
    //             // Redirect to list of members
    //             return $this->redirect()->toRoute('member');
    //         }
    //     }
    //
    //     return array(
    //         'id' => $id,
    //         'form' => $form,
    //     );
    // }
    //
    // public function deleteAction()
    // {
    //     $id = (int) $this->params()->fromRoute('id', 0);
    //     if (!$id) {
    //         return $this->redirect()->toRoute('member');
    //     }
    //
    //     $request = $this->getRequest();
    //     if ($request->isPost()) {
    //         $del = $request->getPost('del', 'No');
    //
    //         if ($del == 'Yes') {
    //             $id = (int) $request->getPost('id');
    //             $this->getMemberTable()->deleteMember($id);
    //         }
    //
    //         // Redirect to list of members
    //         return $this->redirect()->toRoute('member');
    //     }
    //
    //     return array(
    //         'id'    => $id,
    //         'member' => $this->getMemberTable()->getMember($id)
    //     );
    // }
    //
    public function getMemberTable()
    {
        if (!$this->memberTable) {
            $sm = $this->getServiceLocator();
            $this->memberTable = $sm->get('Member\Model\MemberTable');
        }
        return $this->memberTable;
    }

    public function getBusinessClassificationTable()
    {
        if (!$this->business_classificationTable) {
            $sm = $this->getServiceLocator();
            $this->business_classificationTable = $sm->get('Member\Model\BusinessClassificationTable');
        }
        return $this->business_classificationTable;
    }

    private function getArrayBusinessClassification()
    {
        $business_classifications = $this->getBusinessClassificationTable()->fetchAll();
        $bc_ary = [];
        foreach($business_classifications as $bc){
            $bc_ary[$bc->id] = $bc->name;
        }
        return $bc_ary;
    }
}

