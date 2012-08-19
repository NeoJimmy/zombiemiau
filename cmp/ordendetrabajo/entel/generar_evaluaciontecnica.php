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
<br>
<h2>Generar evaluaci&oacute;n t&eacute;cnica</h2>
<br>
<form id='form' action='' method='POST' enctype="multipart/form-data">
    <p><b>Adjuntar evaluaci&oacute;n t&eacute;cnica:</b><br>
    <input name="file" type="file"></input></p>
     <p>(archivo con extensi&oacute;n *.xlsx o *.xls)
	<p><b>Cambiar a nuevo estado:</b><br>
    <select name="estado">
    <?php
        echo "<option value=ninguno>Seleccione uno...</option>\n";

        $query="SELECT estado FROM estado_ot";
        $result = mysql_query($query) or die(mysql_error());

        if ($result)
        while($renglon = mysql_fetch_array($result))
        {
            $valor = str_replace(" ", "_",$renglon['estado']);
            $opcion = $renglon['estado'];
        	echo "<option value=".$valor.">".$opcion."</option>\n";
         }
    ?>
    </select></p>
    <p><input type='submit' value='Guardar'></input><input type='hidden' value='1' name='submitted'></input>
<?php

if (isset($_POST['submitted'])) {
	foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }
	
	//Subir evaluacion tecnica.
	if(isset($_POST['nro_ot']) && isset($_FILES['file']) && isset($_POST['estado']) && $_POST['estado']!="ninguno")
	{
		$nombre_archivo = $_FILES['file']['name'];
		$tipo_archivo = $_FILES['file']['type'];
		$tamano_archivo = $_FILES['file']['size'];
		$directorio = '../../public_html/upload/archivos';
		
		//compruebo si las características del archivo son las que deseo
		if ( ( !strpos($tipo_archivo, "xlsx") || !strpos($tipo_archivo, "xls") ) && ($tamano_archivo > 3000000)) {
		   	echo "La extensión o el tamaño de los archivos no es correcta. <br><br><table><tr><td><li>Se permiten archivos .xls o .xlsx<br><li>se permiten archivos de 3 Mb máximo.</td></tr></table>";
		} else if (move_uploaded_file($_FILES['file']['tmp_name'], "$directorio/$nombre_archivo")) {
            chmod("$directorio/$nombre_archivo", 0755);
            echo "<br>El archivo ha sido cargado correctamente.";            
		 }else{
		    echo "Ocurrió algún error al subir el archivo. No pudo guardarse.";
		  }

		$nro_ot = $_POST['nro_ot'];
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
	if(isset($_POST['nro_ot'])&& isset($_POST['estado']) && $_POST['estado']!="ninguno")
	{
		
		$nro_ot = $_POST['nro_ot'];
		//Nuevo estado
		$estado = str_replace("_", " ", $_POST['estado']);
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
		$sql = "INSERT INTO `historial_ot`
        (`idhistorial_ot`, `orden_de_trabajo_idorden_de_trabajo`, `estado`, `inicio`, `termino`)
        VALUES (NULL, $nro_ot, '$estado',NOW(), NULL);";
        
    	$result = mysql_query($sql) or die(mysql_error());
    	if(!$result){
    		echo "Fallo en crear el nuevo estado <br>";
    	}else {
    		echo "<br>Estado de la OT cambiado.<br>";
    	}
	}
}
	
    //Parametro obtenido del combobox
         $sql= "SELECT `idorden_de_trabajo`, `nombre`, `apellido`, `anexo`, `ciudad`, `faena`, `area`, `tipo_ot`, `subtipo_ot`, `descripcion`, `observaciones`, `evaluacion_tecnica`
           FROM `orden_de_trabajo`, `historial_ot` 
           WHERE  `estado` = 'GENERACIÓN OT' AND `idorden_de_trabajo` = `orden_de_trabajo_idorden_de_trabajo`  AND `termino` IS NULL " ;    

   //echo $sql."<br>";

   $result = mysql_query($sql) or die(mysql_error());
   $rows = mysql_num_rows($result);
?>
    <div style="overflow-x: auto; overflow-y: hidden;">
<?php
     if ( $rows == 0) : ?>
    	<p>No se encontraron &oacute;rdenes de trabajo validadas por CMP.</p>
<?php else : ?>
	<h4>Seleccione una orden de trabajo a modificar</h4>
	<br>
    <table id="tabla_ot" class="ui-widget ui-widget-content">
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
	        <td><?php echo $ot[$i]['evaluacion_tecnica']; ?></td>        
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

</div> <!-- content -->

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

</div><!-- container -->

</body>
</html>