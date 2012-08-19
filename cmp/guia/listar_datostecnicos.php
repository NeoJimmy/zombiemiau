<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- -->
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
<br>
<h2>Datos t&eacute;cnicos de anexos</h2>
<br>
<?php

$sql= "SELECT *
       FROM `guia_datostecnicos` " ;
    

$result = mysql_query($sql) or die(mysql_error());
$rows = mysql_num_rows($result);
?>
    <div style="overflow-x: auto; overflow-y: hidden;">
<?php
     if ( $rows == 0) : ?>

    <p>No se encontraron registros con los datos ingresados.</p>

<?php else : ?>

    <br>
    <table id="tabla_datostecnicos" class="ui-widget ui-widget-content">
      <thead class="ui-widget-header">
      <tr>
   		<th scope="col">anexo</th>	
		<th scope="col">tipo</th>
		<th scope="col">subtipo</th>
		<th scope="col">categor&iacute;a</th>
		<th scope="col">sap</th>
		<th scope="col">correo de voz</th>
		<th scope="col">claves</th>
		<th scope="col">modelo</th>
		<th scope="col">serie</th>
		<th scope="col">mac</th>
		<th scope="col">switch:puerta</th>
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
	        <td><?php echo $agenda[$i]['tipo']; ?></td>
   	        <td><?php echo $agenda[$i]['subtipo']; ?></td>
   	        <td><?php echo $agenda[$i]['categoria']; ?></td>
   	        <td><?php echo $agenda[$i]['sap']; ?></td>
   	        <td><?php echo $agenda[$i]['correo_de_voz']; ?></td>
	        <td><?php echo $agenda[$i]['claves'] ?></td>
	        <td><?php echo $agenda[$i]['modelo'] ?></td>
   	        <td><?php echo $agenda[$i]['serie'] ?></td>
	        <td><?php echo $agenda[$i]['mac']; ?></td>
	        <td><?php echo $agenda[$i]['switch_puerta']; ?></td>
	</tr>
    <?php
             endfor;
    ?>
      </tbody>
    </table>

<?php endif; ?>
    </div>
<?php if($rows) :?>
<br><br>
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
