<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- ADMIN GUIA TELEFONICA-->
<html>
<head>
  <!--cabecera estandar-->
  <?php include ("../include/head.php")?>

</head>
<body>

<div id="container">

<!-- div menu -->
<?php include ("../include/menu.php"); ?>

<?php
//Si existe una sesion muestro el contenido
//if ( isset($_SESSION['usuario']) && (($_SESSION['usuario']['perfil'] == 'admin_tablas')|| ($_SESSION['usuario']['perfil'] == 'admin') ) ): ?>

<div id="content">

<?php
include('../include/conect.php');

$db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
mysql_select_db($config['db_database']);
mysql_query("SET NAMES 'utf8'");

?>

<h2>Personas</h2>

<?php

$sql= "SELECT `anexo`, `nombre`, `apellido`, `unidad`, `localidad`, `centro_de_costo`
       FROM `guia_personas` " ;
    

$result = mysql_query($sql) or die(mysql_error());
$rows = mysql_num_rows($result);
?>
    <div style="overflow-x: auto; overflow-y: hidden;">
<?php
     if ( $rows == 0) : ?>

    <p>No se encontraron registros con los datos ingresados.</p>

<?php else : ?>

    <table id="tabla_agenda" class="ui-widget ui-widget-content table table-striped table-bordered">
      <thead class="ui-widget-header">
      <tr>
      	<th scope="col">Anexo</th>
		<th scope="col">Nombre</th>
		<th scope="col">Apellido</th>	
		<th scope="col">Unidad</th>
		<th scope="col">Localidad</th>
		<th scope="col">Centro de costo</th>
      </tr>
      </thead>
      <tbody>
   <?php
     for ($i = 0; $i < $rows; $i++)
     $agenda[] = mysql_fetch_assoc($result);
   ?>
	    <?php for ($i = 0; $i < $rows; $i++): ?>
	<tr>	
		    <td><?php echo $agenda[$i]['anexo']; ?></td>
	        <td><?php echo $agenda[$i]['nombre']; ?></td>
   	        <td><?php echo $agenda[$i]['apellido']; ?></td>
   	        <td><?php echo $agenda[$i]['unidad']; ?></td>
   	        <td><?php echo $agenda[$i]['localidad'] ?></td>        
	        <td><?php echo $agenda[$i]['centro_de_costo'] ?></td>        
    <?php
             endfor;
    ?>
      </tr>
      </tbody>
    </table>

<?php endif; ?>
    </div>
<?php if($rows) :?>

<?php endif;?>

</div>

<?php
//se acabo la sesion
//else :
?>
<!--<br><center><h3>Tu sesi&oacute;n a expirado...</h3><br><br>
<p><a href='../index.php'>volver a Inicio</a></p></center>-->
<?php 
//endif; 
?>

<!-- div footer-->
<?php include ("../include/footer.php") ?>

</div>

</body>
</html>
