
<script type="text/javascript" src="js/rotator.js"></script>
<div class="dwuserEasyRotator" style="width: 960px; height: 500px; position:relative; text-align: left;" data-erConfig="{autoplayEnabled:true, randomize:true, autoplayStopOnInteraction:false, autoplayGalleryLoop:true, lpp:'102-105-108-101-58-47-47-47-67-58-47-85-115-101-114-115-47-104-101-110-114-105-95-48-48-48-47-68-111-99-117-109-101-110-116-115-47-69-97-115-121-82-111-116-97-116-111-114-80-114-101-118-105-101-119-47-112-114-101-118-105-101-119-95-115-119-102-115-47', wv:1, autoplayPauseOnHover:false}" data-erName="chama" data-erTID="{drqknh362p5960420831631}">
	<div data-ertype="content" style="display: none;"><ul data-erlabel="Main Category">
	
	<?php 
		$strSQL = mysql_query("SELECT item.item_id, item.item_nome, item.item_desc, MIN(foto.foto_url) as 'foto_url' FROM item INNER JOIN foto ON foto_item = item_id  AND foto_pos = (SELECT MIN( foto_pos ) FROM foto WHERE foto_item = item_id) WHERE item.item_show = '1' AND item.item_slide = '1' GROUP BY item.item_id, item.item_nome, item.item_desc ORDER BY item_id"); 
		while($linha = mysql_fetch_array($strSQL)){
			
            $chamada_id = $linha['item_id'];
            $chamada_Nome = $linha['item_nome'];
            $chamada_descricao = $linha['item_desc'];
            $chamada_foto = $linha['foto_url'];
	?>	
	
	<li>
		<a class="mainLink" href="index.php?pagina=page/page&amp;paginas=6&id=<?php Echo($chamada_id) ?>"><img class="main" src="ocorretor/app/fotos/<?php echo($chamada_foto) ?>" alt="Casa 5 comomodos com espaço de sobre" /></a>
		<img class="thumb" src="ocorretor/app/fotos/<?php echo($chamada_foto) ?>" />
		<span class="title"><?php echo($chamada_Nome) ?></span>
		<span class="desc"><?php echo(substr($chamada_descricao, 0, 130)) ?> </span>
	</li>
	
    <?php 
        }
    ?>
	
</ul>
</div> 
	<div data-ertype="layout" data-ertemplateName="NONE" style="">			<!-- bg -->
			<div style="
            position:absolute;
            left: 0;
            right: 0;
            top:0;
            bottom:0;
            "style_real="box-shadow: 0 1px 3px #666;" class="erFixCSS3">
			</div>
			
			<div class="erimgMain" style="
            position: absolute;
            left:0;
            right:0;
            top:0;
            bottom:85px;"
            data-erConfig="{___numTiles:3, scaleMode:'fillArea', imgType:'main', __loopNextButton:false, arrowButtonMode:'rollover'}">
				<div class="erimgMain_slides" style="
                position: absolute;
                left:0;
                top:0;
                bottom:0;
                right:0;">
					<div class="erimgMain_slide">
						<div class="erimgMain_img" style="
                        position: absolute;
                        left: 0;
                        right: 0;
                        top: 0;
                        bottom: 0;"></div>
						
                        <div class="erimgMain_customField" data-erfield="videoOverlay" style="
                        position: absolute;
                        left: 0;
                        right: 0;


                        top: 0;
                        bottom: 0;
                        display: none;"></div>
					</div>
				</div>
				<div class="erimgMain_arrowLeft" style="
                position:absolute; 
                left:10px;
                top: 50%;
                margin-top: -15px;" 
                data-erConfig="{image:'circleSmall', image2:'circleSmall'}"></div>
				<div class="erimgMain_arrowRight" style="
                position:absolute;
                right: 10px;
                top: 50%;               
                margin-top: -15px;"></div>
			</div>
            
			<div class="erimgMain rotatorTileNav" style="
            position: absolute;
            left:0;
            right:0;
            height:110px;
            bottom:0;"
             data-erConfig="{numTiles:-1, scaleMode:'fillArea', imgType:'thumb', loopNextButton:false, arrowButtonMode:'rollover', __slideLinkEvent:'rollover'}">
				<div style="
                position: absolute;
                height:100px;
                left: 0;
                top: 10px;
                right: 0;
                bottom: 0;
                background: #111;"></div>
                
				<div class="erimgMain_slides" style="
                position: absolute;
                left:0;
                top:0;
                bottom:0;
                right:0;">
					<div class="erimgMain_slide">
						
                        
                        <!-- <div class="erimgMain_img" style="position: absolute; left: 0; right: 0; top: 10px; bottom: 0; margin: 2px 1px;"></div> -->
						<div class="" 
							style="
                            position: absolute;
                            left: 0;
                            right:0;
                            top: 10px;
                            bottom: 0;
                            padding:5px;
                            color: #FFF;
                            font-family: 'Helvetica Neue',Helvetica,Arial,_sans; 
                            font-size: 12px;"
							style_erinjection=".erimgMain_slide_selected THIS { background:#FFF; } THIS:hover .erimgMain_title { color:#0099FF; }">
							<p class="erimgMain_title" 
								style="
                                padding: 0 10px;
                                font-weight: bold;
                                margin: 0 0 3px 0;
                                font-size: 13px;
                                color: #FFF;"
								style_erinjection=".erimgMain_slide_selected THIS { color:#0099FF; }"></p>
							<p class="erimgMain_desc" 
								style="padding: 0 10px; font-weight: normal; margin: 0; font-size: 11px; line-height: 1.4em; color: #CCC;"
								style_erinjection=".erimgMain_slide_selected THIS { color:#333; }"></p>
						</div>
                        
						<div class="selectionArrow visibleWhenSelected" style="
                        position: absolute;
                        top: 0;
                        left: 50%;
                        margin-left: -10px;
                        width: 20px;
                        height:10px;
                        background-image: url('http://easyrotator.s3.amazonaws.com/1/i/rotator/FFF_arrow10_export.png');"></div>
					</div>
				</div>
                
				<div class="erimgMain_arrowLeft" style="
                position:absolute;
                left: 60px;
                top: 50%;
                margin-top: -10px;
                " data-erConfig="{image:'circleSmall', image2:'circleSmall'}"></div>
                
				<div class="erimgMain_arrowRight" style="
                position:absolute;
                right: 60px;
                top: 50%;
                margin-top: -10px;"></div>
                
			</div><div class="erabout erFixCSS3" style="
            color: #FFF;
            text-align: left;
            background: #000;
             background:rgba(0,0,0,0.93); 
             border: 2px solid #FFF;
             padding: 20px;
             font: normal 11px/14px Verdana,_sans;

             width: 300px;
             border-radius: 10px;
             display:none;">
		<a style="color:#FFF;" href="http://www.dwuser.com/easyrotator/" target="_blank"><a style="color:#FFF;" href="http://www.dwuser.com/easyrotator/" target="_blank"></a>
			<br /><br />
           <a style="color:#FFF;" href="http://www.dwuser.com/easyrotator/wordpress/" target="_blank"></a> <a style="color:#FFF;" href="http://www.dwuser.com/easyrotator/wordpress/" target="_blank">
			<br /><br />
			<a style="color:#FFF;" href="#" class="erabout_ok"></a>   
		</div>
		<noscript>
			<a href="http://www.dwuser.com/easyrotator/"></a>
		</noscript>
		<script type="text/javascript">/*Avoid IE gzip bug*/(function(b,c,d){try{if(!b[d]){b[d]="temp";var a=c.createElement("script");a.type="text/javascript";a.src="http://easyrotator.s3.amazonaws.com/1/js/nozip/easy_rotator.min.js";c.getElementsByTagName("head")[0].appendChild(a)}}catch(e){alert("EasyRotator fail; contact support.")}})(window,document,"er_$144");</script>
	
	</div>
</div>