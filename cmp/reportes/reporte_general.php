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
/*if ( isset($_SESSION['usuario']) && (($_SESSION['usuario']['perfil'] == 'admin_tablas')|| ($_SESSION['usuario']['perfil'] == 'admin') ) ):*/ ?>

<div id="content">

<?php
include('../include/conect.php');

$db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
mysql_select_db($config['db_database']);
mysql_query("SET NAMES 'utf8'");

?>

<h2>Reporte general</h2>

<?php

if (isset($_POST['submitted'])) {
	foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }
}	

        $sql= "SELECT `idorden_de_trabajo`, `nombre`, `apellido`, `anexo`, `ciudad`, `faena`, `area`, `tipo_ot`, `subtipo_ot`, `descripcion`, `estado`, `inicio`, `observacion`    
               FROM `orden_de_trabajo`, `historial_ot`
           	   WHERE  `idorden_de_trabajo` = `orden_de_trabajo_idorden_de_trabajo` AND `termino` IS NULL ";
	
	   $result = mysql_query($sql) or die(mysql_error());
	   $rows = mysql_num_rows($result);
   
?>
    <div style="overflow-x: auto; overflow-y: hidden;">
<?php
     if ( $rows == 0) : ?>
    	<p>No se encontraron registros con los datos ingresados.</p>
<?php else : ?>
	<h3>Orden de trabajo</h3>

    <table id="tabla_ot" class="ui-widget ui-widget-content table table-striped table-bordered">
      <thead class="ui-widget-header">
      <tr>
      	<th scope="col">nro de OT</th>
      	<th scope="col">estado</th>
      	<th scope="col">creaci&oacute;n de OT</th>
      	<th scope="col">inicio del estado actual</th>
      	<th scope="col">ciudad</th>
		<th scope="col">lugar</th>
		<th scope="col">tipo</th>
		<th scope="col">subtipo</th>
		<th scope="col">descripci&oacute;n</th>
		<th scope="col">observaci&oacute;n de cierre</th>
      </tr>
      </thead>
      <tbody>
   <?php
     for ($i = 0; $i < $rows; $i++)
     $ot[] = mysql_fetch_assoc($result);
   ?>
	    <?php for ($i = 0; $i < $rows; $i++): 
	    
	    	$sql = "SELECT inicio 
	    			FROM historial_ot 
	    			WHERE orden_de_trabajo_idorden_de_trabajo =".$ot[$i]['idorden_de_trabajo']." AND estado='CREADA' ";
	    	$rs = mysql_query($sql) or die(mysql_error());
	    	$row = mysql_fetch_row($rs);
	    ?>
	<tr>
		<td><?php echo $ot[$i]['idorden_de_trabajo'];?></td>
		<td><?php echo $ot[$i]['estado']; ?></td>
		<td><?php $date = new DateTime($row[0]); echo $date->format('d/m/Y'); ?></td>
		<td><?php $date = new DateTime($ot[$i]['inicio']); echo $date->format('d/m/Y'); ?></td>		
        <td><?php echo $ot[$i]['ciudad']; ?></td>
        <td><?php echo $ot[$i]['faena']; ?></td>
        <td><?php echo $ot[$i]['tipo_ot']; ?></td>
        <td><?php echo $ot[$i]['subtipo_ot']; ?></td>
        <td><?php echo $ot[$i]['descripcion']; ?></td>
        <td><?php if($ot[$i]['estado'] == 'CERRADA') echo $ot[$i]['observacion']; ?></td>
	 </tr>
    <?php
             endfor;
    ?>
      </tbody>
    </table>
   
<?php endif; ?>
    </div><!-- overflow -->
    <input type="button" class="btn" value="Descargar Reporte" onclick="location.href='descargar_reporte.php'">

</div>

<?php
//se acabo la sesion
/*else :
?>
<br><center><h3>Tu sesi&oacute;n a expirado...</h3><br><br>
<p><a href='../index.php'>volver a Inicio</a></p></center>
<?php
endif;
 *
 */
?>

<!-- div footer-->
<?php include ("../include/footer.php") ?>

</div>

</body>
</html>
