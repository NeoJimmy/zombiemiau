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

<h2>B&uacute;squeda de OT por solicitante</h2>

<form id='form' action='' method='POST'>
    <table id="clear" style="width:100%;">
    	<tbody id="clear">
    	<tr id="clear">
    		<td id="clear">
			    <p><b>Seleccione solicitante:</b><br>
			    <select name="solicitante">
			    <?php
			        echo "<option value=ninguno>Seleccione uno...</option>\n";
			
			        $query="SELECT DISTINCT nombre, apellido FROM orden_de_trabajo";
			        $result = mysql_query($query) or die(mysql_error());
			
			        if ($result)
			        while($renglon = mysql_fetch_array($result))
			        {
			            $valor = str_replace(" ", "%", $renglon['nombre']."_".$renglon['apellido']);
			            $opcion = $renglon['nombre']." ".$renglon['apellido'];
			            if ($valor != $_POST['solicitante'])
			                echo "<option value=".$valor.">".$opcion."</option>\n";
			            else
			                echo "<option selected value=".$valor.">".$opcion."</option>\n";
			        }
			    ?>
			    </select></p>
			    <p><input type='submit' value='Buscar' class='btn btn-primary'></input><input type='hidden' value='1' name='submitted'></input>
		    </td>
    	</tbody>
    </table>
</form>

<?php

if (isset($_POST['submitted'])) {
	foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }
	$solicitante = str_replace("%", " ", $_POST['solicitante']);
	
    //Parametro obtenido del combobox
    if($solicitante!="ninguno")
    {
		$nombre_apellido = explode("_", $solicitante);
		$nombre = $nombre_apellido[0];
		$apellido = $nombre_apellido[1];

        $sql= "SELECT `idorden_de_trabajo`, `nombre`, `apellido`, `anexo`, `ciudad`, `faena`, `area`, `tipo_ot`, `subtipo_ot`, `descripcion`, `observaciones`, `estado`, `evaluacion_tecnica`  
               FROM `orden_de_trabajo`, `historial_ot`
           	   WHERE  `nombre` = '$nombre' AND `apellido` = '$apellido' AND `idorden_de_trabajo` = `orden_de_trabajo_idorden_de_trabajo` AND `termino` IS NULL ";

	   //echo $sql."<br>";
	
	   $result = mysql_query($sql) or die(mysql_error());
	   $rows = mysql_num_rows($result);
   }else{ 
   		exit();
   }
   
?>
    <div style="overflow-x: auto; overflow-y: hidden;">
<?php
     if ( $rows == 0) : ?>
    	<p>No se encontraron registros con los datos ingresados.</p>
<?php else : ?>
	<h4>Orden de trabajo</h4>
	<br>
    <table id="tabla_ot" class="table table-striped table-bordered">
      <thead>
      <tr>
      	<th scope="col">Nro de OT</th>
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
		<th scope="col">Estado actual</th>		
      </tr>
      </thead>
      <tbody>
   <?php
     for ($i = 0; $i < $rows; $i++)
     $ot[] = mysql_fetch_assoc($result);
   ?>
	    <?php for ($i = 0; $i < $rows; $i++): ?>
	<tr>
		<td><?php echo $ot[$i]['idorden_de_trabajo'];?></td>
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
        <td><?php echo "<a href='../public_html/upload/archivos/".$ot[$i]['evaluacion_tecnica']."' >".$ot[$i]['evaluacion_tecnica']."</a>"; ?></td>        
        <td><?php echo $ot[$i]['estado']; ?></td>
	 </tr>
    <?php
             endfor;
    ?>
      </tbody>
    </table>
   
<?php endif; ?>
    </div><!-- overflow -->
    <input class="btn" type="button" value="Descargar Reporte" onclick="location.href='reporte_solicitante.php?solicitante=<?php echo $_POST['solicitante']; ?>'">
<?php
}//submitted
?>

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
