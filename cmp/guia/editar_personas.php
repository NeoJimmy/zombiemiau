<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--EDITAR CLIENTE-->
<html>
<head>
  <!--cabecera estandar-->
  <?php include ("../include/head.php")?>
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
if ( isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil'] == 'admin' ) ): ?>

<div id="content">

<?
include('../include/conect.php');

$db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
mysql_select_db($config['db_database']);
mysql_query("SET NAMES 'utf8'");

//Obtenemos el id del elemento a editar
if (isset($_GET['id']) ) {
$idguia_personas = (int) $_GET['id'];

//echo $idclientes."<br />";

//Se edita el elemento
if (isset($_POST['submitted'])) {
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }

//Editamos el elemento
$sql = "UPDATE `guia_personas` SET
`anexo` =  '{$_POST['anexo']}', 
`nombre` =  '{$_POST['nombre']}', 
`apellido` =  '{$_POST['apellido']}', 
`unidad` =  '{$_POST['unidad']}', 
`localidad` =  '{$_POST['localidad']}', 
`centro_de_costo` =  '{$_POST['centro_de_costo']}' 
WHERE `idguia_personas` = '$idguia_personas' ";

//echo $sql."<br />";

mysql_query($sql) or die(mysql_error());
echo (mysql_affected_rows()) ? "Fila Editada.<br />" : "Sin cambios. <br />";
echo "<a href='admin_personas.php'>Volver a Agenda</a><br /><br />";
}

//Llenamos el formulario
$row = mysql_fetch_array ( mysql_query("SELECT * FROM `guia_personas` WHERE `idguia_personas` = '$idguia_personas' "));
?>

<h2>Editar personas</h2>
<!--/////////////////////////////////////////////////////////////////////////////////////-->
<form id='formEditar' action='' method='POST'>
	<fieldset>
		<legend>Datos de la gu&iacute;a de personas</legend>
	<p><b>anexo:</b><br><input type='text' name='anexo' value='<?= stripslashes($row['anexo']) ?>' class='required'></input>
	<p><b>nombre:</b><br><input type='text' name='nombre' value='<?= stripslashes($row['nombre']) ?>' class='required'></input>
    <p><b>apellido:</b><br><input type='text' name='apellido' value='<?= stripslashes($row['apellido']) ?>' class='required '></input>
    <p><b>unidad:</b><br><input type='text' name='unidad' value='<?= stripslashes($row['unidad']) ?>' class='required'></input>
    <p><b>localidad:</b><br><input type='text' name='localidad' value='<?= stripslashes($row['localidad']) ?>' class='required'></input>
    <p><b>centro de costo:</b><br><input type='text' name='centro_de_costo' value='<?= stripslashes($row['centro_de_costo']) ?>'  class='required'></input>
	
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
