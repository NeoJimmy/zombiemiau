<?php session_start();ob_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- ADMIN-->
<html>
<head>
  <!--cabecera estandar-->
  <?php include ("../include/head.php")?>
	<script type="text/javascript">
		$(function() {
			$("#dialog-confirm").dialog({
				resizable: false,
				height:140,
				modal: true,
				autoOpen: false
			});
			$("a#opener").click(function(e) {
				event.preventDefault();
				var targetUrl = $(this).attr("href");
				$("#dialog-confirm").dialog('option', 'buttons', {
					"Eliminar": function() {
	 					window.location.href = targetUrl;
					},
					"Cancelar": function() {
						$(this).dialog("close");
					}
				});
				$("#dialog-confirm").dialog("open");
			});
		});
	  </script>
</head>
<body>
<div id="container">
<!-- div menu -->
<?php include ("../include/menu.php"); ?>

<?php
//Si existe una sesion muestro el contenido
if ( isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil'] == 'admin' ) ): ?>

<div id="content">

<?php
include('../include/conect.php');

$db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
mysql_select_db($config['db_database']);
mysql_query("SET NAMES 'utf8'");

?>

<h2>Administrar ciudades y faenas de las ordenes de trabajo</h2>

<?php

// Eliminar elemento
  if ( isset( $_GET['delete'] ) )
  {
    $query = 'DELETE FROM faenas WHERE idfaenas = '.$_GET['delete'];
    mysql_query($query);
    unset($_GET['delete']);
    header('Location:admin_faenas.php');
  }
?>
<div id="dialog-confirm" title="Eliminar faenas:">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>&iquest;Desea eliminar este elemento?</p>
</div>
<p>
        <a class="btn btn-success" href="nueva_faena.php">Nuevo registro</a>
</p>
<?php

$sql= "SELECT *
       FROM `faenas` " ;

$result = mysql_query($sql) or die(mysql_error());
$rows = mysql_num_rows($result);
?>
    <div style="overflow-x: auto; overflow-y: hidden;">
<?php
     if ( $rows == 0) : ?>

    <p>No se encontraron registros con los datos ingresados.</p>

<?php else : ?>

    <br>
    <table id="tabla_agenda" class="ui-widget ui-widget-content table table-striped table-bordered">
      <thead class="ui-widget-header">
      <tr>
		<th scope="col">ciudad</th>
		<th scope="col">faena</th>		
		<th scope="col">acciones</th>
      </tr>
      </thead>
      <tbody>
   <?php
     for ($i = 0; $i < $rows; $i++)
     $faenas[] = mysql_fetch_assoc($result);
   ?>
	    <?php for ($i = 0; $i < $rows; $i++): ?>
	<tr>	
	        <td><?php echo $faenas[$i]['ciudad']; ?></td>
   	        <td><?php echo $faenas[$i]['faena']; ?></td>      
            <td>
            <div id="accion">
            <ul>
                <li id="accion">
                    <a href="editar_faena.php?id=<?php echo $faenas[$i]['idfaenas']; ?>" >Editar</a>
                </li>
                <li id="accion">
                    <a id="opener" href="admin_faenas.php?delete=<?php echo $faenas[$i]['idfaenas']; ?>"> Eliminar</a>
                </li>
            </ul>
            </div>
            </td>
    <?php
             endfor;
    ?>
      </tr>
      </tbody>
    </table>

<?php endif; ?>
    </div>
<?php if($rows) :?>

<?php endif;?>

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
