<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- -->
<html>
<head>
  <!--cabecera estandar-->
  <?php include ("../include/head.php")?>
  
  <script type="text/javascript" src="../js/jquery.ui.datepicker-es.js"></script>

  <script type="text/javascript">
	$(function() {
		var dates = $( "#from, #to" ).datepicker({
                        regional: "es",
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 3,
			onSelect: function( selectedDate ) {
				var option = this.id == "from" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" );
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
		});
	});
  </script>

    <!--necesario para tabs y UI-->
    <link type="text/css" href="http://localhost/cmp/css/cupertino/jquery-ui-1.8.9.custom.css" rel="stylesheet"></link>
    <script type="text/javascript" src="http://localhost/cmp/js/jquery-ui-1.8.9.custom.min.js"></script>

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

<h2>B&uacute;squeda de Orden de trabajo por Fecha</h2>


<form id='form' action='' method='POST'>
	<p><label for="from">Desde</label>
    <input type="text" id="from" name="from" value="<?php echo (isset($_POST['from'])) ? $_POST['from'] : NULL ?>"></input><br></p>
    <p><label for="to">hasta</label>
    <input type="text" id="to" name="to" value="<?php echo (isset($_POST['to'])) ? $_POST['to'] : NULL ?>"></input></p>
    <p class="espacio-submit"><input type='submit' value='Buscar' class='btn btn-primary'></input><input type='hidden' value='1' name='submitted'></input>
</form>

<?php

if (isset($_POST['submitted'])) {
	foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }
	
         $sql= "SELECT DISTINCT `idorden_de_trabajo`, `nombre`, `apellido`, `anexo`, `ciudad`, `faena`, `area`, `tipo_ot`, `subtipo_ot`, `descripcion`, `observaciones`, `evaluacion_tecnica`
           FROM `orden_de_trabajo`, `historial_ot` 
           WHERE `idorden_de_trabajo` = `orden_de_trabajo_idorden_de_trabajo` AND `inicio` BETWEEN STR_TO_DATE('".$_POST['from']."', '%d/%m/%Y')  AND STR_TO_DATE('".$_POST['to']."', '%d/%m/%Y') " ;    

   //echo $sql."<br>";

   $result = mysql_query($sql) or die(mysql_error());
   $rows = mysql_num_rows($result);
?>
    <div style="overflow-x: auto; overflow-y: hidden;">
<?php
     if ( $rows == 0) : ?>
    	<p>No se encontraron registros con los datos ingresados.</p>
<?php else : ?>
	<h4>Orden de trabajo</h4>
	<br>
    <table id="tabla_ot" class="ui-widget ui-widget-content table table-striped table-bordered">
      <thead class="ui-widget-header">
      <tr>
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

   
<?php endif; ?>
    </div><!-- overflow -->
    <input class="btn" type="button" value="Descargar Reporte" onclick="location.href='reporte_fecha.php?from=<?php echo $_POST['from']."&to=".$_POST['to']; ?>'">
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
