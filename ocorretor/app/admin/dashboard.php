<?php

class Dashboard extends PHPFrodo
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
        $this->select()
                ->from( 'config' )
                ->execute();
        if ( $this->result() )
        {
            $this->config = ( object ) $this->data[0];
            $this->assignAll();
        }        
        $this->user_login = $sid->getNode( 'user_login' );
        $this->user_id = $sid->getNode( 'user_id' );
        $this->user_name = $sid->getNode( 'user_name' );
        $this->assign( 'user_name', $this->user_name );
    }

    public function welcome()
    {
        $this->tpl( 'admin/dashboard.html' );
        $this->render();
    }

    public function pedidos()
    {
        $this->pagebase = "$this->baseUri/admin/cliente";
        $this->tpl( 'admin/dashboard_pedido.html' );
        $this->select()
                ->from( 'pedido' )
                ->join( 'lista', 'lista_pedido = pedido_id', 'INNER' )
                ->join( 'cliente', 'cliente_id = pedido_cliente', 'INNER' )
                //->where( "pedido_cliente = $this->cliente_id" )
                ->paginate( 10 )
                ->groupby( 'pedido_id' )
                ->orderby( 'pedido_id desc' )
                ->execute();
        if ( $this->result() )
        {
            $this->money( 'pedido_total' );
            $this->money( 'pedido_total_frete' );
            $this->preg( array( '/1/', '/2/', '/3/' ), array( 'Pendente', 'Andamento', 'Finalizado' ), 'pedido_status' );
            $this->addkey( 'pedido_tt_frete', '', 'pedido_total_frete' );
            $this->fetch( 'cart', $this->data );
        }
        else
        {
            $this->assign( 'showHide', 'hide' );
            $this->assign( 'msg_pedido', '<h5 class="alert">Nenhum pedido na lista.</h5>' );
        }
        $this->render();
    }

    public function itens()
    {
        $this->tpl( 'admin/dashboard_item.html' );
        $this->select()
                ->from( 'item' )
                ->join( 'sub', 'sub_id = item_sub', 'INNER' )
                ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' )
                ->paginate( 20 )
                ->orderby( 'item_views desc' )
                ->execute();
        if ( $this->result() )
        {
            $this->money( 'item_preco' );
            $this->money( 'item_desconto' );
            $this->fetch( 'rs', $this->data );
        }
        $this->render();
    }

}

/*end file*/