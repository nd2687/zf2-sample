<?php
namespace Member\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Member\Model\Member;
use Member\Form\MemberForm;

class MemberController extends AbstractActionController
{
    protected $memberTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'members' => $this->getMemberTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new MemberForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $member = new Member();
            $form->setInputFilter($member->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $member->exchangeArray($form->getData());
                $this->getMemberTable()->saveMember($member);

                // Redirect to list of members
                return $this->redirect()->toRoute('member');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('member', array(
                'action' => 'add'
            ));
        }

        // Get the Member with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $member = $this->getMemberTable()->getMember($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('member', array(
                'action' => 'index'
            ));
        }

        $form  = new MemberForm();
        $form->bind($member);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($member->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getMemberTable()->saveMember($member);

                // Redirect to list of members
                return $this->redirect()->toRoute('member');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('member');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getMemberTable()->deleteMember($id);
            }

            // Redirect to list of members
            return $this->redirect()->toRoute('member');
        }

        return array(
            'id'    => $id,
            'member' => $this->getMemberTable()->getMember($id)
        );
    }

    public function getMemberTable()
    {
        if (!$this->memberTable) {
            $sm = $this->getServiceLocator();
            $this->memberTable = $sm->get('Member\Model\MemberTable');
        }
        return $this->memberTable;
    }
}

