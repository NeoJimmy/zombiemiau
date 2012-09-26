<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- -->
<html>
<head>
  <!--cabecera estandar-->
  <?php include ("../../include/head.php")?>
</head>
<body>

<div id="container">

<!-- div menu -->
<?php include ("../../include/menu.php"); ?>

<?php
//Si existe una sesion muestro el contenido
/*if ( isset($_SESSION['usuario']) && (($_SESSION['usuario']['perfil'] == 'admin_tablas')|| ($_SESSION['usuario']['perfil'] == 'admin') ) ):*/ ?>

<div id="content">

<?php
include('../../include/conect.php');

$db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
mysql_select_db($config['db_database']);
mysql_query("SET NAMES 'utf8'");

?>

<h2>Ingresar OTT a una orden de trabajo</h2>

<form id='form' action='' method='POST'>
    <p><b>N&uacute;mero de OTT:</b><br>
    <input name="nro_ott" type="text" size="37" class="required"></p>
    <p><input type='submit' value='Guardar' class="btn btn-primary"><input type='hidden' value='1' name='submitted'>
<?php

if (isset($_POST['submitted'])) {
	foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }
	
	//Cambio de estado.
	if(isset($_POST['nro_ott']) && isset($_POST['nro_ot']) )
	{
		$nro_ott = $_POST['nro_ott'];		
		$nro_ot = $_POST['nro_ot'];
		
		//Vemos si la OT ya tiene asignado una OTT
		$rs = mysql_query("SELECT `idott_generadas` FROM `ott_generadas` WHERE `orden_de_trabajo_id_orden_de_trabajo`=".$nro_ot) or die(mysql_error());
		$row = mysql_fetch_row($rs);
		
		//Si la OT posee OTT
		if(isset($row[0])){
			$sql = "UPDATE `ott_generadas` SET `nro_ott` = '".$nro_ott."' WHERE `idott_generadas` = ".$row[0];
			mysql_query($sql) or die(mysql_error());
			echo (mysql_affected_rows()) ? "<br>Fila Editada.<br>" : "<br>Sin cambios. <br>";
		//de lo contrario	
		}else{
			$sql = "INSERT INTO `ott_generadas` 
			(`idott_generadas`, `orden_de_trabajo_id_orden_de_trabajo`, `nro_ott`) 
			VALUES (NULL, $nro_ot, '$nro_ott');";
		
    		$result = mysql_query($sql) or die(mysql_error());
    		if($result)
    			echo "<br>Se ha sido ingresado el n&uacute;mero de OTT correctamente.<br>";
		}
		
    	

	}

}
	
    //Parametro obtenido del combobox
     $sql= "SELECT `idorden_de_trabajo`, `nombre`, `apellido`, `anexo`, `ciudad`, `faena`, `area`, `tipo_ot`, `subtipo_ot`, `descripcion`, `observaciones`, `evaluacion_tecnica`, `nro_ott`
       FROM `orden_de_trabajo`, `historial_ot`, `ott_generadas`
       WHERE  `estado` = 'EJECUCIÓN' AND `idorden_de_trabajo` = `historial_ot`.`orden_de_trabajo_idorden_de_trabajo`  AND `termino` IS NULL AND `idorden_de_trabajo` = `ott_generadas`.`orden_de_trabajo_id_orden_de_trabajo`" ;    

   //echo $sql."<br>";

   $result = mysql_query($sql) or die(mysql_error());
   $rows = mysql_num_rows($result);
?>
    <div style="overflow-x: auto; overflow-y: hidden;">
<?php
     if ( $rows == 0) : ?>
    	<p>No hay ordenes de trabajo por revisar.</p>
<?php else : ?>
	<h3>Seleccione una orden de trabajo a modificar</h3>

    <table id="tabla_ot" class="table table-striped table-bordered">
      <thead>
      <tr>
      	<th scope="col">Sel</th>
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
		<th scope="col">Nro OTT</th>
      </tr>
      </thead>
      <tbody>
   <?php
     for ($i = 0; $i < $rows; $i++)
     $ot[] = mysql_fetch_assoc($result);
     
   ?>
	<?php for ($i = 0; $i < $rows; $i++): 
    	 	$rs = mysql_query("SELECT * FROM historial_ot WHERE orden_de_trabajo_idorden_de_trabajo =".$ot[$i]['idorden_de_trabajo']." AND estado='EVALUACIÓN COMERCIAL' ") or die(mysql_error());
	 		$row = mysql_fetch_row($rs);
	 		if(isset($row[0])) :
	 	?>
	<tr>	
			<td><input type=radio name=nro_ot value=<?php echo $ot[$i]['idorden_de_trabajo'];?>></td>
			<td><?php echo $ot[$i]['idorden_de_trabajo']; ?></td>
	        <td><?php echo $ot[$i]['nombre']; ?></td>
   	        <td><?php echo $ot[$i]['apellido']; ?></td>
	        <td><?php echo $ot[$i]['anexo']; ?></td>
	        <td><?php echo $ot[$i]['ciudad']; ?></td>
	        <td><?php echo $ot[$i]['faena'] ?></td>	                       
   	        <td><?php echo $ot[$i]['area']; ?></td>   	        
	        <td><?php echo $ot[$i]['tipo_ot'] ?></td>
	        <td><?php echo $ot[$i]['subtipo_ot']; ?></td>
	        <td><?php echo $ot[$i]['descripcion']; ?></td>
	        <td><?php echo $ot[$i]['observaciones']; ?></td>
	        <td><?php if(isset($ot[$i]['evaluacion_tecnica'])) echo "<a href='../../public_html/upload/archivos/".$ot[$i]['evaluacion_tecnica']."' >descargar</a>"; ?></td>
	 		<td><?php echo $ot[$i]['nro_ott']; ?></td>
	 </tr>
    <?php
    		endif;
         endfor;
    ?>
      </tbody>
    </table>
<br>
<br>
   
<?php endif; ?>
    </div><!-- overflow -->

<?php
//submitted
?>
</form>
</div>

<?php
//se acabo la sesion
/*else :
?>
<br><center><h3>Tu sesi&oacute;n a expirado...</h3><br><br>
<p><a href='../../index.php'>volver a Inicio</a></p></center>
<?php
endif;
 *
 */
?>

<!-- div footer-->
<?php include ("../../include/footer.php") ?>

</div>

</body>
</html>
