<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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

    <!-- validamos el formulario -->
    <script type="text/javascript">
    $(document).ready(function(){
    $("#myform").validate();
    });
    </script>

</head>
<body>


<?php
//Si existe una sesion muestro el contenido
if ( isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil'] == 'admin' ) ): ?>

<div id="container">

<!-- div menu -->
<?php include ("../include/menu.php"); ?>

<div id="content">

    <?
    include('../include/conect.php');

    $db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
    mysql_select_db($config['db_database']);
    mysql_query("SET NAMES 'utf8'");

	function checkbox_value($name) {
	    return (isset($_POST[$name]) ? 1 : 0);
	}
		
    if (isset($_POST['submitted'])) {
    foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }
	
	$_POST['subtipo'] = str_replace("_", " ", $_POST['subtipo']);
	$_POST['tipo'] = str_replace("_", " ", $_POST['tipo']);
	
    //Agregamos el elemento
    $sql= "INSERT INTO `guia_datostecnicos`
    (`idguia_datostecnicos`, `anexo`, `tipo`,  `subtipo`, `categoria`, `sap`, `correo_de_voz`, `claves`, `modelo`, `serie`, `mac`, `switch_puerta` )
    VALUES (NULL, '{$_POST['anexo']}', '{$_POST['tipo']}', '{$_POST['subtipo']}', '{$_POST['categoria']}', '{$_POST['sap']}', ".checkbox_value('correo_de_voz').", '{$_POST['claves']}', '{$_POST['modelo']}', '{$_POST['serie']}', '{$_POST['mac']}', '{$_POST['switch_puerta']}');";
    //echo $sql.'<br>';
    $result = mysql_query($sql);
    if (! $result){
                    echo "La Inserción contiene errores. <br> Datos mal ingresados";
                    ?>
                    <br>
                    <a href="admin_datostecnicos.php">Volver a Agenda</a><br><br>
                    <?php
                    exit();
                  }
    echo "Fila Agregada.<br>";
    echo "<a href='admin_datostecnicos.php'>Volver a Agenda</a><br><br>";
    }
    ?>
    <br><br><br>
    <a href="admin_datostecnicos.php">volver</a>
    <br><br>
    <h2>Nuevo registro de datos t&eacute;cnicos</h2>
    <br>
    <form id="myform"  action='' method='POST'>
    <p><label><b>anexo:</b></label><input type='text' name='anexo' class='required'><br>
    <p><label><b>tipo:</b></label>
    <select id="parent" name='tipo'>
    <?php
		$query="SELECT DISTINCT tipo FROM tipo_anexo";
	    $result = mysql_query($query) or die(mysql_error());
	    if ($result)
	    while($renglon = mysql_fetch_array($result))
	    {
	        $opcion = $renglon['tipo'];
	        $valor = str_replace(" ", "_",$renglon['tipo']);
	        if($valor != $_POST['tipo'])
	        	echo "<option value=".$valor.">".$opcion."</option>\n";
	        else
	        	echo "<option selected value=".$valor.">".$opcion."</option>\n";
	    }
	?>
    </select><br>
    <p><label><b>subtipo:</b></label>
    <select id="child" name='subtipo'>
    <?php
	    $query="SELECT tipo, subtipo FROM tipo_anexo";
	    $result = mysql_query($query) or die(mysql_error());
	    if ($result)
	    while($renglon = mysql_fetch_array($result))
	    {
	        $opcion = $renglon['subtipo'];
	        $valor =  str_replace(" ", "_", $renglon['subtipo']);
	        $clase = str_replace(" ", "_", $renglon['tipo']);
	        if($valor != $_POST['subtipo'])
	        	echo "<option class='sub_".$clase."' value=".$valor.">".$opcion."</option>\n";
	        else
	        	echo "<option selected class='sub_".$clase."' value=".$valor.">".$opcion."</option>\n";
	    }
	?>
    </select><br>
    <p><label><b>categor&iacute;a:</b></label><input type='text' name='categoria' class='required '><br>
    <p><label><b>sap:</b></label><input type='text' name='sap' class='required'><br>
	<p><label><b>correo de voz:</b></label><input type="checkbox" name='correo_de_voz' value='1'><br>
    <p><label><b>claves:</b></label><input type='text' name='claves' class='required'><br>
    <p><label><b>modelo:</b></label><input type='text' name='modelo' class='required'><br>
    <p><label><b>serie:</b></label><input type='text' name='serie' class='required'><br>
    <p><label><b>mac:</b></label><input type='text' name='mac' class='required'><br>
    <p><label><b>switch-puerta:</b></label><input type='text' name='switch_puerta' class='required'><br>
	<br>
    <p><input class='submit' style="position: absolute; left: -103px;" type='submit' value='Agregar Registro'><input type='hidden' value='1' name='submitted'>
    </form>

</div>

<?php
//se acabo la sesion
else :
?>
<br><center><h3>Tu sesi&oacute;n a expirado...</h3><br><br>
<p><a href='../index.php'>volver a Inicio</a></p></center>
<?php 
endif;                         
?>

<!-- div footer-->
<?php include ("../include/footer.php") ?>

</div>


</body>
</html>
