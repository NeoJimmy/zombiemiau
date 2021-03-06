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
<h2>B&uacute;squeda de Orden de trabajo por n&uacute;mero</h2>

<form id='form' action='' method='POST'>
    <table id="clear" style="width:100%;">
    	<tbody id="clear">
    	<tr id="clear">
    		<td id="clear">
			    <p><b>Nro. de Orden de trabajo:</b><br>
			    <select name="nro_ot">
			    <?php
			        echo "<option value=ninguno>Seleccione uno...</option>\n";
			
			        $query="SELECT idorden_de_trabajo AS nro_ot FROM orden_de_trabajo";
			        $result = mysql_query($query) or die(mysql_error());
			
			        if ($result)
			        while($renglon = mysql_fetch_array($result))
			        {
			            $valor=$renglon['nro_ot'];
			            if ($valor!=$_POST['nro_ot'])
			                echo "<option value=".$valor.">".$valor."</option>\n";
			            else
			                echo "<option selected value=".$valor.">".$valor."</option>\n";
			        }
			    ?>
			    </select></p>
			    <p><input type='submit' value='Buscar' class='btn btn-primary'><input type='hidden' value='1' name='submitted'>
		    </td>
    	</tbody>
    </table>
</form>

<?php

if (isset($_POST['submitted'])) {
	foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }
	    $nro_ot=$_POST['nro_ot'];
	
    //Parametro obtenido del combobox
    if($nro_ot!="ninguno")
    {
        $sql= "SELECT *
           FROM `orden_de_trabajo`
           WHERE  `idorden_de_trabajo`=$nro_ot" ;
           
        $sql2 =  "SELECT *
        	FROM `historial_ot`, `historial_ot_usuario`
			WHERE  `orden_de_trabajo_idorden_de_trabajo`=$nro_ot AND `historial_ot_idhistorial_ot`=`idhistorial_ot`" ;
    }

   //echo $sql."<br>";

   $result = mysql_query($sql) or die(mysql_error());
   $rows = mysql_num_rows($result);
   
   $result2 = mysql_query($sql2) or die(mysql_error());
   $rows2 = mysql_num_rows($result2);
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
<?php for ($i = 0; $i < $rows; $i++): ?>
	<tr>	
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
        <td><?php if(isset($ot[$i]['evaluacion_tecnica'])) echo "<a href='../public_html/upload/archivos/".$ot[$i]['evaluacion_tecnica']."' >".$ot[$i]['evaluacion_tecnica']."</a>"; ?></td>
		<td><?php echo $ot[$i]['nro_ott']; ?></td>	        
	 </tr>
<?php
    endfor;
?>
      </tbody>
    </table>
<br>
<h4>Historial de la Orden de trabajo</h4>
	<br>
    <table id="tabla_historial" class="table table-striped table-bordered">
      <thead>
      <tr>
		<th scope="col">Estado</th>
		<th scope="col">Inicio</th>		
		<th scope="col">T&eacute;rmino</th>
		<th scope="col">Observaci&oacute;n</th>
		<th scope="col">Usuario</th>
      </tr>
      </thead>
      <tbody>
   <?php
     for ($i = 0; $i < $rows2; $i++)
     $historial[] = mysql_fetch_assoc($result2);
     
     //date_format($fecha, 'Y-m-d H:i:s');
   ?>
	    <?php for ($i = 0; $i < $rows2; $i++): ?>
	<tr>	
	        <td><?php echo $historial[$i]['estado']; ?></td>
	        <?php 
	        	$inicio = new DateTime($historial[$i]['inicio']);
	        	$inicio = date_format($inicio, 'H:i:s d-m-Y');
	        	if($historial[$i]['termino'] != NULL)
	        	{
	        		$termino = new DateTime($historial[$i]['termino']);
	        		$termino = date_format($termino, 'H:i:s d-m-Y');
	        	}else{
	        		$termino = NULL;
	        	}
	        ?>
	        <td><?php echo $inicio;?></td>
	        <td><?php echo $termino; ?></td>
	        <td><?php echo $historial[$i]['observacion']; ?></td>
   	        <td><?php echo $historial[$i]['usuario']; ?></td>        	        
	</tr>
    <?php
             endfor;
    ?>
      </tbody>
    </table>
    <br>
   
<?php endif; ?>
    </div><!-- overflow -->
<input class="btn" type="button" value="Descargar Reporte" onclick="location.href='reporte_numero.php?nro_ot=<?php echo $_POST['nro_ot']; ?>'">
<input class="btn" type="button" value="Reporte Imprimible" onclick="location.href='reporte_imprimible.php?nro_ot=<?php echo $_POST['nro_ot']; ?>'">  
    
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
