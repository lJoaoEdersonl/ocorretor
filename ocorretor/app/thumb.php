<?php

class Thumb extends PHPFrodo
{

    public function __construct()
    {
        parent::__construct();
    }

    public function welcome()
    {
        if ( isset( $this->uri_segment[1] ) )
        {
            $pic = "app/fotos/" . $this->uri_segment[1] . ".jpg";
            if ( !file_exists( $pic ) )
            {
                $pic = "app/images/default/nopic.jpg";
            }
            $this->helper( 'canvas' );
            $t = new Canvas;
            $t->carrega( $pic );
            if ( isset( $this->uri_segment[4] ))
            {
                $t->redimensiona( $this->uri_segment[2], $this->uri_segment[3], 'crop' );
            }
            else
            {
                $t->redimensiona( $this->uri_segment[2], $this->uri_segment[3] );
            }
            if ( isset( $this->uri_segment[5] ))
            {
                if($this->uri_segment[5] == 1)
                $t->marca( 'app/images/layout/vendido.png', 'meio', 'esquerda');
                elseif($this->uri_segment[5] == 2)
                $t->marca( 'app/images/layout/alugado.png', 'meio', 'esquerda');
            }
            $t->grava();
        }
    }

}

/*end file*/