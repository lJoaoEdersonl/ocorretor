<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html" />
<title>O Corretor Online</title>
<meta name="description" content="O Corretor Online surgiu a partir da ideia de revolucionar o mercado imobiliário trazendo para dentro da sua casa corretores especializados, capacitados a atender suas expectativas.">
<meta name="keywords" content="imoveis, casa, terreno, alugar, apartamento, kitnet, condominio, anunciar imovel, sitio, fazenda, chacara, valor, imóvel, corretor"> 
<meta name="author" content="Spell Tech"> 
<link rel="shortcut icon" href="img/logo_mini.png">
<link href="css/all.css" rel="stylesheet" type="text/css" />
<link href="css/menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/menu.js"></script>

</head>

<?php 
	$db = mysql_connect('localhost', 'ocorr671', 'Mejor702');
	$dados = mysql_select_db('ocorr671_dbimob', $db);
?>

<body>

    <div id="top">
           
          <div id="topo_logo">
            <a href="index.php?pagina=page/home"><img class="logo" src="img/logo.png"></a>
            <div class="ilust"></div>
            
          </div>
          
           <div id="parttop">

           <div id="menu">
    <ul class="menu">
       <li><a href="index.php?pagina=page/home"><span>Home</span></a></li>
        <li><a href="index.php?pagina=page/page&amp;paginas=2"><span>Sobre</span></a></li>
        
        <li><a href="#" class="parent"><span>Imoveis</span></a>
            <div class="columns two">
                <ul class="one">
				
					<?php 
						$strSQLMenu = mysql_query("SELECT tipo.tipo_id, tipo.tipo_title FROM tipo INNER JOIN item On item.item_tipo = tipo.tipo_id WHERE item.item_show = 1 GROUP BY tipo_title"); 
						while($linha = mysql_fetch_array($strSQLMenu)){
							$categorias = $linha['tipo_title'];
							$tipo_id = $linha['tipo_id'];
					?>		
						<li><a href="index.php?pagina=page/page&amp;paginas=3&tipo_id=<?php echo($tipo_id) ?>#todos"><span><?php echo($categorias) ?></span></a></li>
					<?php 
						}
					?>
                </ul>
            </div>
        </li>
       <li><a href="index.php?pagina=page/page&amp;paginas=4"><span>Serviços</span></a></li>
        <li><a href="index.php?pagina=page/page&amp;paginas=3#todos"><span>Novidades</span></a></li>
        <li><a href="index.php?pagina=page/page&amp;paginas=5"><span>Contato</span></a></li>
    </ul>
</div>

<div id="copyright"><a href="http://apycom.com/"></a></div>
            </div><!--Parttopo-->
           </div><!-- topo -->
           
       
        <div id="conteudo1">
           <div id="parte1"> 
    