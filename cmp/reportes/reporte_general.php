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
                                $('input#id_busqueda').quicksearch('table#tabla_ot tbody tr');
                        });
  </script>
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

<p>Ingrese algún patrón a buscar:</p>
<form action="#">
    <fieldset>
        <input type="text" name="search" value="" id="id_busqueda" placeholder="Buscar" autofocus></input>
    </fieldset>
</form>

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

    <table id="tabla_ot" class="table table-striped table-bordered">
      <thead>
      <tr>
      	<th scope="col">Nro de OT</th>
      	<th scope="col">Estado</th>
        <th scope="col">Usuario</th>
        <th scope="col">Anexo</th>
      	<th scope="col">Creaci&oacute;n de OT</th>
      	<th scope="col">Inicio del estado actual</th>
      	<th scope="col">Ciudad</th>
    		<th scope="col">Faena</th>
    		<th scope="col">Tipo</th>
    		<th scope="col">Subtipo</th>
    		<th scope="col">Descripci&oacute;n</th>
    		<th scope="col">Observaci&oacute;n del estado actual</th>
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
    <td><?php echo $ot[$i]['nombre']." ".$ot[$i]['apellido']; ?></td>
    <td><?php echo $ot[$i]['anexo']; ?></td>
		<td><?php $date = new DateTime($row[0]); echo $date->format('d/m/Y'); ?></td>
		<td><?php $date = new DateTime($ot[$i]['inicio']); echo $date->format('d/m/Y'); ?></td>		
        <td><?php echo $ot[$i]['ciudad']; ?></td>
        <td><?php echo $ot[$i]['faena']; ?></td>
        <td><?php echo $ot[$i]['tipo_ot']; ?></td>
        <td><?php echo $ot[$i]['subtipo_ot']; ?></td>
        <td><?php echo $ot[$i]['descripcion']; ?></td>
        <td><?php echo $ot[$i]['observacion']; ?></td>
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
