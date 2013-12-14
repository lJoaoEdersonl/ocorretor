<?php

class Item extends PHPFrodo
{

    public $user_login;
    public $user_id;
    public $user_name;
    public $msgError;
    public $categoria_id;
    public $tipo_id;
    public $categoria_title;
    public $sub_id;
    public $sub_title;
    public $item_id;
    public $item_ref;
    public $item_sub;
    public $item_preco;
    public $item_keywords;
    public $item_desc;
    public $item_show;
    public $item_oferta;
    public $item_url;

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
        $this->pagebase = "$this->baseUri/admin/item";
        $this->tpl( 'admin/item.html' );
        $this->select()
                ->from( 'item' )
                ->join( 'sub', 'sub_id = item_sub', 'INNER' )
                ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' )
                ->paginate( 15 )
                ->orderby( 'item_id desc' )
                ->execute();
        if ( $this->result() )
        {
            $this->money( 'item_preco' );
            $this->money( 'item_preco_locacao' );
            $this->money( 'item_preco_iptu' );
            $this->money( 'item_preco_condominio' );
            $this->preg( array( '/1/', '/2/', '/3/', '/4/' ), array( 'Venda', 'Locação', 'Locação e Venda', 'Temporada' ), 'item_finalidade' );
            $this->fetch( 'rs', $this->data );
            $this->assign( 'item_qtde', $this->getTotalItem() );
        }
        $this->render();
    }

    public function getTotalItem()
    {
        $this->select()->from( 'item' )->execute();
        if ( $this->result() )
        {
            return count( $this->data );
        }
        else
        {
            return 0;
        }
    }

    public function busca()
    {
        //$this->pagebase = "$this->baseUri/admin/item";
        $item_ref = "";
        if ( isset( $_POST['busca'] ) )
        {
            $item_ref = $_POST['busca'];
        }
        $this->tpl( 'admin/item_busca.html' );

        if ( $item_ref != "" )
        {
            $this->select()
                    ->from( 'item' )
                    ->join( 'sub', 'sub_id = item_sub', 'INNER' )
                    ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' )
                    ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                    ->where( "item_ref  = '$item_ref' OR categoria_title like'%$item_ref%' OR item_id = '$item_ref' OR sub_title like'%$item_ref%'" )
                    ->orderby( 'item_ref asc' )
                    ->execute();
            if ( $this->result() )
            {
                $this->money( 'item_preco' );
                $this->money( 'item_desconto' );
                $this->assign( 'item_qtde', count( $this->data ) );
                $this->fetch( 'rs', $this->data );
            }
            else
            {
                $this->assign( 'showHide', "hide" );
                $this->assign( 'msg_busca', '<h5 class="alert">Nenhum item encontrado.</h5>' );
            }
        }
        else
        {
            $this->assign( 'showHide', "hide" );
        }
        $this->assign( 'busca', "$item_ref" );
        $this->render();
    }

    public function editar()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $this->item_id = $this->uri_segment[2];
            $this->tpl( 'admin/item_editar.html' );
            $this->select()
                    ->from( 'item' )
                    ->join( 'sub', 'sub_id = item_sub', 'INNER' )
                    ->join( 'categoria', 'categoria_id = sub_categoria', 'INNER' )
                    ->where( "item_id = $this->item_id" )
                    ->execute();
            if ( $this->result() )
            {
                $this->money( 'item_preco' );
                $this->money( 'item_preco_locacao' );
                $this->money( 'item_preco_iptu' );
                $this->money( 'item_preco_condominio' );
                $this->assignAll();
                $this->helper( 'redactor' );
                $editor = editor( $this->data[0]['item_desc'], 'item_desc', '350px', '90%' );
                $this->assign( 'editor', $editor );
                $this->fillCategoria();
            }
            if ( isset( $this->uri_segment[3] ) )
            {
                $tab = $this->uri_segment[3];
                $tab = "$('#myTab a[href=\"#$tab\"]').tab('show')";
                $this->assign( 'loadTab', $tab );
            }
            //fill fotos
            $this->fillFotos();
            $this->fillTipo();
            $this->render();
        }
    }

    public function novo()
    {
        $this->tpl( 'admin/item_novo.html' );
        $this->fillCategoria();
        $this->fillTipo();
        $this->helper( 'redactor' );
        $editor = editor( '', 'item_desc', '350px', '90%' );
        $this->assign( 'editor', $editor );
        $this->render();
    }

    public function fillFotos()
    {
        $this->select()
                ->from( 'foto' )
                ->where( "foto_item = $this->item_id" )
                ->orderby( 'foto_pos asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->preg( '/\.jpg/', '', 'foto_url' );
            $this->fetch( 'ft', $this->data );
        }
        else
        {
            $this->assign( 'fotoControl', 'hide' );
        }
    }

    public function fillCategoria()
    {
        $this->select()
                ->from( 'categoria' )
                ->orderby( 'categoria_title asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->fetch( 'combo', $this->data );
        }
    }

    public function fillTipo()
    {
        $this->select()
                ->from( 'tipo' )
                ->orderby( 'tipo_title asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->fetch( 'tp', $this->data );
        }
    }

    public function fillSubCategoria()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $this->categoria_id = $this->uri_segment[2];
            $this->select( 'sub_id,sub_title' )
                    ->from( 'sub' )
                    ->where( "sub_categoria = $this->categoria_id" )
                    ->orderby( 'sub_title asc' )
                    ->execute();
            if ( $this->result() )
            {
                @header( 'Content-Type: text/html; charset=iso-8859-1' );
                echo $this->toJson();
            }
            else
            {
                echo 0;
            }
        }
    }

    public function incluir()
    {
        if ( $this->postIsValid( array(
                    'item_ref' => 'string',
                    'item_categoria' => 'string',
                    'item_sub' => 'string'
                ) ) )
        {
            $this->categoria_id = $this->postGetValue( 'item_categoria' );
            $this->sub_id = $this->postGetValue( 'item_sub' );
            $this->tipo_id = $this->postGetValue( 'item_tipo' );
            $termo_busca = $this->getTermos();
            $this->postIndexAdd( 'item_busca', $termo_busca );

            $this->postIndexDrop( 'upload' );
            $this->postValueChange( 'item_preco', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco' ) ) );
            $this->postValueChange( 'item_preco_locacao', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_locacao' ) ) );
            $this->postValueChange( 'item_preco_iptu', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_iptu' ) ) );
            $this->postValueChange( 'item_preco_condominio', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_condominio' ) ) );
            $this->insert( 'item' )->fields()->values()->execute();
            $item = mysql_insert_id();
            $this->redirect( "$this->baseUri/admin/item/editar/$item/fotos/" );
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
            if ( $this->postIsValid( array(
                        'item_ref' => 'string',
                        'item_categoria' => 'string',
                        'item_sub' => 'string'
                    ) ) )
            {
                $this->categoria_id = $this->postGetValue( 'item_categoria' );
                $this->sub_id = $this->postGetValue( 'item_sub' );
                $this->tipo_id = $this->postGetValue( 'item_tipo' );

                $termo_busca = $this->getTermos();
                $this->postIndexAdd( 'item_busca', $termo_busca );
                $this->postIndexDrop( 'upload' );
                $this->item_id = $this->uri_segment[2];
                $this->postValueChange( 'item_preco', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco' ) ) );
                $this->postValueChange( 'item_preco_locacao', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_locacao' ) ) );
                $this->postValueChange( 'item_preco_iptu', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_iptu' ) ) );
                $this->postValueChange( 'item_preco_condominio', preg_replace( array( '/\./', '/\,/' ), array( '', '.' ), $this->postGetValue( 'item_preco_condominio' ) ) );
                //$this->showPostData();
                $this->update( 'item' )->set()->where( "item_id = $this->item_id" )->execute();
                $this->redirect( "$this->baseUri/admin/item/editar/$this->item_id/process-ok/" );
            }
        }
    }

    public function getTermos()
    {
        $term = "";

        $this->select()->from( 'tipo' )->where( "tipo_id = $this->tipo_id" )->execute();
        $term .= $this->data[0]['tipo_title'] . " ";

        $this->select()->from( 'categoria' )->where( "categoria_id = $this->categoria_id" )->execute();
        $term .= $this->data[0]['categoria_title'] . " ";

        $this->select()->from( 'sub' )->where( "sub_id = $this->sub_id" )->execute();
        $term .= $this->data[0]['sub_title'];

        return $term;
    }

    public function remover()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $this->item_id = $this->uri_segment[2];
            $this->removeFotos();
            $this->delete()->from( 'item' )->where( "item_id = $this->item_id" )->execute();
            $this->redirect( "$this->baseUri/admin/item/process-ok/" );
        }
    }

    public function removeFotos()
    {
        $this->select()
                ->from( 'foto' )
                ->where( "foto_item = $this->item_id" )
                ->execute();
        if ( $this->result() )
        {
            foreach ( $this->data as $f )
            {
                $f = ( object ) $f;
                $file = "app/fotos/$f->foto_url";
                if ( file_exists( $file ) )
                {
                    @unlink( $file );
                }
            }
        }
    }

    public function removeUniqFoto()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $foto_id = $this->uri_segment[2];
        }
        elseif ( isset( $_POST['foto_id'] ) && !empty( $_POST['foto_id'] ) )
        {
            $foto_id = $_POST['foto_id'];
        }
        if ( isset( $foto_id ) )
        {
            $this->select()
                    ->from( 'foto' )
                    ->where( "foto_id = $foto_id" )
                    ->execute();
            if ( $this->result() )
            {
                $f = ( object ) $this->data[0];
                $file = "app/fotos/$f->foto_url";
                if ( file_exists( $file ) )
                {
                    @unlink( $file );
                    echo "$file removido";
                }
                $this->delete()->from( 'foto' )->where( "foto_id = $foto_id" )->execute();
            }
            else
            {
                echo 'error';
            }
        }
    }

    public function updateFotoPos()
    {
        $item = $_POST['item'];
        parse_str( $item, $arr );
        foreach ( $arr['li'] as $pos => $foto_id )
        {
            $this->update( 'foto' )
                    ->set( array( 'foto_pos' ), array( "$pos" ) )
                    ->where( "foto_id = $foto_id" )
                    ->execute();
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