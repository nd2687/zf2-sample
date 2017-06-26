<?php
namespace Member\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\Factory;
use Member\Model\Member;
use Member\Model\Premember;
use Member\Model\Add;
use Member\Model\BusinessClassification;
use Member\Form\MemberForm;
use Member\Model\Add\AddService;
use Member\Model\Member\MemberTable;
use Member\Model\Member\PrememberTable;
use Zend\Mail\Message;
use Zend\Mail\Transport\File as FileTransport;
use Zend\Mail\Transport\FileOptions;

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
        if ($inputs->isValid()) {
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
    }

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
            $this->getPrememberTable()->savePremember($premember);
            $this->mail_to_premember($postData);
            return array(
                'inputs' => $inputs->getInputs(),
                'regist_url' => $postData['link_pass'],
            );
        } else {
            $business_classification = '';
            if(!empty($postData) && !empty($postData["business_classification_id"])) {
                $business_classification = $this->getBusinessClassificationTable()->getBusinessClassification($postData["business_classification_id"]);
            }
            $bc_ary = $this->getArrayBusinessClassification();
            $view = new ViewModel([
                'inputs' => $inputs->getInputs(),
                'business_classification' => $business_classification,
                'bc_ary' => $bc_ary,
            ]);
            $view->setTemplate('member/member/add.twig');
            return $view;
        }
    }

    public function getMemberTable()
    {
        if (!$this->memberTable) {
            $sm = $this->getServiceLocator();
            $this->memberTable = $sm->get('Member\Model\MemberTable');
        }
        return $this->memberTable;
    }

    public function getPrememberTable()
    {
        if (!$this->prememberTable) {
            $sm = $this->getServiceLocator();
            $this->prememberTable = $sm->get('Member\Model\PrememberTable');
        }
        return $this->prememberTable;
    }

    public function getBusinessClassificationTable()
    {
        if (!$this->business_classificationTable) {
            $sm = $this->getServiceLocator();
            $this->business_classificationTable = $sm->get('Member\Model\BusinessClassificationTable');
        }
        return $this->business_classificationTable;
    }

    public function checkPrememberAction()
    {
        $loginId = $this->params()->fromQuery('login_id');
        $linkPass = $this->params()->fromQuery('link_pass');
        if(!empty($loginId) && !empty($linkPass)) {
            $premember = $this->getPrememberTable()->authPremember($loginId, $linkPass);
            if(!empty($premember)) {
                $this->getPrememberTable()->deletePremember($premember->id);
                $this->getMemberTable()->saveMember($premember);
                $message = "登録完了しました。ログインしてください。";
            } else {
                $message = "このURLは無効です";
            }
        } else {
            $message = "このURLは無効です。";
        }

        $view = new ViewModel([
            'message' => $message,
        ]);
        $view->setTemplate('member/member/checkPremember.twig');
        return $view;
    }


    public function mail_to_premember($userdata)
    {
        $message = new Message();
        $message->setEncoding("UTF-8");
        $message->addFrom("zf2-sample@example.com")
                ->addTo($userdata["mail_address"])
                ->setSubject("会員登録の確認"); // Subject 文字化け
        $messageBody =<<<EOM
{$userdata['login_id']}様

会員登録ありがとうございます。
下のリンクにアクセスして会員登録を完了してください。
http://zf2kawano.local/member/checkPremember?login_id={$userdata["login_id"]}&link_pass={$userdata['link_pass']}

このメールに覚えがない場合はメールを削除してください。

--
会員システム

EOM;
        $message->setBody($messageBody);

        $transport = new FileTransport();
        $options   = new FileOptions(array(
            'path' => 'data/mail',
            'callback' => function (FileTransport $transport) {
                return 'Message_' . microtime(true) . '_' . mt_rand() . '.txt';
            },
        ));
        $transport->setOptions($options);
        $transport->send($message);
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

