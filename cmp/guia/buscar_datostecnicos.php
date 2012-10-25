<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- -->
<html>
<head>
  <!--cabecera estandar-->
  <?php include ("../include/head.php")?>
  <script type="text/javascript" src="../js/jquery.quicksearch.js"></script>
        <script type="text/javascript">
			$(function () {
                                $('input#id_busqueda').quicksearch('table#tabla_datostecnicos tbody tr');
                        });
  </script>


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

<h2>Busqueda datos t&eacute;cnicos de anexos</h2>

<p>Ingrese algún patrón a buscar:</p>
<form action="#">
    <fieldset>
        <input type="text" name="search" value="" id="id_busqueda" placeholder="Buscar" autofocus></input>
    </fieldset>
</form>

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

    <table id="tabla_datostecnicos" class="ui-widget ui-widget-content table table-striped table-bordered">
      <thead class="ui-widget-header">
      <tr>
   		<th scope="col">Anexo</th>	
		<th scope="col">Tipo</th>
		<th scope="col">Subtipo</th>
		<th scope="col">Sap</th>
		<th scope="col">Correo de voz</th>
		<th scope="col">Claves</th>
		<th scope="col">Modelo</th>
		<th scope="col">Serie</th>
		<th scope="col">Mac</th>
		<th scope="col">Switch:puerta</th>
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
