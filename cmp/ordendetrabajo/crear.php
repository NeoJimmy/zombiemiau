<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!---->
<html>
<head>
  <!--cabecera estandar-->
  <?php include ("../include/head.php")?>
  <script type="text/javascript" src="../js/jquery.ui.datepicker-es.js"></script>

  <script type="text/javascript">
	$(function() {
		var dates = $( "#from").datepicker({
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
	
	$(function() {
		$( "input:submit, button" ).button();
	});
	
	
  </script>
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

// Proximo id a insertar
$rs = mysql_query("SELECT MAX(idorden_de_trabajo) AS id FROM orden_de_trabajo") or die(mysql_error());
$row = mysql_fetch_row($rs);
if ($row[0] != NULL) {
	$id = $row[0]+1;
} else {
	$id = 1;
}

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

    //echo $sql.'<br>';
    
    //Si la OT es generada por los admins de CMP, esta es validada inmediatamente, pasando al estado de Generacion OT
    if($_SESSION['usuario']['perfil']=='operadora'){
		$sql2 = "INSERT INTO `historial_ot`
		(`idhistorial_ot`, `orden_de_trabajo_idorden_de_trabajo`, `estado`, `inicio`, `termino`, `observacion`)
		VALUES (NULL, $id, 'CREADA',NOW(), NULL, NULL);";
    } else if ($_SESSION['usuario']['perfil']=='admin_cmp' || $_SESSION['usuario']['perfil']=='admin_cmp2' || $_SESSION['usuario']['perfil']=='admin'){
		$sql2 = "INSERT INTO `historial_ot`
		(`idhistorial_ot`, `orden_de_trabajo_idorden_de_trabajo`, `estado`, `inicio`, `termino`, `observacion`)
		VALUES (NULL, $id, 'GENERACIÓN OT',NOW(), NULL, NULL);";
    }
   // echo $sql2.'<br>';
        
    $result = mysql_query($sql) or die(mysql_error());
    $result2 = mysql_query($sql2) or die(mysql_error());    
    if (!$result || !$result2)
    {
        echo "La Inserción contiene errores. <br> Datos mal ingresados";
        ?>
        <br>
        <a href="../index.php">Volver al inicio</a><br><br>
        <?php
        exit();
    }

    echo "Fila Agregada.<br>";
    echo "<a href='../index.php'>Volver al inicio</a><br><br>";
    }

?>
<br>
<h4 class="ui-widget">Orden de trabajo Nº <?php echo $id;?></h4>
		<form id="solicitud" action='' method='POST'>
		<br>
		<fieldset class="ui-widget ui-state-default ui-corner-all">
		<legend class="ui-widget">Datos del solicitante</legend>
		    <br>
		    <p><label>Nombre:</label><input name="nombre" type="text" size="37" class="required ui-widget ui-corner-all"></p>
		    <p><label>Apellido:</label><input name="apellido" type="text" size="37" class="required ui-widget ui-corner-all"></p>
		    <p><label>Anexo:</label><input name="anexo" type="text" size="37" class="required ui-widget ui-corner-all"></p>
		    <p><label>Ciudad:</label>
		    <select id="parent" name="ciudad" class="ui-widget ui-state-default ui-corner-all">
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
		    <select id="child" name="faena" class="ui-widget ui-state-default ui-corner-all">
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
		    <p><label>&Aacute;rea:</label><input name="area" type="text" size="37" class="required ui-widget ui-corner-all"></p>
		</fieldset>
		<br>
		<fieldset class="ui-widget ui-state-default ui-corner-all">
			<legend class="ui-widget">Datos de la solicitud</legend>
			<br>
		    <p><label>Tipo de OT:</label>
		    <select id="parent2" name="tipo" class="ui-widget ui-state-default ui-corner-all">
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
		    <select id="child2" name="subtipo" class="ui-widget ui-state-default ui-corner-all">
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
		    </p>
		    <p><label>Descripci&oacute;n de la solicitud:</label><textarea name="descripcion" class="required" cols=50 rows=4></textarea></p>
		    <p><label>Observaciones:</label><textarea name="observaciones" cols=50 rows=4  class="required"></textarea></p>
		</fieldset>
		<br>
		<div class="demo">
		<p><input type='submit' value='Crear OT' class="ui-button ui-widget ui-state-default ui-corner-all"><input type='hidden' value='1' name='submitted'>
		</div>
</div><!--content-->

<!-- div footer-->
<?php include ("../include/footer.php") ?>

</div>

</body>
</html>
