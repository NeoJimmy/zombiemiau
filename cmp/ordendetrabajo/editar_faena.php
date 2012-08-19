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
$idfaenas = (int) $_GET['id'];

//echo $idclientes."<br />";

//Se edita el elemento
if (isset($_POST['submitted'])) {
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }

//Editamos el elemento
$sql = "UPDATE `faenas` SET
`ciudad` =  '{$_POST['ciudad']}', 
`faena` =  '{$_POST['faena']}' 
WHERE `idfaenas` = '$idfaenas' ";

//echo $sql."<br />";

mysql_query($sql) or die(mysql_error());
echo (mysql_affected_rows()) ? "Fila Editada.<br />" : "Sin cambios. <br />";
echo "<a href='admin_faenas.php'>Volver a tipos de orden</a><br /><br />";
}

//Llenamos el formulario
$row = mysql_fetch_array ( mysql_query("SELECT * FROM `faenas` WHERE `idfaenas` = '$idfaenas' "));
?>
<br>
<h2>Editar faena</h2>
<br>
<!--/////////////////////////////////////////////////////////////////////////////////////-->
<form id='formEditar' action='' method='POST'>
	<p><label><b>ciudad:</b></label><input type='text' name='ciudad' value='<?= stripslashes($row['ciudad']) ?>' class='required'><br>
	<p><label><b>faena:</b></label><input type='text' name='faena' value='<?= stripslashes($row['faena']) ?>' class='required'><br>
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
