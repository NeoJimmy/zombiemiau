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

<h2>Revisar &oacute;rdenes de trabajo con evaluaci&oacute;n comercial</h2>

<form id='form' action='' method='POST'>
    <p><label>Acci&oacute;n:</label>
    <select name="accion">
        <option value=ninguno>Seleccione una acci&oacute;n...</option>
        <option value=aprobar>Aprobar</option>
        <option value=rechazar>Rechazar</option>
    </select></p>
    <p><label>Observaci&oacute;n (opcional):</label>
    <textarea name="observacion" cols=50 rows=4></textarea>
    </p>
    <p class="espacio-submit"><input type='submit' value='Guardar' class="btn btn-primary"></input><input type='hidden' value='1' name='submitted'></input>
<?php

if (isset($_POST['submitted'])) {
	foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }
	
	//Cambio de estado.
	if(isset($_POST['accion']) && $_POST['accion']!="ninguno")
	{
		$accion = $_POST['accion'];		
		$nro_ot = $_POST['nro_ot'];
		//Nuevo estado
		//MAX ID (Id del estado actual de la OT)
		$rs = mysql_query("SELECT MAX(idhistorial_ot) AS id FROM `historial_ot` WHERE `orden_de_trabajo_idorden_de_trabajo`=$nro_ot") or die(mysql_error());
		$row = mysql_fetch_row($rs);
		if ($row[0] != NULL){
			//Se termina el estado actual por lo que se le asigna fecha de termino.
			$query =  "UPDATE `historial_ot` 
					 SET `termino`=NOW() 
		           	 WHERE  `idhistorial_ot`= $row[0]" ;
			mysql_query($query) or die(mysql_error());
		}
		
		//Ingresar nuevo estado
		if($_POST['accion'] == "aprobar"){
			$estado = "GENERACIÓN OTT";
		}else if ($_POST['accion'] == "rechazar"){
			$estado = "RECHAZADA";
		}
		
		if (isset($_POST['observacion'])){
			$sql = "INSERT INTO `historial_ot` 
	        (`idhistorial_ot`, `orden_de_trabajo_idorden_de_trabajo`, `estado`, `inicio`, `termino`, `observacion`)
	        VALUES (NULL, $nro_ot, '$estado',NOW(), NULL, '".$_POST['observacion']."');";
		}else {
			$sql = "INSERT INTO `historial_ot`
	        (`idhistorial_ot`, `orden_de_trabajo_idorden_de_trabajo`, `estado`, `inicio`, `termino`, `observacion`)
	        VALUES (NULL, $nro_ot, '$estado',NOW(), NULL, NULL);";		
		}
        
    	$result = mysql_query($sql) or die(mysql_error());
    	if(!$result){
    		echo "<br>Fallo al cambiar de estado <br>";
    	}else {
    		echo "<br>La OT Nº ".$nro_ot." ha sido cambiada de estado .<br>";
    	}
	}


}
	
    //Parametro obtenido del combobox
     $sql= "SELECT `idorden_de_trabajo`, `nombre`, `apellido`, `anexo`, `ciudad`, `faena`, `area`, `tipo_ot`, `subtipo_ot`, `descripcion`, `observaciones`, `evaluacion_tecnica` 
       FROM `orden_de_trabajo`, `historial_ot` 
       WHERE  `estado` = 'APROBACIÓN CMP' AND `idorden_de_trabajo` = `orden_de_trabajo_idorden_de_trabajo`  AND `termino` IS NULL " ;    

   //echo $sql."<br>";

   $result = mysql_query($sql) or die(mysql_error());
   $rows = mysql_num_rows($result);
?>
    <div style="overflow-x: auto; overflow-y: hidden;">
<?php
     if ( $rows == 0) : ?>
    	<p>No hay ordenes de trabajo por revisar.</p>
<?php else : ?>
	<h4>Seleccione una orden de trabajo a modificar</h4>
	<br>
    <table id="tabla_ot" class="ui-widget ui-widget-content table table-striped table-bordered">
      <thead class="ui-widget-header">
      <tr>
      	<th scope="col">sel</th>
   		<th scope="col">Nro OT</th>
		<th scope="col">Nombre</th>
		<th scope="col">Apellido</th>		
		<th scope="col">anexo</th>
		<th scope="col">Ciudad</th>
		<th scope="col">Faena</th>
		<th scope="col">area</th>
		<th scope="col">tipo</th>
		<th scope="col">subtipo</th>
		<th scope="col">descripci&oacute;n</th>
		<th scope="col">observaciones</th>
		<th scope="col">evaluaci&oacute;n t&eacute;cnica</th>
      </tr>
      </thead>
      <tbody>
   <?php
     for ($i = 0; $i < $rows; $i++)
     $ot[] = mysql_fetch_assoc($result);
   ?>
	    <?php for ($i = 0; $i < $rows; $i++): ?>
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
	        <td><?php echo "<a href='../../public_html/upload/archivos/".$ot[$i]['evaluacion_tecnica']."' >".$ot[$i]['evaluacion_tecnica']."</a>"; ?></td>	        	        
	 </tr>
    <?php
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
