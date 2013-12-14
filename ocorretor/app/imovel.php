<?php

class Imovel extends PHPFrodo
{

    public $config = array( );
    public $config_cep = array( );
    public $menu;
    public $item_categoria = null;
    public $item_sub = null;
    public $item_url = null;
    public $item_id = null;
    public $item = null;
    public $f_foto = null;
    public $f_foto_big = null;
    public $cliente_uf = null;

    public function __construct()
    {
        parent:: __construct();

        $this->select()
                ->from( 'config' )
                ->execute();
        if ( $this->result() )
        {
            $this->config = ( object ) $this->data[0];
            $this->assignAll();
        }
        $this->select()
                ->from( 'cliente' )
                ->execute();
        if ( $this->result() )
        {
            $this->cliente_uf = $this->data[0]['cliente_uf'];
            $this->assignAll();
        }

        if ( isset( $this->uri_segment[1] ) && isset( $this->uri_segment[2] ) && isset( $this->uri_segment[3] ) )
        {
            $this->item_categoria = $this->uri_segment[1];
            $this->item_sub = $this->uri_segment[2];
            $this->item_id = $this->uri_segment[3];
        }
    }

    public function welcome()
    {
        $this->tpl( 'public/detalhe.html' );
        if ( $this->item_id != null )
        {
            $this->select()
                    ->from( 'item' )
                    ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                    ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                    ->join( 'categoria', 'item_categoria = categoria_id', 'INNER' )
                    ->where( "item_id = $this->item_id" )
                    ->execute();
            if ( $this->result() )
            {
                $i = (object)$this->data[0];
                
                $this->data[0]['bairro'] = strtolower($this->data[0]['sub_title']);
                if ( $this->data[0]['bairro'] != 'centro'  )
                {
                    $endereco_mapa = $this->data[0]['bairro'] .", ". $this->data[0]['categoria_title'].", $this->cliente_uf, Brasil";
                }
                else{
                    $endereco_mapa = $this->data[0]['categoria_title'].",$this->cliente_uf, Brasil";
                }
                
                //$endereco_mapa = "$i->sub_title, $i->categoria_title, $this->cliente_uf, Brasil";
                $this->addkey( 'endereco_mapa', $endereco_mapa );
                $this->addkey( 'item_bread_title', '', 'item_title' );
                $this->money( 'item_preco' );
                $this->money( 'item_preco_locacao' );
                $this->money( 'item_preco_iptu' );
                $this->money( 'item_preco_condominio' );
                $this->addkey( 'item_negocio', '', 'item_finalidade' );
                $this->preg( array( '/1/', '/2/', '/3/', '/4/' ), array( 'Venda', 'Locação', 'Locação e Venda', 'Temporada' ), 'item_negocio' );
                $this->item = $this->data[0];
                $this->assignAll();
                if ( $this->data[0]['item_finalidade'] == 1 )
                {
                    $this->assign( 'showHideLocacao', 'hide' );
                }
                if ( $this->data[0]['item_preco_condominio'] == 0 )
                {
                    $this->assign( 'showHideCond', 'hide' );
                }
                if ( $this->data[0]['item_preco_iptu'] == 0 )
                {
                    $this->assign( 'showHideIptu', 'hide' );
                }
            }
            $this->fillFoto();
            $this->fillTipo();
            $this->fillCategoria();
            $this->render();
            $this->viewcount();
        }
    }

    public function viewcount()
    {
        $this->increment( 'item', 'item_views', 1, "item_id = $this->item_id" );
    }

    public function fillFoto()
    {
        $this->select()
                ->from( 'foto' )
                ->where( "foto_item = $this->item_id" )
                ->orderby( 'foto_pos asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->addkey( 'foto_big', '', 'foto_url' );
            $this->preg( '/\.jpg/', '', 'foto_url' );
            $this->f_foto = $this->data[0]['foto_url'];
            $this->f_foto_big = $this->data[0]['foto_big'];
            $this->assign( 'f_foto', $this->f_foto );
            $this->assign( 'f_big', $this->f_foto_big );
            $this->assignAll();
            //unset($this->data[0]);
            $this->fetch( 'fg', $this->data );
        }
    }

    public function fillTipo()
    {
        $this->select()
                ->from( 'tipo' )
                ->join( 'item', 'item_tipo = tipo_id', 'INNER' )
                ->groupby( 'tipo_id' )
                ->orderby( 'tipo_title asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->fetch( 'tpb', $this->data );
        }
    }

    public function fillCategoria()
    {
        $this->select()
                ->from( 'categoria' )
                ->join( 'sub', 'sub_categoria = categoria_id', 'INNER' )
                ->join( 'item', 'item_categoria = categoria_id', 'INNER' )
                ->groupby( 'categoria_id' )
                ->orderby( 'categoria_title asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->fetch( 'catb', $this->data );
        }
    }

}

/* end file */