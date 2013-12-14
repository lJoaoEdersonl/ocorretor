<?php

class Usuario extends PHPFrodo
{

    public $user_login;
    public $user_id;
    public $user_name;
    public $msgError;

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
        if ( isset( $this->uri_segment ) && in_array( 'process-ok', $this->uri_segment ) )
        {
            $this->assign( 'msgOnload', 'notify("<h1>Procedimento realizado com sucesso</h1>")' );
        }
    }

    public function welcome()
    {
        $this->pagebase = "$this->baseUri/admin/usuario";
        $this->tpl( 'admin/user.html' );
        $this->select()
                ->from( 'user' )
                ->paginate( 15 )
                ->orderby( 'user_name asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->fetch( 'rs', $this->data );
            $this->assign( 'user_qtde', count( $this->data ) );
        }
        $this->render();
    }

    public function editar()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $this->user_id = $this->uri_segment[2];
        }
        $this->tpl( 'admin/user_editar.html' );
        $this->select()
                ->from( 'user' )
                ->where( "user_id = $this->user_id" )
                ->execute();
        if ( $this->result() )
        {
            $this->assignAll();
        }
        $this->render();
    }

    public function me()
    {
        $this->editar();
    }

    public function novo()
    {
        $this->tpl( 'admin/user_novo.html' );
        $this->render();
    }

    public function incluir()
    {
        if ( $this->postIsValid( array( 'user_login' => 'string', 'user_password' => 'string' ) ) )
        {
            $this->postIndexDrop( 'user_passwordr' );
            $this->postValueChange( 'user_password', md5( $this->postGetValue( 'user_password' ) ) );
            $this->insert( 'user' )->fields()->values()->execute();
            $this->redirect( "$this->baseUri/admin/usuario/process-ok/" );
        }
        else
        {
            $this->msgError = $this->response;
            $this->pageError();
        }
    }

    public function atualizar()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            if ( $this->postIsValid( array( 'user_email' => 'string' ) ) )
            {
                $this->user_id = $this->uri_segment[2];
                $this->postIndexDrop( 'user_passwordr' );
                if ( !$this->postGetValue( 'user_password' ) )
                {
                    $this->postIndexDrop( 'user_password' );
                }
                else
                {
                    $this->postValueChange( 'user_password', md5( $this->postGetValue( 'user_password' ) ) );
                }
                $this->update( 'user' )->set()->where( "user_id = $this->user_id" )->execute();
                $this->redirect( "$this->baseUri/admin/usuario/process-ok/" );
            }
        }
    }

    public function remover()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $this->user_id = $this->uri_segment[2];
            $this->delete()->from( 'user' )->where( "user_id = $this->user_id" )->execute();
            $this->redirect( "$this->baseUri/admin/usuario/process-ok/" );
        }
    }

    public function pageError()
    {
        $this->tpl( 'admin/error.html' );
        $this->assign( 'msgError', $this->msgError );
        $this->render();
    }

}

/*end file*/