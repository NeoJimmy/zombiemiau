<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- -->
<html>
<head>
  <!--cabecera estandar-->
  <?php include ("../include/head.php")?>
  
  <script type="text/javascript">
		function makeSublist(parent,child,isSubselectOptional,childVal)
		{
			$("body").append("<select style='display:none' id='"+parent+child+"'></select>");
			$('#'+parent+child).html($("#"+child+" option"));
			
			var parentValue = $('#'+parent).attr('value');
			$('#'+child).html($('#'+parent+child+" .sub_"+parentValue).clone());
			
			childVal = (typeof childVal == "undefined")? "" : childVal ;
			$("#"+child).val(childVal).attr('selected','selected');
			
			$('#'+parent).change(function(){
				var parentValue = $('#'+parent).attr('value');
				$('#'+child).html($("#"+parent+child+" .sub_"+parentValue).clone());
			
				$('#'+child).trigger("change");
				$('#'+child).focus();
			});
		}
		
		$(document).ready(function()
		{
			makeSublist('parent','child', true, '1');
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

<h2>B&uacute;squeda de Orden de trabajo por Faena</h2>


<form id='form' action='' method='POST'>
	<p><b>Ciudad:</b><br>
	<select id="parent" name="ciudad">
	<?php
		$query="SELECT DISTINCT ciudad FROM faenas";
	    $result = mysql_query($query) or die(mysql_error());
	    if ($result)
	    while($renglon = mysql_fetch_array($result))
	    {
	        $opcion = $renglon['ciudad'];
	        $valor = str_replace(" ", "_",$renglon['ciudad']);
	        if($valor != $_POST['ciudad'])
	        	echo "<option value=".$valor.">".$opcion."</option>\n";
	        else
	        	echo "<option selected value=".$valor.">".$opcion."</option>\n";
	    }
	?>
	</select></p>
	<p><b>Faena:</b><br>
	<select id="child" name="faena">
	<?php
	    $query="SELECT ciudad, faena FROM faenas";
	    $result = mysql_query($query) or die(mysql_error());
	    if ($result)
	    while($renglon = mysql_fetch_array($result))
	    {
	        $opcion = $renglon['faena'];
	        $valor =  str_replace(" ", "_", $renglon['faena']);
	        $clase = str_replace(" ", "_", $renglon['ciudad']);
	        if($valor != $_POST['faena'])
	        	echo "<option class='sub_".$clase."' value=".$valor.">".$opcion."</option>\n";
	        else
	        	echo "<option selected class='sub_".$clase."' value=".$valor.">".$opcion."</option>\n";
	    }
	?>
	</select></p>
	<p><input type='submit' value='Buscar' class='btn btn-primary'></input><input type='hidden' value='1' name='submitted'></input>
</form>

<?php

if (isset($_POST['submitted'])) {
	foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }
	$faena = str_replace("_", " ", $_POST['faena']);
	$ciudad = str_replace("_", " ", $_POST['ciudad']);

	
         $sql= "SELECT `idorden_de_trabajo`, `nombre`, `apellido`, `anexo`, `ciudad`, `faena`, `area`, `tipo_ot`, `subtipo_ot`, `descripcion`, `observaciones`, `evaluacion_tecnica`, `estado` 
           FROM `orden_de_trabajo`, `historial_ot` 
           WHERE  `ciudad` = '$ciudad' AND `faena` = '$faena'  AND `idorden_de_trabajo` = `orden_de_trabajo_idorden_de_trabajo` AND `termino` IS NULL " ;    

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
    <table id="tabla_ot" class="table table-striped table-bordered">
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
	        <td><?php echo "<a href='../public_html/upload/archivos/".$ot[$i]['evaluacion_tecnica']."' >".$ot[$i]['evaluacion_tecnica']."</a>"; ?></td>	        
	        <td><?php echo $ot[$i]['estado']; ?></td>	        
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
    <input class="btn" type="button" value="Descargar Reporte" onclick="location.href='reporte_faena.php?ciudad=<?php echo $_POST['ciudad']."&faena=".$_POST['faena']; ?>'">    
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
