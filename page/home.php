     
     <?php include "page/chamada.php" ;?>
	 
           <div id="parte2">
              <div class="box1">

                <?php 
                    $strSQLConfig = mysql_query("SELECT config_site_text, config_site_description FROM config"); 
                    while($linha = mysql_fetch_array($strSQLConfig)){
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
       
        <div id="chat" class="chat_ajust1">
          <img  class="chat" src="img/fale_conosco.jpg" />
          <p class="title_chat">Anuncie seu Imovel</p>

          <?php 
              $strSQLCliente = mysql_query("SELECT cliente_informativo FROM cliente"); 
              while($linha = mysql_fetch_array($strSQLCliente)){
                $textoAnuncie = $linha['cliente_informativo'];
          ?> 

          <p class="txt_chat"> <?php echo($textoAnuncie) ?> </p>
          <a href="index.php?pagina=page/page&amp;paginas=5" class="chat_acesso">Anunciar</a>

        </div><!-- Chat -->
         
          <?php 
            }
          ?>

       
        <div id="novidades">
           <h3>Novidades</h3>
          <?php 
            $strSQLNovidade = mysql_query("SELECT item_mes, item_dia, item_id, item_nome, item_desc, item_preco, MIN(foto_url) as 'foto_url', item_mostra_valor FROM item INNER JOIN foto ON foto_item = item_id  AND foto_pos = (SELECT MIN( foto_pos ) FROM foto WHERE foto_item = item_id)  Where COALESCE(item_mes, '') <> '' AND COALESCE(item_dia, '') <> '' AND item_show = '1' AND item_destaque = '1' GROUP BY item_mes, item_dia, item_id, item_nome, item_desc, item_preco ORDER BY CASE UCASE(item_mes) WHEN 'JANEIRO' THEN 1 WHEN 'FEVEREIRO' THEN 2 WHEN 'MARÇO' THEN 3 WHEN 'ABRIL' THEN 4 WHEN 'MAIO' THEN 5 WHEN 'JUNHO' THEN 6 WHEN 'JULHO' THEN 7 WHEN 'AGOSTO' THEN 8 WHEN 'SETEMBRO' THEN 9 WHEN 'OUTUBRO' THEN 10 WHEN 'NOVEMBRO' THEN 11 WHEN 'DEZEMBRO' THEN 12 END DESC, item_dia DESC LIMIT 0 , 3"); 
            while($linha = mysql_fetch_array($strSQLNovidade)){
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
              <?php if($mostra_valor == true) { ?> 
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