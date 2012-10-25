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

<h2>Revisar &oacute;rdenes de trabajo en evaluaci&oacute;n comercial</h2>

<form id='form' action='' method='POST' enctype="multipart/form-data">
    <p><b>Acci&oacute;n:</b><br>
    <select name="accion">
        <option value=ninguno>Seleccione una acci&oacute;n...</option>
        <option value=aprobar>Aprobar</option>
        <option value=aprobarsubir>Aprobar y cambiar evaluaci&oacute;n</option>
    </select></p>
    <p><b>Adjuntar evaluaci&oacute;n t&eacute;cnica:</b><br>
    <input name="file" type="file"></p>
     <p>(archivo con extensi&oacute;n *.xlsx o *.xls)
    <p><input type='submit' value='Guardar' class='btn btn-primary'><input type='hidden' value='1' name='submitted'>
<?php

if (isset($_POST['submitted'])) {
	foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }
	
	if(isset($_FILES['file']) && isset($_POST['nro_ot']) && isset($_POST['accion']) && ($_POST['accion']=="aprobarsubir") )
	{
		$nro_ot = $_POST['nro_ot'];
		$nombre_archivo = $_FILES['file']['name'];
		$tipo_archivo = $_FILES['file']['type'];
		$tamano_archivo = $_FILES['file']['size'];
		$directorio = '../../public_html/upload/archivos';
		$rs = mysql_query("SELECT `evaluacion_tecnica` FROM `orden_de_trabajo` WHERE `idorden_de_trabajo`=$nro_ot") or die(mysql_error());
		$row = mysql_fetch_row($rs);
		$borrar_nombre = $row[0];		
		
		//se borra el anterior
		unlink($directorio."/".$borrar_nombre);
		
		//compruebo si las características del archivo son las que deseo
		if ( ( !strpos($tipo_archivo, "xlsx") || !strpos($tipo_archivo, "xls") ) && ($tamano_archivo > 3000000)) {
		   	echo "La extensión o el tamaño de los archivos no es correcta. <br><br><table><tr><td><li>Se permiten archivos .xls o .xlsx<br><li>se permiten archivos de 3 Mb máximo.</td></tr></table>";
		} else if (move_uploaded_file($_FILES['file']['tmp_name'], "$directorio/$nombre_archivo")) {
            chmod("$directorio/$nombre_archivo", 0755);
            echo "<br>El archivo ha sido cargado correctamente.";            
		 }else{
		    echo "Ocurrió algún error al subir el archivo. No pudo guardarse.";
		  }

		
		$query =  "UPDATE `orden_de_trabajo` 
						 SET `evaluacion_tecnica`= '".$nombre_archivo."' 
			           	 WHERE  `idorden_de_trabajo`= $nro_ot " ;
		$result = mysql_query($query) or die(mysql_error());
		if(!$result){
    		echo "Fallo en asignar ingresar evaluaci&oacute;n en la base de datos<br>";
    	} else {
    		echo "<br>Evaluaci&oacute;n ingresada correctamente.<br>";
    	}
	}
	
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
						
		$sql = "INSERT INTO `historial_ot`
        (`idhistorial_ot`, `orden_de_trabajo_idorden_de_trabajo`, `estado`, `inicio`, `termino`, `observacion`)
        VALUES (NULL, $nro_ot, 'APROBACIÓN CMP',NOW(), NULL, NULL);";

    	$result = mysql_query($sql) or die(mysql_error());
    	if(!$result){
    		echo "<br>Fallo al cambiar de estado <br>";
    	}else {
    		echo "<br>La OT Nº ".$nro_ot." ha sido aprobada.<br>";
    	}
   	    $sql3 = "INSERT INTO `historial_ot_usuario` (`idhistorial_ot_usuario`, `historial_ot_idhistorial_ot`, `usuario`) VALUES (NULL, ".mysql_insert_id().", '".$_SESSION['usuario']['nombre']."');";
		mysql_query($sql3) or die(mysql_error());

	}

}
	
    //Parametro obtenido del combobox
     $sql= "SELECT `idorden_de_trabajo`, `nombre`, `apellido`, `anexo`, `ciudad`, `faena`, `area`, `tipo_ot`, `subtipo_ot`, `descripcion`, `observaciones` , `evaluacion_tecnica` 
       FROM `orden_de_trabajo`, `historial_ot` 
       WHERE  `estado` = 'EVALUACIÓN COMERCIAL' AND `idorden_de_trabajo` = `orden_de_trabajo_idorden_de_trabajo`  AND `termino` IS NULL " ;    

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
   	        <td><?php if(isset($ot[$i]['evaluacion_tecnica'])) echo "<a href='../../public_html/upload/archivos/".$ot[$i]['evaluacion_tecnica']."' >".$ot[$i]['evaluacion_tecnica']."</a>"; ?></td>        
	 </tr>
    <?php
             endfor;
    ?>
      </tbody>
    </table>


<?php endif; ?>
    </div><!-- overflow -->
</form>
<?php
//submitted
?>

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
