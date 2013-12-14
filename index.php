<?php include_once "header.php";?>

<?php
foreach ($_REQUEST as $___opt => $___val) {
  $$___opt = $___val;
}
if(empty($pagina)) {
include("page/home.php");
}
elseif(substr($pagina, 0, 4)=='http' or substr($pagina, 
0, 1)=="/" or substr($pagina, 0, 1)==".") 
{
echo '<br><font face=arial size=11px><br><b>A Página Não Existe.</b><br>Por favor 

selecione uma página a partir do Menu Principal.</font>'; 
}
else {include("$pagina.php");}?>

<?php include_once "footer.php";?>
