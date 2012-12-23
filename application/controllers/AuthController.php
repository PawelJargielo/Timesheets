<?php

class AuthController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    public function indexAction()
    {
        if(Zend_Auth::getInstance()->hasIdentity())
        {
            echo "Zalogowany";
             
        }
        else
        {
            //wyświetlanie formularza
            $this->view->form = new Application_Form_Login();
        }
    }

     public function loginAction()
    {
        $this->_helper->viewRenderer('index');
        $form = new Application_Form_Login();
        
        if ($form->isValid($this->getRequest()->getPost())) {

            $adapter = new Zend_Auth_Adapter_DbTable(
                null,
                'user',
                'username',
                'password',
                'MD5(CONCAT(?, salt))'
            );

            $adapter->setIdentity($form->getValue('username'));
            $adapter->setCredential($form->getValue('password'));
            
            
            $auth = Zend_Auth::getInstance();

            $result = $auth->authenticate($adapter);

            if ($result->isValid()) {
                Zend_Auth::getInstance()->getStorage()
                            ->write($adapter
                            ->getResultRowObject(null, 'password'));
                
                return $this->_helper->redirector(
                    'index',
                    'index',
                    'default'
                );
            }
            $form->password->addError('Błędna próba logowania!');
        }
        $this->view->form = $form;
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        return $this->_helper->redirector(
            'index',
            'index',
            'default'
        );
    }

}





