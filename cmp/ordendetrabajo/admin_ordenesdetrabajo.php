<?php session_start();ob_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- ADMIN-->
<html>
<head>
  <!--cabecera estandar-->
  <?php include ("../include/head.php")?>
	<script type="text/javascript">
		$(function() {
			$("#dialog-confirm").dialog({
				resizable: false,
				height:140,
				modal: true,
				autoOpen: false
			});
			$("a#opener").click(function(e) {
				event.preventDefault();
				var targetUrl = $(this).attr("href");
				$("#dialog-confirm").dialog('option', 'buttons', {
					"Eliminar": function() {
	 					window.location.href = targetUrl;
					},
					"Cancelar": function() {
						$(this).dialog("close");
					}
				});
				$("#dialog-confirm").dialog("open");
			});
		});
	</script>
	<!--necesario para confirm dialog-->
    <link type="text/css" href="../css/cupertino/jquery-ui-1.8.9.custom.css" rel="stylesheet"></link>
    <script type="text/javascript" src="../js/jquery-ui-1.8.9.custom.min.js"></script>

</head>
<body>

<div id="container">

<!-- div menu -->
<?php include ("../include/menu.php"); ?>

<?php
//Si existe una sesion muestro el contenido
if ( isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil'] == 'admin' ) ): ?>

<div id="content">

<?php
include('../include/conect.php');

$db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
mysql_select_db($config['db_database']);
mysql_query("SET NAMES 'utf8'");

?>

<h2>Administrar ordenes de trabajo</h2>

<?php

// Eliminar elemento
  if ( isset( $_GET['delete'] ) )
  {
	$result = mysql_query("SELECT `idhistorial_ot` FROM `historial_ot` WHERE `orden_de_trabajo_idorden_de_trabajo` = ".$_GET['delete']) or die(mysql_error());
	$rows = mysql_num_rows($result);
	for ($i = 0; $i < $rows; $i++)
		$id[] = mysql_fetch_assoc($result);
  	for ($i = 0; $i < $rows; $i++){
		$query = 'DELETE FROM `historial_ot_usuario` WHERE `historial_ot_idhistorial_ot` = '.$id[$i]['idhistorial_ot'];
	  	mysql_query($query) or die(mysql_error());
	}
   
  	//Borramos historial
  	$query = 'DELETE FROM `historial_ot` WHERE `orden_de_trabajo_idorden_de_trabajo` = '.$_GET['delete'];
  	mysql_query($query) or die(mysql_error());
    //Eliminar el archivo de OT asignado
    $directorio = '../public_html/upload/archivos';
	$rs = mysql_query("SELECT `evaluacion_tecnica` FROM `orden_de_trabajo` WHERE `idorden_de_trabajo`= ".$_GET['delete']) or die(mysql_error());
	$row = mysql_fetch_row($rs);
	$borrar_nombre = $row[0];
	unlink($directorio."/".$borrar_nombre);
	//Borramos orden de trabajo
    $query = 'DELETE FROM orden_de_trabajo WHERE idorden_de_trabajo = '.$_GET['delete'];
    mysql_query($query) or die(mysql_error());
	unset($_GET['delete']);
	header('Location:admin_ordenesdetrabajo.php');
  }
?>
<div id="dialog-confirm" title="Eliminar orden de trabajo:">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>&iquest;Desea eliminar este elemento?</p>
</div>
<?php

$sql= "SELECT *
       FROM `orden_de_trabajo` " ;

$result = mysql_query($sql) or die(mysql_error());
$rows = mysql_num_rows($result);
?>
    <div style="overflow-x: auto; overflow-y: hidden;">
<?php
     if ( $rows == 0) : ?>

    <p>No se encontraron registros con los datos ingresados.</p>

<?php else : ?>

    <table id="tabla_agenda" class="table table-striped table-bordered">
      <thead>
      <tr>
      	<th scope="col">Nro OT</th>
		<th scope="col">Nombre</th>
		<th scope="col">Apellido</th>
		<th scope="col">Anexo</th>
		<th scope="col">Ciudad</th>
		<th scope="col">Faena</th>
		<th scope="col">Area</th>
		<th scope="col">Tipo</th>
		<th scope="col">Subtipo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col">Observaciones</th>
		<th scope="col">Evaluaci&oacute;n t&eacute;cnica</th>
		<th scope="col">Acciones</th>
      </tr>
      </thead>
      <tbody>
   <?php
     for ($i = 0; $i < $rows; $i++)
     $ot[] = mysql_fetch_assoc($result);
   ?>
	    <?php for ($i = 0; $i < $rows; $i++): ?>
	<tr>	
	      	<td><?php echo $ot[$i]['idorden_de_trabajo']; ?></td>
			<td><?php echo $ot[$i]['nombre']; ?></td>
   	        <td><?php echo $ot[$i]['apellido']; ?></td>
			<td><?php echo $ot[$i]['anexo']; ?></td>
			<td><?php echo $ot[$i]['ciudad']; ?></td>
			<td><?php echo $ot[$i]['faena']; ?></td>
			<td><?php echo $ot[$i]['area']; ?></td>
			<td><?php echo $ot[$i]['tipo_ot']; ?></td>
			<td><?php echo $ot[$i]['subtipo_ot']; ?></td>
			<td><?php echo $ot[$i]['descripcion']; ?></td>
			<td><?php echo $ot[$i]['observaciones']; ?></td>
			<td><?php if(isset($ot[$i]['evaluacion_tecnica'])) echo "<a href='../public_html/upload/archivos/".$ot[$i]['evaluacion_tecnica']."' >".$ot[$i]['evaluacion_tecnica']."</a>"; ?></td>
            <td>
            <div id="accion">
            <ul>
                <li id="accion">
                    <a id="opener" href="admin_ordenesdetrabajo.php?delete=<?php echo $ot[$i]['idorden_de_trabajo']; ?>"> Eliminar</a>
                </li>
            </ul>
            </div>
            </td>
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
