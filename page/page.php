<?php if($_GET['paginas'] == 'home'){?>
<?php
}
?>




<?php if($_GET['paginas'] == '2'){?>
       <div class="center"> 
        <div id="sobre">
           
           <h3>Sobre</h3>
           
           <div id="box_sobre">
           
                <?php 
                    $strSQL = mysql_query("SELECT config_site_about FROM config"); 
                    while($linha = mysql_fetch_array($strSQL)){
                      $textoSobre = $linha['config_site_about'];
                ?> 
              
              <?php echo($textoSobre) ?>

              <?php 
                }
              ?>
                
          </div>
            
           </div><!-- Sobre -->
           </div><!-- Center -->
           </div><!-- Content1 -->
<?php
}
?>







<?php if($_GET['paginas'] == '3'){?>
   
           <div id="parte2">
              <div class="box1">
                <?php 
                    $strSQL = mysql_query("SELECT config_site_text, config_site_description FROM config"); 
                    while($linha = mysql_fetch_array($strSQL)){
                      $textoSlogan = $linha['config_site_description'];
                      $textoPesquisa = $linha['config_site_text'];
                ?> 
                 <p><br /><?php echo($textoSlogan) ?><br />     
                 <span> <?php echo($textoPesquisa) ?> </span>  
                 </p>

                <?php 
                  }
                ?>
              
              </div>
               <div class="box2">
                <h3>PESQUISA DINAMICA</h3>
                
                <form action="index.php#todos" method="get">
              
               <div id="fp2">
              
               <input type="hidden" name="pagina" value="page/page" />
               <input type="hidden" name="paginas" value="3" />
          
               <label> Cidade: </label><br />
               
                <select>
                  <option>Todas</option>
                <?php 
                    $strSQLCategoria = mysql_query("SELECT categoria_id, categoria_title FROM categoria INNER JOIN item ON item.item_categoria = categoria.categoria_id AND item.item_show = '1' GROUP BY categoria_title "); 
                    while($linha = mysql_fetch_array($strSQLCategoria)){
                    $cidade = $linha['categoria_title'];
                    $Categoria_id = $linha['categoria_id'];
                ?> 
                
                <option name ="<?php echo($Categoria_id) ?>" id="<?php echo($Categoria_id) ?>"><?php echo($cidade) ?> </option>
                <?php 
                  }
                ?>

                </select><br /><br />
          
                <label>Bairro/Região</label><br />
                <select name = "item_sub" id = "item_sub" value = "item_sub">
                  <option>Todos</option>
                  <?php 
                    $strSQLBairro = mysql_query("SELECT sub_id, sub_title FROM sub INNER JOIN item ON item.item_sub = sub.sub_id AND item.item_show = '1' GROUP BY sub_title "); 
                    while($linha = mysql_fetch_array($strSQLBairro)){
                    $bairro = $linha['sub_title'];
                    $sub_id = $linha['$sub_id'];
                  ?> 

                  <option name ="<?php echo($sub_id) ?>" id="<?php echo($sub_id) ?>"> <?php echo($bairro) ?> </option>
                  
                  <?php 
                    }
                  ?>

                </select><br /><br />
        
                <label>Área em metros:</label><br />
                <select name = "item_area" id = "item_area" value = "item_area">
                  <option>Todos</option>
                  <option>Até 50</option>
                  <option>50-100</option>
                  <option>100-150</option>
                  <option>150-200</option>
                  <option>200-300</option>
                  <option>300-400</option>
                  <option>400+</option>
                </select>
             </div>
             
             
             <div id="fp2">
                <label>Tipo:</label><br />
                <select name = "tipo_title" id = "tipo_title" value = "tipo_title">
                  <option>Todos</option>
                  <?php 
                    $strSQLTipo = mysql_query("select tipo.tipo_id, tipo.tipo_title FROM tipo inner join item on item.item_tipo = tipo.tipo_id where item.item_show = '1' group by tipo_title"); 
                    while($linha = mysql_fetch_array($strSQLTipo)){
                    $tipo = $linha['tipo_title'];
                    $tipo_id = $linha['tipo_id'];
                  ?> 

                 <option name ="<?php echo($tipo_id) ?>" id="<?php echo($tipo_id) ?>"><?php echo($tipo) ?></option> 

                  <?php 
                    }
                  ?>

                </select><br /><br />
          
               <label> Valor: </label><br />
                <select name = "item_preco" id = "item_preco" value = "item_preco">
                 <option>Qualquer valor</option>
                 <option>-50.000</option>
                 <option>50.000-100.000</option>
                 <option>100.000-200.000</option>
                 <option>200.000-300.000</option>
                 <option>300.000-400.000</option>
                 <option>400.000-500.000</option>
                 <option>500.000-1.000.000</option>
                 <option>1.000.000-3.000.000</option>  
                 <option>Acima de 3.000.000</option>
                </select><br /><br />
          
                
                 <label>Quartos:</label><br />
                <select name = "item_dorm" id = "item_dorm" value = "item_dorm">
                  <option>Selecione</option>
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                  <option>Acima de 5</option>
                </select><br /><br />
                
                <input type="submit" name="pesquisa" id="pesquisa" value="Pesquisar" />
                </form>
                
              </div>
           </div>
        </div>
        
        
        
       <div class="center1"> 
        <div id="novidades">
           
           <h3 id="todos">Resultado da Pesquisa</h3>
           
            <?php 

               $cSQL = " SELECT item_mes, item_dia, item_id, item_nome, item_desc, item_preco, MIN(foto_url) as 'foto_url', item_mostra_valor FROM item INNER JOIN foto ON foto_item = item_id  AND foto_pos = (SELECT MIN( foto_pos ) FROM foto WHERE foto_item = item_id) INNER JOIN tipo ON tipo.tipo_id = item.item_tipo INNER JOIN categoria ON categoria.categoria_id = item.item_categoria INNER JOIN sub ON sub.sub_id = item.item_sub  WHERE COALESCE(item_mes, '') <> '' AND COALESCE(item_dia, '') <> '' AND item_show = '1' ";
               

                If(ISSET($_GET['item_dorm'])){
                  if($_GET['item_dorm'] == "Selecione"){
                     $cSQL = $cSQL . " " ;
                  }elseif($_GET['item_dorm'] == "Acima de 5"){
                     $cSQL = $cSQL . " AND item_dorm > 5 ";
                  }else{
                    $cSQL = $cSQL . " AND item_dorm = '" . $_GET['item_dorm'] . "' ";
                  }
                }

                If(ISSET($_GET['item_area'])){
                  If($_GET['item_area'] == "Todos"){
                      $cSQL = $cSQL . " ";
                  }elseif($_GET['item_area'] == "Até 50"){
                    $cSQL = $cSQL . " AND item_area <= 50 ";
                  }elseif($_GET['item_area'] == "50-100"){
                      $cSQL = $cSQL . " AND item_area >= 50 and item_area <= 100 ";
                  }elseif($_GET['item_area'] == "100-150"){
                      $cSQL = $cSQL . " AND item_area >= 100 and item_area <= 150 ";
                  }elseif($_GET['item_area'] == "150-200"){
                      $cSQL = $cSQL . " AND item_area >= 150 and item_area <= 200 ";
                  }elseif($_GET['item_area'] == "200-300"){
                      $cSQL = $cSQL . " AND item_area >= 200 and item_area <= 300 ";
                  }elseif($_GET['item_area'] == "300-400"){
                      $cSQL = $cSQL . " AND item_area >= 300 and item_area <= 400 ";
                  }else{
                      $cSQL = $cSQL . " AND item_area >= 400 ";
                  }
                }

                If(ISSET($_GET['item_preco'])){

                  If($_GET['item_preco'] == "Qualquer valor"){
                    $cSQL = $cSQL . " ";
                  }elseif($_GET['item_preco'] == "-50.000"){
                    $cSQL = $cSQL . " AND item_preco <= 50000";
                  }elseif($_GET['item_preco'] == "50.000-100.000"){
                      $cSQL = $cSQL . " AND item_preco >= 50000 and item_preco <= 100000 ";
                  }elseif($_GET['item_preco'] == "100.000-200.000"){
                      $cSQL = $cSQL . " AND item_preco >= 100000 and item_preco <= 200000 ";
                  }elseif($_GET['item_preco'] == "200.000-300.000"){
                      $cSQL = $cSQL . " AND item_preco >= 200000 and item_preco <= 300000 ";
                  }elseif($_GET['item_preco'] == "300.000-400.000"){
                      $cSQL = $cSQL . " AND item_preco >= 300000 and item_preco <= 400000 ";
                  }elseif($_GET['item_preco'] == "400.000-500.000"){
                      $cSQL = $cSQL . " AND item_preco >= 400000 and item_preco <= 500000 ";
                  }elseif($_GET['item_preco'] == "500.000-1.000.000"){
                      $cSQL = $cSQL . " AND item_preco >= 500000 and item_preco <= 1000000 ";
                  }elseif($_GET['item_preco'] == "1.000.000-3.000.000"){
                      $cSQL = $cSQL . " AND item_preco >= 1000000 and item_preco <= 3000000 ";
                  }else{
                      $cSQL = $cSQL . " AND item_preco >= 3000000 ";
                  }
                }
                
                If(ISSET($_GET['tipo_id'])){
                  $cSQL = $cSQL . " AND tipo.tipo_id = '" . $_GET['tipo_id'] . "' ";
                }

                If(ISSET($_GET['tipo_title'])){
                  If($_GET['tipo_title'] == "Todos"){
                    $cSQL = $cSQL . " ";
                  }else{
                    $cSQL = $cSQL . " AND tipo.tipo_title like '%" . $_GET['tipo_title'] . "%' ";
                  }
                }

                If(ISSET($_GET['categoria_id'])){
                  If($_GET['categoria_id'] == "Todas"){
                    $cSQL = $cSQL . " ";
                  }else{
                    $cSQL = $cSQL . " AND categoria.categoria_title like '%" . $_GET['categoria_id'] . "%' ";
                  }
                  
                }

                If(ISSET($_GET['item_sub'])){
                  If($_GET['item_sub'] == "Todos"){
                    $cSQL = $cSQL . " ";
                  }else{
                    $cSQL = $cSQL . " AND sub.sub_title like '%" . $_GET['item_sub'] . "%' ";
                  }
                }
                  
              
               $cSQL = $cSQL . " GROUP BY item_mes, item_dia, item_id, item_nome, item_desc, item_preco ORDER BY CASE UCASE(item_mes) WHEN 'JANEIRO' THEN 1 WHEN 'FEVEREIRO' THEN 2 WHEN 'MARÇO' THEN 3 WHEN 'ABRIL' THEN 4 WHEN 'MAIO' THEN 5 WHEN 'JUNHO' THEN 6 WHEN 'JULHO' THEN 7 WHEN 'AGOSTO' THEN 8 WHEN 'SETEMBRO' THEN 9 WHEN 'OUTUBRO' THEN 10 WHEN 'NOVEMBRO' THEN 11 WHEN 'DEZEMBRO' THEN 12 END DESC, item_dia desc, item_mostra_valor";

              
                $strSQL = mysql_query($cSQL) or die ( mysql_error() ); 
                while($linha = mysql_fetch_array($strSQL)){
                $novidades_id = $linha['item_id'];
                $novidades_nome = $linha['item_nome'];
                $novidades_desc = $linha['item_desc'];
                $novidades_preco = $linha['item_preco'];
                $novidades_dia = $linha['item_dia'];
                $novidades_mes = $linha['item_mes'];
                $novidades_url = $linha['foto_url'];
                $mostra_valor = $linha['item_mostra_valor'];
            ?> 

             <div class="casa">
              <img src="ocorretor/app/fotos/<?php echo($novidades_url) ?>" />
               <div class="box_txt">
                <p class="data1"><?php echo($novidades_nome) ?></p><br /><br />
                <p class="texto"><?php echo($novidades_desc) ?></p>
                 <p class="botao"><a href="index.php?pagina=page/page&amp;paginas=6&id=<?php Echo($novidades_id) ?>">Avaliar</a></p>
                 <?php if($mostra_valor == 1) { ?> 
                 <p class="preco">Valor: <span class="valor">R$ <?php echo(number_format($novidades_preco, 2, ',', '.')) ?></span></p>
                <?php } ?>
                 <br /><br />
             </div>
             <h2><?php echo($novidades_dia) ?><br /><span class="data2"><?php echo($novidades_mes) ?></span></h2>
             </div>

            <?php 
              }
            ?>
           
         
        </div><!-- novidades -->
        </div><!-- Center -->
       </div>
<?php
}
?>









<?php if($_GET['paginas'] == '4'){?>
        
       <div class="center"> 
        <div id="sobre">
           
           <h3>Serviços</h3>
           
           <div id="box_sobre">
           
          <?php 
              $strSQL = mysql_query("SELECT servicos_text FROM servicos"); 
              while($linha = mysql_fetch_array($strSQL)){
                $Servicos = $linha['servicos_text'];

                echo($Servicos); 
          }
          ?> 
              

                
          </div>
            
           </div><!-- Sobre -->
           </div><!-- Center -->
           </div><!-- Content2 -->
        
<?php
}
?>









<?php if($_GET['paginas'] == '5'){ ?>

         <div class="center"> 
        <div id="sobre" class="contatos">
           
           <h3>Contatos</h3>
           
          <?php 
              $strSQL = mysql_query("SELECT cliente_convite FROM cliente"); 
              while($linha = mysql_fetch_array($strSQL)){
                $convite = $linha['cliente_convite'];
          ?> 

           <div id="box_sobre">
           <p class="info"><?php echo($convite) ?></p>

           <?php } ?>

             <form action="mailto:contato@ocorretoronline.com.br?subject=Assunto" method="post" enctype="text/plain">
          
              <label>Nome</label><br />
                 <input type="text" name="nome" maxlength="100" /><br />
              <label>E-mail</label><br />
                 <input type="email" name="email"/><br />
              <label>Assunto</label><br />
                 <input type="text" name="Assunto" /> <br />
              <label>Texto</label><br />
                 <textarea name="texto" ></textarea>
               <input type="submit" value="Enviar" id="enviar" />  
             </form>
                
          </div>
            
           </div><!-- Sobre -->
           </div><!-- Center -->
<?php
}
?>



<?php if($_GET['paginas'] == '6'){?>
       
 <div class="center"> 
        <div id="sobre">
      
        <?php 
            $strSQL = mysql_query("SELECT item_views, item.item_nome, item.item_desc, item.item_preco, item.item_area, item.item_dorm, item.item_wc, item.item_suite, item.item_mostra_valor, item.item_vaga, categoria.categoria_title, sub.sub_title, MIN(foto.foto_url) as 'foto_url' FROM item INNER JOIN categoria ON item.item_categoria = categoria.categoria_id INNER JOIN sub ON item.item_sub = sub.sub_id INNER JOIN foto ON foto_item = item_id  AND foto_pos = (SELECT MIN( foto_pos ) FROM foto WHERE foto_item = item_id) WHERE item.item_id = '" . $_GET['id'] . "' GROUP BY item.item_nome, item.item_desc, item.item_preco, item.item_area, item.item_dorm, item.item_wc, item.item_suite, item.item_mostra_valor, item.item_vaga, categoria.categoria_title, sub.sub_title, item_views"); 
            
            while($linha = mysql_fetch_array($strSQL)){
            $item_nome = $linha['item_nome'];
            $item_desc = $linha['item_desc'];
            $item_preco = $linha['item_preco'];
            $item_area = $linha['item_area'];
            $item_dorm = $linha['item_dorm'];
            $item_wc = $linha['item_wc'];
            $item_suite = $linha['item_suite'];
            $mostra_valor = $linha['item_mostra_valor'];
            $item_vaga = $linha['item_vaga'];
            $item_categoriaDesc = $linha['categoria_title'];
            $item_subDesc = $linha['sub_title'];
            $item_foto = $linha['foto_url'];
            $item_views = $linha['item_views'];

            $item_views = $item_views + 1;

            $strSQL_UPDATE = mysql_query("UPDATE item SET item_views =" . $item_views . " WHERE item.item_id = '" . $_GET['id'] . "' ");
        }

        ?> 
      


           <h3><?php echo($item_nome) ?></h3>
           
           <div id="box_sobre">
             
      
              
       <div id="box">       
               
      <div id="box_dados-imovel">
      <img class="imovel" src="ocorretor/app/fotos/<?php echo($item_foto) ?>" />
     <p class="t">Informações do Imovel<span>
     
            
               <a href="https://www.facebook.com/ocorretoronlineoficial?ref=ts&fref=ts"><img src="img/facebook.jpg" /></a>
               <a href="https://twitter.com/OCOoficial"> <img src="img/twitter.jpg" /></a>
               <a href="#"> <img src="img/google.jpg" /></a>
               </span></p>
    
    
    
    
     <p class="txt_dados">
        <?php echo($item_desc) ?>
    
         </p> 
          
 <div class="bt_contato"><a href="index.php?pagina=page/page&amp;paginas=5">Entrar em Contato</a></div>


 <?php if($mostra_valor == 1) { ?> 
    <p class="preco">Valor: <span class="valor">R$ <?php echo(number_format($item_preco, 2, ',', '.')) ?></span></p>
 <?php } ?>        
</div><!-- Box -->


    </div><!--box_dados-imovel-->   
         



<div id="sled_casa">
<!-- Begin DWUser_EasyRotator -->
<script type="text/javascript" src="js/rotator.js"></script>
<div class="dwuserEasyRotator" style="width: 900px; height: 600px; position:relative; text-align: left;" data-erConfig="{lpp:'102-105-108-101-58-47-47-47-67-58-47-85-115-101-114-115-47-104-101-110-114-105-95-48-48-48-47-68-111-99-117-109-101-110-116-115-47-69-97-115-121-82-111-116-97-116-111-114-80-114-101-118-105-101-119-47-112-114-101-118-105-101-119-95-115-119-102-115-47', wv:1, randomize:true, autoplayGalleryLoop:true}" data-erName="imovel">
	<div data-ertype="content" style="display: none;"><ul data-erlabel="Main Category">
	
  <?php $strSQL = mysql_query("SELECT foto_url FROM foto WHERE foto_item = '" . $_GET['id'] . "' ORDER BY foto.foto_pos");
    
    while($linha = mysql_fetch_array($strSQL)) {
      $foto = $linha['foto_url'];
  ?>

  <li>
		<img class="main" src="ocorretor/app/fotos/<?php echo($foto) ?>" />
		<img class="thumb" src="ocorretor/app/fotos/<?php echo($foto) ?>" />
	</li>
  
  <?php }  ?>

</ul>
</div>
	<div data-ertype="layout" data-ertemplateName="NONE" style="">
		
		<div class="erimgMain" style="position: absolute; left:0;right:0;top:0;bottom:70px;" data-erConfig="{___numTiles:3, scaleMode:'fillArea', imgType:'main', __loopNextButton:false, arrowButtonMode:'rollover'}">
			<div class="erimgMain_slides" style="position: absolute; left:0px; top:0; bottom:0; right:0px;">
				<div class="erimgMain_slide">
					<div class="erimgMain_img" style="position: absolute; left: 0; right: 0; top: 0; bottom: 0;"></div>
					<div class="erhideWhenNoText" style="background: #000; background: rgba(0,0,0,0.85); position: absolute; left: 0; right: 0; bottom: 0; padding: 5px; color: #FFF; font-family: Arial; font-size: 12px;">
						<p class="erimgMain_title" style="padding: 0; margin: 0 0 3px 0; font-weight: bold;"></p>
						<p class="erimgMain_desc" style="padding: 0 0 10px 0; margin: 0;"></p>
					</div>
				</div>
			</div>
			<div class="erimgMain_arrowLeft" style="position:absolute; left: 10px; top: 50%; margin-top: -15px;" data-erConfig="{image:'circleSmall', image2:'circleSmall'}"></div>
			<div class="erimgMain_arrowRight" style="position:absolute; right: 10px; top: 50%; margin-top: -15px;"></div>
		</div>
		<div class="erimgMain rotatorTileNav" style="position: absolute; left:0;right:0;bottom:0;height:80px;" data-erConfig="{numTiles:-1, scaleMode:'fillArea', imgType:'thumb', loopNextButton:false, arrowButtonMode:'rollover', __slideLinkEvent:'rollover'}">
			<div style="position: absolute; left: 0; top: 10px; right: 0; bottom: 0; background: #FFF;"></div>
			<div class="erimgMain_slides" style="position: absolute; left:0px; top:0; bottom:0; right:0px;">
				<div class="erimgMain_slide">
					<div class="erimgMain_img" style="position: absolute; left: 0; right: 0; top: 10px; bottom: 0; margin: 2px 1px;"></div>
					<!-- <div class="" style="background: #555; position: absolute; left: 1px; right: 1px; top: 10px; bottom: 0; padding: 5px; color: #FFF; font-family: Arial; font-size: 12px; text-align: center;">
						<p class="erimgMain_title" style="padding: 5px; margin: 0 0 3px 0; font-weight: bold;"></p>
					</div> -->
					<div class="selectionArrow visibleWhenSelected" style="position: absolute; top: 0; left: 50%; margin-left: -10px; width: 20px; height: 10px; background-image: url('http://easyrotator.s3.amazonaws.com/1/i/rotator/FFF_arrow10_export.png');"></div>
				</div>
			</div>
			<div class="erimgMain_arrowLeft" style="position:absolute; left: 60px; top: 50%; margin-top: -10px;" data-erConfig="{image:'circleSmall', image2:'circleSmall'}"></div>
			<div class="erimgMain_arrowRight" style="position:absolute; right: 60px; top: 50%; margin-top: -10px;"></div>
		</div>
				
		
		<div class="erabout erFixCSS3" style="color: #FFF; text-align: left; background: #000; background:rgba(0,0,0,0.93); border: 2px solid #FFF; padding: 20px; font: normal 11px/14px Verdana,_sans; width: 300px; border-radius: 10px; display:none;">
			<a style="color:#FFF;" href="http://www.dwuser.com/easyrotator/" target="_blank"></a> <a style="color:#FFF;" href="http://www.dwuser.com/easyrotator/" target="_blank"></a>
			<br /><br />
          <a style="color:#FFF;" href="http://www.dwuser.com/easyrotator/wordpress/" target="_blank"></a> <a style="color:#FFF;" href="http://www.dwuser.com/easyrotator/wordpress/" target="_blank"></a> 
			<br /><br />
			<a style="color:#FFF;" href="#" class="erabout_ok"></a>   
		</div>
		<noscript>
			 <a href="http://www.dwuser.com/easyrotator/"></a>
		</noscript>
		<script type="text/javascript">/*Avoid IE gzip bug*/(function(b,c,d){try{if(!b[d]){b[d]="temp";var a=c.createElement("script");a.type="text/javascript";a.src="http://easyrotator.s3.amazonaws.com/1/js/nozip/easy_rotator.min.js";c.getElementsByTagName("head")[0].appendChild(a)}}catch(e){alert("EasyRotator fail; contact support.")}})(window,document,"er_$144");</script>
	
	</div>
</div>
<!-- -->

     <ul class="divide">
       <ul class="dados">
         <li class="nome_dado">Area:</li>
         <li id="valor_dados"><?php echo($item_area) ?></li>
       </ul>
       
       <ul class="dados"> 
         <li class="nome_dado">Dormitórios:</li>  
         <li id="valor_dados"><?php echo($item_dorm) ?></li>
       </ul>
        
       <?php if ($item_suite > 0) : ?>
           <ul class="dados">   
             <li class="nome_dado">Suite:</li> 
             <li id="valor_dados"><?php echo($item_suite) ?></li>
           </ul>
      <?php endif ?> 

       

      </ul><!-- Divide -->
      
      
     <ul class="divide">  
      
       <ul class="dados">
         <li class="nome_dado">Banheiros:</li> 
         <li id="valor_dados"><?php echo($item_wc) ?></li>
       </ul>

       <ul class="dados">  
         <li class="nome_dado">Vagas (garagem)</li> 
         <li id="valor_dados"><?php echo($item_vaga) ?></li>
       </ul> 
        
        
       </ul><!-- Divide -->

      <ul class="divide"> 
      
          
        <ul class="dados"> 
         <li class="nome_dado">Cidade:</li>
         <li id="txt_chat"><?php echo($item_categoriaDesc) ?></li>
        </ul>
         
        <ul class="dados">  
         <li class="nome_dado">Bairro:</li>
         <li id="txt_chat"><?php echo($item_subDesc) ?> </li>
        </ul>
         
         
      </ul><!-- Divide -->
        
              </div><!-- Sled Casa -->
      
             </div>
            
           </div><!-- Sobre -->
         
         
        <div class="center1"> 
       
        <div id="chat" class="chat_ajust1">
          <img  class="chat" src="img/fale_conosco.jpg" />
          <p class="title_chat">Anuncie seu Imovel</p>

          <?php 
              $strSQL = mysql_query("SELECT cliente_informativo FROM cliente"); 
              while($linha = mysql_fetch_array($strSQL)){
                $textoAnuncie = $linha['cliente_informativo'];
          ?> 

          <p class="txt_chat"> <?php echo($textoAnuncie) ?> </p>
           <a href="index.php?pagina=page/page&amp;paginas=5"><div class="chat_acesso" />Anunciar</div>
         </div><!-- Chat -->
         
          <?php 
            }
          ?>
         
           </div><!-- Center -->
          </div><!-- Content2 -->

 </div><!-- Content2 -->
          
   
<?php
}
?>