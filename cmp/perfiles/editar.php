<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--EDITAR PERFILES-->
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
//Si existe una sesion abierta del admin, muestro el contenido
if ( isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil'] == 'admin')) :?>

<div id="content">

<?
include('../include/conect.php');

$db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
mysql_select_db($config['db_database']);
mysql_query("SET NAMES 'utf8'");

//Obtenemos el id del elemento a editar
if (isset($_GET['id']) ) {
$idperfil = (int) $_GET['id'];

//Se edita el elemento
if (isset($_POST['submitted'])) {
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }


$sql = "UPDATE `perfil` SET
`nombre` = '{$_POST['nombre']}' ,
`descripcion` =  '{$_POST['descripcion']}'
WHERE `idperfil` = '$idperfil' ";


$result=mysql_query($sql);
if(!$result){
    echo "Error al hacer UPDATE";
    echo "<a href='admin_perfiles.php' title='Administrar Perfiles'>volver a Perfiles</a><br /><br />";
    exit();
}

echo (mysql_affected_rows()) ? "Fila Editada.<br />" : "Sin cambios. <br />";
echo "<a href='admin_perfiles.php' title='Administrar Perfiles'>volver a Perfiles</a><br />";

}

//Llenamos el formulario
$row = mysql_fetch_array ( mysql_query("SELECT * FROM `perfil` WHERE `idperfil` = '$idperfil' "));
?>

<h2>Editar perfil</h2>
<form id='formEditar' action='' method='POST'>
<br>
<p><b>Nombre:</b><br><input type='text' name='nombre' value='<?= stripslashes($row['nombre']) ?>' class="required">
<p><b>Descripci&oacute;n</b><br><textarea name="descripcion" rows="5" cols="40" class="required">
<?= stripslashes($row['descripcion']) ?>
</textarea>
<p><input type='submit' class="btn btn-primary" value='Editar'><input type='hidden' value='1' name='submitted'>
</form>
<? } ?>

</div>

<?php
//se acabo la sesion
else :
?>
<br><center><h3>Tu sesi&oacute;n a expirado...</h3><br><br>
<p><a href='../../obras/index.php'>volver a Inicio</a></p></center>
<?php 
endif; 
?>

<!-- div footer-->
<?php include ("../include/footer.php") ?>
</div>
</body>
</html>

