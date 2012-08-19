<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--EDITAR CLIENTE-->
<html>
<head>
  <!--cabecera estandar-->
  <?php include ("../include/head.php")?>
  
  <script type="text/javascript">
		function makeSublist(parent,child,isSubselectOptional,childVal)
		{
			$("body").append("<select style='display:none' id='"+parent+child+"'></select>");
			$('#'+parent+child).html($("#"+child+" option"));
			
			var parentValue = $('#'+parent).attr('value');
			$('#'+child).html($('#'+parent+child+" .sub_"+parentValue).clone());
			
			childVal = (typeof childVal == "undefined")? "" : childVal ;
			$("#"+child).val(childVal).attr('selected','selected');
			
			$('#'+parent).change(function(){
				var parentValue = $('#'+parent).attr('value');
				$('#'+child).html($("#"+parent+child+" .sub_"+parentValue).clone());
			
				$('#'+child).trigger("change");
				$('#'+child).focus();
			});
		}
		
		$(document).ready(function()
		{
			makeSublist('parent','child', true, '1');
		});
  </script>
  <script type="text/javascript">
  $(document).ready(function(){
    $("#formEditar").validate();
  });
  </script>

</head>
<body>

<div id="container">

<!-- div menu -->
<?php include ("../include/menu.php"); ?>

<?php
//Si existe una sesion muestro el contenido
if ( isset($_SESSION['usuario']) && ( $_SESSION['usuario']['perfil'] == 'admin' ) ): ?>

<div id="content">

<?
include('../include/conect.php');

$db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
mysql_select_db($config['db_database']);
mysql_query("SET NAMES 'utf8'");

function checkbox_value($name) {
    return (isset($_POST[$name]) ? 1 : 0);
}

//Obtenemos el id del elemento a editar
if (isset($_GET['id']) ) {
$idguia_datostecnicos = (int) $_GET['id'];

//echo $idclientes."<br />";

//Se edita el elemento
if (isset($_POST['submitted'])) {
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }


//Editamos el elemento
$sql = "UPDATE `guia_datostecnicos` SET
`anexo` =  '{$_POST['anexo']}', 
`tipo` =  '{$_POST['tipo']}', 
`subtipo` =  '{$_POST['subtipo']}', 
`categoria` =  '{$_POST['categoria']}', 
`sap` =  '{$_POST['sap']}', 
`correo_de_voz` =  ".checkbox_value('correo_de_voz').", 
`claves` =  '{$_POST['claves']}', 
`modelo` =  '{$_POST['modelo']}', 
`serie` =  '{$_POST['serie']}', 
`mac` =  '{$_POST['mac']}', 
`switch_puerta` =  '{$_POST['switch_puerta']}' 
WHERE `idguia_datostecnicos` = '$idguia_datostecnicos' ";

//echo $sql."<br />";

mysql_query($sql) or die(mysql_error());
echo (mysql_affected_rows()) ? "Fila Editada.<br />" : "Sin cambios. <br />";
echo "<a href='admin_datostecnicos.php'>Volver a Datos t&eacute;cnicos</a><br /><br />";
}

//Llenamos el formulario
$row = mysql_fetch_array ( mysql_query("SELECT * FROM `guia_datostecnicos` WHERE `idguia_datostecnicos` = '$idguia_datostecnicos' "));
?>
<br>
<h2>Editar datos t&eacute;cnicos</h2>
<!--/////////////////////////////////////////////////////////////////////////////////////-->
<form id='formEditar' action='' method='POST'>
<br>
	<p><label><b>anexo:</b></label><input type='text' name='anexo' value='<?= stripslashes($row['anexo']) ?>' class='required'><br>
    <p><label><b>tipo:</b></label>
	<select id="parent" name='tipo'>
    <?php
		$query="SELECT DISTINCT tipo FROM tipo_anexo";
	    $result = mysql_query($query) or die(mysql_error());
	    if ($result)
	    while($renglon = mysql_fetch_array($result))
	    {
	        $opcion = $renglon['tipo'];
	        $valor = str_replace(" ", "_",$renglon['tipo']);
	        if($valor != $row['tipo'])
	        	echo "<option value=".$valor.">".$opcion."</option>\n";
	        else
	        	echo "<option selected value=".$valor.">".$opcion."</option>\n";
	    }
	?>
    </select><br>
    <p><label><b>subtipo:</b></label>
    <select id="child" name='subtipo'>
    <?php
	    $query="SELECT tipo, subtipo FROM tipo_anexo";
	    $result = mysql_query($query) or die(mysql_error());
	    if ($result)
	    while($renglon = mysql_fetch_array($result))
	    {
	        $opcion = $renglon['subtipo'];
	        $valor =  str_replace(" ", "_", $renglon['subtipo']);
	        $clase = str_replace(" ", "_", $renglon['tipo']);
	        if($valor != $row['subtipo'])
	        	echo "<option class='sub_".$clase."' value=".$valor.">".$opcion."</option>\n";
	        else
	        	echo "<option selected class='sub_".$clase."' value=".$valor.">".$opcion."</option>\n";
	    }
	?>
    </select><br>
	<p><label><b>categor&iacute;a:</b></label><input type='text' name='categoria' value='<?= stripslashes($row['categoria']) ?>' class='required'><br>
	<p><label><b>sap:</b></label><input type='text' name='sap' value='<?= stripslashes($row['sap']) ?>' class='required'><br>
	<!--<input type="checkbox" name="status" value="1" checked />-->
	<p><label><b>correo de voz:</b></label><input type="checkbox" name='correo_de_voz' value='1'><br>
	<p><label><b>claves:</b></label><input type='text' name='claves' value='<?= stripslashes($row['claves']) ?>' class='required'><br>
	<p><label><b>modelo:</b></label><input type='text' name='modelo' value='<?= stripslashes($row['modelo']) ?>' class='required'><br>
	<p><label><b>serie:</b></label><input type='text' name='serie' value='<?= stripslashes($row['serie']) ?>' class='required'><br>
	<p><label><b>mac:</b></label><input type='text' name='mac' value='<?= stripslashes($row['mac']) ?>' class='required'><br>
	<p><label><b>switch-puerta:</b></label><input type='text' name='switch_puerta' value='<?= stripslashes($row['switch_puerta']) ?>' class='required'><br>		
    <br>
<p><input type='submit' value='Editar'><input type='hidden' value='1' name='submitted'>
<!--/////////////////////////////////////////////////////////////////////////////////////-->
</form>
<? } ?>

</div>

<?php
//se acabo la sesion
else :
?>
<br><center><h3>Tu sesi&oacute;n a expirado...</h3><br><br>
<p><a href='../index.php'>volver a Inicio</a></p></center>
<?php 
endif;                         
?>

<!-- div footer-->
<?php include ("../include/footer.php") ?>

</div>

</body>
</html>
