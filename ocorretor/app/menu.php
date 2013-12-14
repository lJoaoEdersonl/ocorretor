<?php

class Menu extends PHPFrodo
{

    public $menulist = array( );

    public function __construct()
    {
        parent:: __construct();
    }

    public function get()
    {
        $this->select()
                ->from( 'categoria' )
                ->join('sub','sub_categoria = categoria_id','INNER')
                ->join('item','item_sub = sub_id','INNER')
                ->groupby('categoria_id')
                ->orderby( 'categoria_title asc' )
                ->execute();
        if ( $this->result() )
        {
            $data = $this->data;
            foreach ( $data as $k => $v )
            {
                $categoria_id = $v['categoria_id'];
                $this->select()
                        ->from( 'sub' )
                        ->join('item','item_sub = sub_id','INNER')
                        ->where( "sub_categoria = $categoria_id" )
                        ->groupby('sub_id')
                        ->orderby( 'sub_title asc' )
                        ->execute();
                if ( $this->result() )
                {
                    //$this->printr($this->data);
                    $data[$k]['sub'] = $this->data;
                    foreach ( $data[$k]['sub'] as $subs => $sub )
                    {
                        $item_id = $sub['sub_id'];
                        $this->select()->from( 'item' )->where( "item_sub = $item_id" )->execute();
                        if ( $this->result() )
                        {
                            $data[$k]['sub'][$subs]['itens'] = count( $this->data );
                        }
                        else
                        {
                            $data[$k]['sub'][$subs]['itens'] = 0;
                        }
                    }
                }
                else{
                     $data[$k]['sub'] = array(array());
                }
            }
            return $data;
        }
        else{
            return array(array('' => ''));
        }
    }

}

/*end file*/