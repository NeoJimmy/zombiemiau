<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--EDITAR-->
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
$idtipo_anexo = (int) $_GET['id'];

//echo $idclientes."<br />";

//Se edita el elemento
if (isset($_POST['submitted'])) {
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }

//Editamos el elemento
$sql = "UPDATE `tipo_anexo` SET
`tipo` =  '{$_POST['tipo']}', 
`subtipo` =  '{$_POST['subtipo']}' 
WHERE `idtipo_anexo` = '$idtipo_anexo' ";

//echo $sql."<br />";

mysql_query($sql) or die(mysql_error());
echo (mysql_affected_rows()) ? "Fila Editada.<br />" : "Sin cambios. <br />";
echo "<a href='admin_tiposdeanexo.php'>Volver a Agenda</a><br /><br />";
}

//Llenamos el formulario
$row = mysql_fetch_array ( mysql_query("SELECT * FROM `tipo_anexo` WHERE `idtipo_anexo` = '$idtipo_anexo' "));
?>

<h2>Editar anexo</h2>
<!--/////////////////////////////////////////////////////////////////////////////////////-->
<form id='formEditar' action='' method='POST'>
	<p><label>Tipo:</label><input type='text' name='tipo' value='<?= stripslashes($row['tipo']) ?>' class='required'>
	<p><label>Subtipo:</label><input type='text' name='subtipo' value='<?= stripslashes($row['subtipo']) ?>' class='required'>
<p class="espacio-submit"><input class="btn btn-primary" type='submit' value='Editar'><input type='hidden' value='1' name='submitted'>
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
