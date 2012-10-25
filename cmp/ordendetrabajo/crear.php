<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!---->
<html>
<head>
  <!--cabecera estandar-->
  <?php include ("../include/head.php")?>
  <!--combobox dependiente-->
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
			makeSublist('parent2', 'child2', true, '1');
		});
  </script>
   <script type="text/javascript">
  		$(document).ready(function(){
    		$("#solicitud").validate();
  		});
  </script>

  
</head>
<body>

<div id="container">

<!-- div menu -->
<?php include ("../include/menu.php"); ?>

<div id="content">

<?php
include('../include/conect.php');

$db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
mysql_select_db($config['db_database']);
mysql_query("SET NAMES 'utf8'");

    if (isset($_POST['submitted'])) {
    	foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }

		$_POST['faena'] = str_replace("_", " ", $_POST['faena']);
		$_POST['ciudad'] = str_replace("_", " ", $_POST['ciudad']);
		$_POST['tipo'] = str_replace("_", " ", $_POST['tipo']);
		$_POST['subtipo'] = str_replace("_", " ", $_POST['subtipo']);
		
	    //Agregamos el elemento
	    $sql= "INSERT INTO `orden_de_trabajo`
	    (`idorden_de_trabajo`, `nombre`, `apellido`,  `anexo`, `ciudad`, `faena`, `area`, `tipo_ot`, `subtipo_ot`, `descripcion`, `observaciones`)
	    VALUES (NULL, '{$_POST['nombre']}', '{$_POST['apellido']}', '{$_POST['anexo']}', '{$_POST['ciudad']}', '{$_POST['faena']}', '{$_POST['area']}', '{$_POST['tipo']}', '{$_POST['subtipo']}', '{$_POST['descripcion']}', '{$_POST['observaciones']}');";
	    mysql_query($sql) or die(mysql_error());
	    //echo $sql.'<br>';
	    $id = mysql_insert_id();
	    
	    //En caso de que sea generada por operadora, la OT queda en estado CREADA
	    if($_SESSION['usuario']['perfil']=='operadora'){
	   	    $sql2 = "INSERT INTO `historial_ot`
				(`idhistorial_ot`, `orden_de_trabajo_idorden_de_trabajo`, `estado`, `inicio`, `termino`, `observacion`)
				VALUES (NULL, $id, 'CREADA',NOW(), NULL, NULL);";	
	   	    mysql_query($sql2) or die(mysql_error());
	   	    
	   	    $sql3 = "INSERT INTO `historial_ot_usuario` (`idhistorial_ot_usuario`, `historial_ot_idhistorial_ot`, `usuario`) VALUES (NULL, ".mysql_insert_id().", '".$_SESSION['usuario']['nombre']."');";
			mysql_query($sql3) or die(mysql_error());
		}

	    //Si la OT es generada por los admins de CMP, esta es validada inmediatamente, pasando al estado de Generacion OT
		if ($_SESSION['usuario']['perfil']=='admin_cmp' || $_SESSION['usuario']['perfil']=='admin_cmp2' || $_SESSION['usuario']['perfil']=='admin') {
		    $sql2 = "INSERT INTO `historial_ot`
				(`idhistorial_ot`, `orden_de_trabajo_idorden_de_trabajo`, `estado`, `inicio`, `termino`, `observacion`)
				VALUES (NULL, $id, 'CREADA',NOW(), NOW(), NULL);";	
   	    	mysql_query($sql2) or die(mysql_error());
   	    
	   	    $sql3 = "INSERT INTO `historial_ot_usuario` (`idhistorial_ot_usuario`, `historial_ot_idhistorial_ot`, `usuario`) VALUES (NULL, ".mysql_insert_id().", '".$_SESSION['usuario']['nombre']."');";
			mysql_query($sql3) or die(mysql_error());

			$sql2 = "INSERT INTO `historial_ot`
				(`idhistorial_ot`, `orden_de_trabajo_idorden_de_trabajo`, `estado`, `inicio`, `termino`, `observacion`)
				VALUES (NULL, $id, 'GENERACIÓN OT',NOW(), NULL, NULL);";
			mysql_query($sql2) or die(mysql_error());
			
   	    	$sql3 = "INSERT INTO `historial_ot_usuario` (`idhistorial_ot_usuario`, `historial_ot_idhistorial_ot`, `usuario`) VALUES (NULL, ".mysql_insert_id().", '".$_SESSION['usuario']['nombre']."');";
			mysql_query($sql3) or die(mysql_error());

    	}
	   // echo $sql2.'<br>';
	    
	    
	    echo "Fila Agregada.<br>";
	    echo "<a href='../index.php'>Volver al inicio</a><br><br>";
    }
    //Se calcula el próximo id de la orden de trabajo.
	$rs = mysql_query("SELECT MAX(`idorden_de_trabajo`) FROM `orden_de_trabajo`") or die(mysql_error());
	$row = mysql_fetch_row($rs);
	$nro_siguiente_ot = $row[0]+1;
?>

<h2>Orden de trabajo Nº <?php echo $nro_siguiente_ot;?></h2>
		<form id="solicitud" action='' method='POST'>

		<fieldset>
		<legend>Datos del solicitante</legend>
			<br>
		    <p><label>Nombre:</label><input name="nombre" type="text" size="37" class="required"></p>
		    <p><label>Apellido:</label><input name="apellido" type="text" size="37" class="required"></p>
		    <p><label>Anexo:</label><input name="anexo" type="text" size="37" class="required"></p>
		    <p><label>Ciudad:</label>
		    <select id="parent" name="ciudad">
		    <?php
		    	$query="SELECT DISTINCT ciudad FROM faenas";
		        $result = mysql_query($query) or die(mysql_error());
		        if ($result)
		        while($renglon = mysql_fetch_array($result))
		        {
		            $opcion = $renglon['ciudad'];
		            $valor = str_replace(" ", "_",$renglon['ciudad']);
		            echo "<option value=".$valor.">".$opcion."</option>\n";
		        }
			?>
		    </select></p>
		    <p><label>Faena:</label>
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
	    	        echo "<option class='sub_".$clase."' value=".$valor.">".$opcion."</option>\n";
		        }
		    ?>
		    </select></p>
		    <p><label>&Aacute;rea:</label><input name="area" type="text" size="37" class="required"></p>
		</fieldset>
		<br>
		<fieldset>
			<legend>Datos de la solicitud</legend>
			<br>
		    <p><label>Tipo de OT:</label>
		    <select id="parent2" name="tipo">
		    <?php
		        $query="SELECT DISTINCT tipo FROM tipo_ot";
		        $result = mysql_query($query) or die(mysql_error());
		        if ($result)
		        while($renglon = mysql_fetch_array($result))
		        {
		            $opcion = $renglon['tipo'];
		            $valor =  str_replace(" ", "_", $renglon['tipo']);
	    	        echo "<option value=".$valor.">".$opcion."</option>\n";
		        }
		    ?>
		    </select>&ensp;
		    <select id="child2" name="subtipo">
   		    <?php
		        $query="SELECT tipo, subtipo FROM tipo_ot";
		        $result = mysql_query($query) or die(mysql_error());
		        if ($result)
		        while($renglon = mysql_fetch_array($result))
		        {
		            $opcion = $renglon['subtipo'];
		            $valor =  str_replace(" ", "_", $renglon['subtipo']);
  		            $clase = str_replace(" ", "_", $renglon['tipo']);
	    	        echo "<option class='sub_".$clase."' value=".$valor.">".$opcion."</option>\n";
		        }
		    ?>
		    </select>
		    <p><label>Descripci&oacute;n de la solicitud:</label><textarea name="descripcion" class="required" cols=50 rows=4></textarea></p>
		    <p><label>Observaciones:</label><textarea name="observaciones" cols=50 rows=4  class=""></textarea></p>
		</fieldset>
		<br>
		<div class="demo">
		<p class="espacio-submit"><input type='submit' value='Crear OT' class="btn btn-primary"><input type='hidden' value='1' name='submitted'>
		</div>
</div><!--content-->

<!-- div footer-->
<?php include ("../include/footer.php") ?>

</div>

</body>
</html>
