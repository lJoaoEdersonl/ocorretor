<?php

class Servicos extends PHPFrodo
{

    public $user_login;
    public $user_id;
    public $user_name;
    public $frete_param;

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
            $this->servicos = ( object ) $this->data[0];
            $this->assignAll();
        }
        if ( isset( $this->uri_segment ) && in_array( 'process-ok', $this->uri_segment ) )
        {
            $this->assign( 'msgOnload', 'notify("<h1>Procedimento realizado com sucesso</h1>")' );
        }
    }

    public function welcome()
    {
        $this->tpl( 'admin/servicos.html' );
        $this->select()
                ->from( 'servicos' )
                ->execute();
        if ( $this->result() )
        {
            $this->helper( 'redactor' );
            $editor = editor( $this->data[0]['servicos_text'], 'servicos_text', '350px', '90%' );
            $this->assign( 'editor', $editor );
            $this->assignAll();
        }
        $this->render();
    }

    public function atualizar()
    {
        if ( $this->postIsValid( array( 'servicos_title' => 'string' ) ) )
        {
            $this->update( 'servicos' )->set()->execute();
            $this->redirect( "$this->baseUri/admin/servicos/process-ok/" );
        }
    }

}

/*end file*/