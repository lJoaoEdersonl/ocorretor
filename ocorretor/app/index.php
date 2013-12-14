<?php

class Index extends PHPFrodo
{

    public $config = array( );
    public $menu;

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
            $this->assignAll();
        }
    }
	
    public function welcome()
    {
        $this->tpl( 'public/index.html' );
		
		/*
		$this->select()
                ->from( 'item' )
                ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' )
                ->join( 'foto', 'foto_item = item_id', 'INNER' )
                ->where( 'foto_pos = 0 and item_show = 1 and item_destaque = 1' )
                ->paginate( 24 )
                ->groupby( 'item_id' )
                ->orderby( 'item_id desc' )
                ->execute();
        if ( $this->result() )
        {
            $this->encode( 'sub_title', 'ucwords' );
            $this->money( 'item_preco' );
            $this->money( 'item_preco_locacao' );
            $this->preg( '/\.jpg/', '', 'foto_url' );
            $this->fetch( 'i', $this->data );
            if ( !isset( $this->uri_segment[2] ) )
            {
                $this->assign( 'categoria_active', 'hider' );
            }
        }
		*/
		
        $this->render();
    }
    public function fillSlideShow()
    {
        $this->select()
                ->from( 'item' )
                ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' )
                ->join( 'foto', 'foto_item = item_id', 'INNER' )
                ->where( 'foto_pos = 0 and item_show = 1 and item_slide = 1' )
                //->paginate( 24 )
                ->groupby( 'item_id' )
                ->orderby( 'item_id desc' )
                ->execute();
        if ( $this->result() )
        {
            $this->encode( 'sub_title', 'ucwords' );
            $this->money( 'item_preco' );
            $this->money( 'item_preco_locacao' );
            $this->preg( array( '/1/', '/2/', '/3/', '/4/' ), array( 'Venda', 'Locação', 'Locação e Venda', 'Temporada' ), 'item_finalidade' );
            $this->preg( '/\.jpg/', '', 'foto_url' );
            $this->fetch( 'sl', $this->data );
        }
    }

    public function fillTipo( $ori = null )
    {
        $this->select()
                ->from( 'tipo' )
                ->join( 'item', 'item_tipo = tipo_id', 'INNER' )
                ->groupby( 'tipo_id' )
                ->orderby( 'tipo_title asc' )
                ->execute();
        if ( $this->result() )
        {
            if ( $ori == null )
            {
                $this->fetch( 'tp', $this->data );
            }
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

    public function fillSub()
    {
        if ( isset( $this->uri_segment[2] ) )
        {
            $this->categoria_id = $this->uri_segment[2];
        }
        $this->select()
                ->from( 'sub' )
                ->join( 'item', 'item_sub = sub_id', 'INNER' )
                ->where( "sub_categoria = $this->categoria_id" )
                ->orderby( 'sub_title asc' )
                ->execute();
        if ( $this->result() )
        {
            $this->encode( 'sub_title', 'utf8_encode' );
            @header( 'Content-Type: text/html; charset=iso-8859-1' );
            echo json_encode( $this->data );
        }
    }

    public function buscaavancada()
    {
        $this->tpl( 'public/busca.html' );
        $cond = "";
        $load = "";
        $loc = null;
        if ( in_array( 'finalidade', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'finalidade' );
            if ( $k )
            {
                $finalidade = $this->uri_segment[$k[0] + 1];
                if ( $finalidade > 0 )
                {
                    if ( $finalidade == 2 )
                    {
                        $loc = 3;
                    }
                }
            }
            unset( $k );
        }

        if ( in_array( 'tipo', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'tipo' );
            if ( $k )
            {
                $tipo = $this->uri_segment[$k[0] + 1];
                if ( $tipo > 0 )
                {
                    $cond .= "item_tipo = $tipo AND ";
                }
                $load .= "$('#tipo').val('$tipo');\n";
            }
            unset( $k );
        }

        if ( in_array( 'dorms', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'dorms' );
            if ( $k )
            {
                $dorm = $this->uri_segment[$k[0] + 1];
                if ( $dorm > 0 )
                {
                    $cond .= "item_dorm = $dorm AND ";
                }
                $load .= "$('#dorms').val('$dorm');\n";
            }
            unset( $k );
        }

        if ( in_array( 'suites', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'suites' );
            if ( $k )
            {
                $suite = $this->uri_segment[$k[0] + 1];
                if ( $suite > 0 )
                {
                    $cond .= "item_suite = $suite AND ";
                }
                $load .= "$('#suites').val('$dorm');\n";
            }
            unset( $k );
        }

        if ( in_array( 'bairro', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'bairro' );
            if ( $k )
            {
                $bairro = $this->uri_segment[$k[0] + 1];
                if ( $bairro > 0 )
                {
                    $cond .= "item_sub = $bairro  AND ";
                }
                $load .= "$('#bairro').val('$bairro');\n";
            }
            unset( $k );
        }
        if ( in_array( 'cidade', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'cidade' );
            if ( $k )
            {
                $cidade = $this->uri_segment[$k[0] + 1];
                if ( $cidade > 0 )
                {
                    $cond .= "item_categoria = $cidade  AND ";
                }
                $load .= "$('#cidade').val('$cidade');\n";
                $load .= "loadSub('$cidade');\n";
            }
            unset( $k );
        }

        if ( $loc == 1 )
        {
            if ( in_array( 'min', $this->uri_segment ) )
            {
                $k = array_keys( $this->uri_segment, 'min' );
                if ( $k )
                {
                    $preco = $this->uri_segment[$k[0] + 1];
                    if ( $preco > 0 )
                    {
                        $cond .= "item_preco >= $preco AND ";
                    }
                    $load .= "$('#valormin').val('$preco');\n";
                }
                unset( $k );
            }
            if ( in_array( 'max', $this->uri_segment ) )
            {
                $k = array_keys( $this->uri_segment, 'max' );
                if ( $k )
                {
                    $preco = $this->uri_segment[$k[0] + 1];
                    if ( $preco > 0 )
                    {
                        $cond .= "item_preco <= $preco  AND ";
                    }
                    $load .= "$('#valormax').val('$preco');\n";
                }
                unset( $k );
            }
        }

        if ( in_array( 'finalidade', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'finalidade' );
            if ( $k )
            {
                $finalidade = $this->uri_segment[$k[0] + 1];
                if ( $finalidade > 0 )
                {
                    $cond .= "item_finalidade = $finalidade";
                }
                $load .= "$('#finalidade').val('$finalidade');\n";
            }
            unset( $k );
        }

        if ( isset( $finalidade ) && $finalidade == 2 )
        {
            $cond .= " OR  " . preg_replace( '/item_finalidade \= 2/', 'item_finalidade  = 3', $cond );
        }
        $load .="$('.bot-panel').click();\n\r\t\t";

        if ( in_array( 'imref', $this->uri_segment ) )
        {
            $k = array_keys( $this->uri_segment, 'imref' );
            if ( $k )
            {
                if ( isset( $this->uri_segment[$k[0] + 1] ) && !empty( $this->uri_segment[$k[0] + 1] ) )
                {
                    $imref = trim( $this->uri_segment[$k[0] + 1] );
                    if ( trim( $imref ) != "" )
                    {
                        $cond = "item_ref = '$imref'";
                        $load .= "$('#imref').val('$imref');\n";
                    }
                }
            }
            unset( $k );
        }
        $this->assign( 'load', $load );
        $this->select()
                ->from( 'item' )
                ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                ->join( 'categoria', 'item_categoria = categoria_id', 'INNER' )
                ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                ->join( 'foto', 'foto_item = item_id', 'INNER' )
                ->where( "$cond" )
                //->where( "$cond and item_vendido = 0" )
                ->groupby( 'item_id' )
                ->orderby( 'item_id desc' )
                ->execute();
        if ( $this->result() )
        {
            $this->money( 'item_preco' );
            $this->money( 'item_preco_locacao' );
            $this->preg( '/\.jpg/', '', 'foto_url' );
            $data = $this->data;
            $this->fetch( 'i', $this->data );
        }
        else
        {
            $this->assign( 'noResult', 'Nenhum imóvel econtrado com os critérios selecionados!' );
        }
        $this->assign( 'busca', "" );
        $this->fillTipo(1);
        $this->fillCategoria();
        $this->render();
    }

    public function busca()
    {
        $this->tpl( 'public/busca.html' );
        if ( isset( $_POST['busca'] ) && !empty( $_POST['busca'] ) )
        {
            $busca = preg_replace( array( '/\s+/' ), array( ' ' ), $_POST['busca'] );
            $term = preg_replace( array( '/\s+/', '/\s+(em)/' ), array( ' ', '' ), $_POST['busca'] );
            $parts = explode( " ", $term );
            $part1 = $parts[0];
            $cond = "item_busca like '%$term%' OR ";
            $cond .= "item_ref like'%$term%' OR ";
            $cond .= "tipo_title like'%$term%' OR ";
            $cond .= "categoria_title like'%$term%' OR ";
            //$cond .= "item_busca like '%$part1%' OR ";
            $cond .= "sub_title like'%$term%' ";
            if ( @is_nan( $busca ) )
            {
                $cond = "item_ref = '$busca'";
            }
            $this->select()
                    ->from( 'item' )
                    ->join( 'sub', 'item_sub = sub_id', 'INNER' )
                    ->join( 'categoria', 'sub_categoria = categoria_id', 'INNER' )
                    ->join( 'tipo', 'item_tipo = tipo_id', 'INNER' )
                    ->join( 'foto', 'foto_item = item_id', 'INNER' )
                    ->where( "$cond" )
                    ->groupby( 'item_id' )
                    ->orderby( 'item_id desc' )
                    ->execute();
            if ( $this->result() )
            {
                $this->money( 'item_preco' );
                $this->money( 'item_preco_locacao' );
                $this->preg( '/\.jpg/', '', 'foto_url' );
                $data = $this->data;
                $this->fetch( 'i', $this->data );
            }
        }
        else
        {
            $this->redirect( "$this->baseUri/" );
        }
        $this->fillTipo( 1 );
        $this->fillCategoria();
        $this->assign( 'busca', "$busca" );
        $this->render();
    }

    public function institucional()
    {
        $onload = "goToInstitucional();";
        $this->assign( 'onload', $onload );
        $this->welcome();
    }

    public function localizacao()
    {
        $onload = "goToMapa();";
        $this->assign( 'onload', $onload );
        $this->welcome();
    }

    public function atendimento()
    {
        $onload = "goToContato();";
        $this->assign( 'onload', $onload );
        $this->welcome();
    }

}

/*end file*/