<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--EDITAR USUARIOS-->
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
$idUsuario = (int) $_GET['id'];


//Se edita el elemento
if (isset($_POST['submitted'])) {
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }

$contrasena=hash('sha256',$_POST['nombre_us'].$_POST['contrasena']);

//Editamos el usuario
$sql = "UPDATE `usuario` SET
`perfil_idperfil` = '{$_POST['perfil']}' ,
`rut` =  '{$_POST['rut']}' ,
`nombre` =  '{$_POST['nombre_us']}' ,
`contrasena` =  '$contrasena' ,
`email` =  '{$_POST['email']}'
WHERE `idUsuario` = '$idUsuario' ";

mysql_query($sql) or die(mysql_error());
echo (mysql_affected_rows()) ? "Fila Editada.<br />" : "Sin cambios. <br />";
echo "<a href='admin_usuarios.php'>Volver a usuarios</a>";
}

//Llenamos el formulario FALTA
$row = mysql_fetch_array ( mysql_query("SELECT * FROM `usuario` WHERE `idUsuario` = '$idUsuario' "));
?>

<!--/////////////////////////////////////////////////////////////////////////////////////-->
<h2>Editar usuario</h2>

<form id='formEditar' action='' method='POST'>
<p><label>Rut:</label><input type='text' name='rut' value='<?= stripslashes($row['rut']) ?>' class="required rut">
<p><label>Nombre:</label><input type='text' name='nombre_us' value='<?= stripslashes($row['nombre']) ?>' class="required">
<p><label>Contrase&ntilde;a:</label><input type='text' name='contrasena' value='' class="required">
<p><label>E-mail:</label><input type='text' name='email' value='<?= stripslashes($row['email']) ?>' class="required email">
<p><label>Perfil:</label>
<select name="perfil">
    <?php
        $query="SELECT idperfil, nombre FROM perfil ORDER BY nombre";
        $result = mysql_query($query) or die(mysql_error());
        if ($result)
        while($renglon = mysql_fetch_array($result))
        {
            $valor=$renglon['idperfil'];
            $nombre=$renglon['nombre'];
                echo "<option value=".$valor.">".$nombre."</option>\n";
        }
    ?>
</select>

<p class="espacio-submit"><input type='submit' value='Editar' class="btn btn-primary"><input type='hidden' value='1' name='submitted'>
<!--/////////////////////////////////////////////////////////////////////////////////////-->
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
