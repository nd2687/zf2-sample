<?php
namespace Member\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\Factory;
use Member\Model\Member;
use Member\Model\Add;
use Member\Model\BusinessClassification;
use Member\Form\MemberForm;
use Member\Model\Add\AddService;
use Member\Model\Member\MemberTable;

class MemberController extends AbstractActionController
{
    protected $memberTable;
    protected $business_classificationTable;
    protected $addService;

    public function indexAction()
    {
        return new ViewModel(array(
            'members' => $this->getMemberTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $inputs = $this->createInputFilter();
        $postData = $this->params()->fromPost();
        if(!empty($postData)) {
            $inputs->setData($postData);
        }
        $bc_ary = $this->getArrayBusinessClassification();
        return array(
            'inputs' => $inputs->getInputs(),
            'bc_ary' => $bc_ary,
            'postData' => $postData,
        );
    }

    public function confirmAction()
    {
        $inputs = $this->createInputFilter();
        $postData = $this->params()->fromPost();
        $inputs->setData($postData);

        $business_classification = '';
        if(!empty($postData) && !empty($postData["business_classification_id"])) {
            $business_classification = $this->getBusinessClassificationTable()->getBusinessClassification($postData["business_classification_id"]);
        }
        $member = new Member();
        if ($inputs->isValid()) {
            $member->exchangeArray($postData);
            $this->getMemberTable()->saveMember($member);
            return array(
                'inputs' => $inputs->getInputs(),
                'business_classification' => $business_classification
            );
        } else {
            $bc_ary = $this->getArrayBusinessClassification();
            $view = new ViewModel([
                'inputs' => $inputs->getInputs(),
                'business_classification' => $business_classification,
                'bc_ary' => $bc_ary,
            ]);
            $view->setTemplate('member/member/add.twig');
            return $view;
        }
        return ['Message' => 'Method Not Allowed.'];
    }

    public function completeAction() {
        $postData = $this->params()->fromPost();
        return ['postData' => $postData];
    }

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

    private function createInputFilter()
    {
        $spec = $this->getAddService()->getInputSpec();
        $factory = new Factory();
        $inputs = $factory->createInputFilter($spec);
        return $inputs;
    }

    private function getAddService()
    {
        if (!$this->addService) {
            $sm = $this->getServiceLocator();
            $this->addService = $sm->get('Member\Model\Add\AddService');
        }
        return $this->addService;
    }
}

