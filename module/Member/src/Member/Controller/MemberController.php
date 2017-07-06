<?php
namespace Member\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\Factory;
use Member\Model\Member;
use Member\Model\Premember;
use Member\Model\Add;
use Member\Model\BusinessClassification;
use Member\Model\Member\MemberTable;
use Member\Model\Member\PrememberTable;
use Member\Model\Auth as Auth;
use Zend\Mvc\MvcEvent;
use Zend\Db\Adapter\Adapter;

class MemberController extends AbstractActionController
{
    /** @var MemberTable $memberTable */
    protected $memberTable;
    /** @var BusinessClassificationTable $business_classificationTable */
    protected $business_classificationTable;
    /** @var AddService $addService */
    protected $addService;
    /** @var MailService $mailService */
    protected $mailService;
    /** @var ViewModel $view */
    protected $view;

    /**
     * @param MvcEvent
     * @return ViewModel
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->view = new ViewModel();
        $auth = new Auth($this->getServiceLocator());
        $this->view->logined = $auth->checkLogin();
        $logined_member = json_decode(json_encode($auth->getLoginUser()), True); //stdClass to Array
        $this->view->logined_member = $logined_member;
        return parent::onDispatch($e);
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        return $this->view;
    }

    /**
     * @return ViewModel
     */
    public function addAction()
    {
        $inputs = $this->createInputFilter();
        $postData = $this->params()->fromPost();
        if(!empty($postData)) {
            $inputs->setData($postData);
        }
        $bc_ary = $this->getArrayBusinessClassification();
        $this->view->setVariables([
            'inputs' => $inputs->getInputs(),
            'bc_ary' => $bc_ary,
            'postData' => $postData,
        ]);
        return $this->view;
    }

    /**
     * @return ViewModel
     */
    public function confirmAction()
    {
        $inputs = $this->createInputFilter();
        $postData = $this->params()->fromPost();
        $inputs->setData($postData);

        $business_classification = '';
        if(!empty($postData) && !empty($postData["business_classification_id"])) {
            $business_classification = $this->getBusinessClassificationTable()->getBusinessClassification($postData["business_classification_id"]);
        }
        if ($inputs->isValid()) {
            $this->view->setVariables([
                'inputs' => $inputs->getInputs(),
                'business_classification' => $business_classification,
            ]);
        } else {
            $bc_ary = $this->getArrayBusinessClassification();
            $this->view->setVariables([
                'inputs' => $inputs->getInputs(),
                'business_classification' => $business_classification,
                'bc_ary' => $bc_ary,
            ]);
            $this->view->setTemplate('member/member/add.twig');
        }
        return $this->view;
    }

    /**
     * @return ViewModel
     */
    public function completeAction() {
        $postData = $this->params()->fromPost();
        $inputs = $this->createInputFilter();
        $inputs->setData($postData);

        if ($inputs->isValid()) {
            $premember = new Premember();
            $postData['link_pass'] = hash('sha256', uniqid(rand(), 1));
            $date = date('Y-m-d H:i:sP', strtotime('+2 hour'));
            $postData['expired_at'] = $date;
            $premember->exchangeArray($postData);
            $conn = $this->getConnection();
            try {
                $conn->beginTransaction();
                $this->getPrememberTable()->savePremember($premember);
                $this->getMailService()->sendMail($postData);
                $conn->commit();
            } catch (Exception $e){
                $conn->rollback();
                echo $e->getMessage()."\n";
            }
            $this->view->setVariables([
                'inputs' => $inputs->getInputs(),
                'regist_url' => $postData['link_pass'],
            ]);
        } else {
            $business_classification = '';
            if(!empty($postData) && !empty($postData["business_classification_id"])) {
                $business_classification = $this->getBusinessClassificationTable()->getBusinessClassification($postData["business_classification_id"]);
            }
            $bc_ary = $this->getArrayBusinessClassification();
            $this->view->setVariables([
                'inputs' => $inputs->getInputs(),
                'business_classification' => $business_classification,
                'bc_ary' => $bc_ary,
            ]);
            $this->view->setTemplate('member/member/add.twig');
        }
        return $this->view;
    }

    /**
     * @return ViewModel
     */
    public function checkPrememberAction()
    {
        $loginId = $this->params()->fromQuery('login_id');
        $linkPass = $this->params()->fromQuery('link_pass');
        if(!empty($loginId) && !empty($linkPass)) {
            $premember = $this->getPrememberTable()->authPremember($loginId, $linkPass);
            if(!empty($premember)) {
                $conn = $this->getConnection();
                try {
                    $conn->beginTransaction();
                    $this->getPrememberTable()->deletePremember($premember->id);
                    $this->getMemberTable()->saveMember($premember);
                    $conn->commit();
                } catch (Exception $e) {
                    $conn->rollback();
                    echo $e->getMessage()."\n";
                }
                $message = "登録完了しました。ログインしてください。";
            } else {
                $message = "このURLは無効です";
            }
        } else {
            $message = "このURLは無効です。";
        }

        $this->view->setVariables([
            'message' => $message,
        ]);
        $this->view->setTemplate('member/member/checkPremember.twig');
        return $this->view;
    }

    /**
     * @return Response|ViewModel
     */
    public function searchformAction()
    {
        $this->mustLogin();
        $members = $this->getMemberTable()->fetchAll();
        $this->view->setVariables([
            'members' => $members,
        ]);
        return $this->view;
    }

    /**
     * @return Response|ViewModel
     */
    public function searchAction()
    {
        $this->mustLogin();
        $name = $this->params()->fromPost('name');
        $from = $this->params()->fromPost('from');
        $to = $this->params()->fromPost('to');
        $resultSet = $this->getMemberTable()->searchMember($name, $from, $to);
        $this->view->setVariables([
            'members' => $resultSet,
            'inputs' => [
                'name' => $name,
                'from' => $from,
                'to' => $to,
            ],
        ]);
        $this->view->setTemplate('member/member/searchform.twig');
        return $this->view;
    }

    /**
     * @return MemberTable
     */
    private function getMemberTable()
    {
        if (!$this->memberTable) {
            $sm = $this->getServiceLocator();
            $this->memberTable = $sm->get('Member\Model\MemberTable');
        }
        return $this->memberTable;
    }

    /**
     * @return PrememberTable
     */
    private function getPrememberTable()
    {
        if (!$this->prememberTable) {
            $sm = $this->getServiceLocator();
            $this->prememberTable = $sm->get('Member\Model\PrememberTable');
        }
        return $this->prememberTable;
    }

    /**
     * @return BusinessClassificationTable
     */
    private function getBusinessClassificationTable()
    {
        if (!$this->business_classificationTable) {
            $sm = $this->getServiceLocator();
            $this->business_classificationTable = $sm->get('Member\Model\BusinessClassificationTable');
        }
        return $this->business_classificationTable;
    }

    /**
     * @return Array BusinessClassification[ID => 名前]
     */
    private function getArrayBusinessClassification()
    {
        $business_classifications = $this->getBusinessClassificationTable()->fetchAll();
        $bc_ary = [];
        foreach($business_classifications as $bc){
            $bc_ary[$bc->id] = $bc->name;
        }
        return $bc_ary;
    }

    /**
     * @return InputFilter
     */
    private function createInputFilter()
    {
        $spec = $this->getAddService()->getInputSpec();
        $factory = new Factory();
        $inputs = $factory->createInputFilter($spec);
        return $inputs;
    }

    /**
     * @return AddService
     */
    private function getAddService()
    {
        if (!$this->addService) {
            $sm = $this->getServiceLocator();
            $this->addService = $sm->get('Member\Model\Add\AddService');
        }
        return $this->addService;
    }

    /**
     * @return MailService
     */
    private function getMailService()
    {
        if (!$this->mailService) {
            $sm = $this->getServiceLocator();
            $this->mailService = $sm->get('Member\Model\Mail\MailService');
        }
        return $this->mailService;
    }

    /**
     * @return Connection
     */
    private function getConnection()
    {
        $sm = $this->getServiceLocator();
        $adapter = $sm->get('Zend\Db\Adapter\Adapter');
        $conn = $adapter->getDriver()->getConnection();
        return $conn;
    }

    /**
     * @return Response
     */
    private function mustLogin()
    {
        $auth = new Auth($this->getServiceLocator());
        if(!$auth->checkLogin()){
            return($this->redirect()->toUrl('/auth/loginform'));
        }
    }

}

