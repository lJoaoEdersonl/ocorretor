<?php

class Index extends PHPFrodo
{

    private $user_login;
    private $user_id;
    private $user_name;

    public function __construct()
    {
        parent::__construct();
        $sid = new Session;
        $sid->start();
        if ( !$sid->check() || $sid->getNode( 'user_id' ) <= 0 )
        {
            $this->redirect( "$this->baseUri/admin/login/logout/" );
            exit;
        }        
        $this->user_login = $sid->getNode( 'user_login' );
        $this->user_id = $sid->getNode( 'user_id' );
        $this->user_name = $sid->getNode( 'user_name' );
        $this->assign( 'user_name', $this->user_name );
        $this->select()
                ->from( 'config' )
                ->execute();
        if ( $this->result() )
        {
            $this->config = ( object ) $this->data[0];
            $this->assignAll();
        }        
    }

    public function welcome()
    {
        //$this->redirect( "$this->baseUri/admin/dashboard/pedidos/" );
        $this->tpl( 'admin/dashboard.html' );
        $this->render();
    }

}

/*end file*/