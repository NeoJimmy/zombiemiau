<?php session_start();ob_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- ADMINISTRAR USUARIOS -->
<html>
    <head>
    <!--cabecera estandar-->
    <?php include ("../include/head.php"); ?>
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
//Si existe una sesion abierta del admin, muestro el contenido
if ( isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil'] == 'admin')): 
?>

<div id="content">

<? include('../include/conect.php');

// Conexion a la base de datos
$db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
mysql_select_db($config['db_database']);
mysql_query("SET NAMES 'utf8'");

// Eliminar
  if (isset($_GET['delete']))
  {
    $query = 'DELETE FROM usuario WHERE idUsuario = '.$_GET['delete'];
    mysql_query($query);
    unset($_GET['delete']);
   	header('Location:admin_usuarios.php');
  }
?>
<div id="dialog-confirm" title="Eliminar usuario:">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>&iquest;Desea eliminar este usuario?</p>
</div>

<?php
//listar
$query = "SELECT `idUsuario`, `rut`,  `usuario`.nombre as nombreus, `contrasena`, `email`, `idperfil` ,  `perfil`.nombre as nombreper, `descripcion`
FROM  `usuario`, `perfil`
WHERE `idperfil`=`perfil_idperfil`";

$result = mysql_query($query);
$rows = mysql_num_rows($result);

      if ( $rows == 0): ?>
        <p>No hay registros en la base de datos</p>
		<ul>
        	<p><a href="nuevo.php">Nuevo usuario</a></p>
    	</ul >
<?php 
	else: 
?>
		<br>
	    <ul>
	        <a href="nuevo.php">Nuevo usuario</a>
	    </ul>
	    <br>
	   <div style="overflow-x: auto; overflow-y: hidden;">
	    <table class="ui-widget ui-widget-content">
	      <thead class="ui-widget-header">
	      <tr>
		      <th scope="col">rut</th>
		      <th scope="col">usuario</th>
		      <th scope="col">e-mail</th>
		      <th scope="col">perfil</th>
		      <th scope="col">descripci&oacute;n del perfil</th>
		      <th scope="col">acciones</th>
	      </tr>
	      </thead>
	      <tbody>
	   <?php
	     for ($i = 0; $i < $rows; $i++)
	     	$usuarios[] = mysql_fetch_assoc($result);
	   ?>
		    <?php for ($i = 0; $i < $rows; $i++): ?>
		      <tr>
		        <td><?php echo $usuarios[$i]['rut']; ?></td>
		        <td><?php echo $usuarios[$i]['nombreus']; ?></td>
	            <td><?php echo $usuarios[$i]['email']; ?></td>
	            <td><?php echo $usuarios[$i]['nombreper']; ?></td>
	            <td><?php echo $usuarios[$i]['descripcion']; ?></td>
	            <td>
	                <div id="admin">
	                <ul>
	                    <li id="admin">
	                        <a href="editar.php?id=<?php echo $usuarios[$i]['idUsuario']; ?>" >Editar</a>
	                    </li>
	                    <li id="admin">
	                        <a id="opener" href="admin_usuarios.php?delete=<?php echo $usuarios[$i]['idUsuario']; ?>"> Eliminar</a>
	                    </li>
	
	                </ul>
	                </div>
	             </td>
	          </tr>
	    	<?php endfor; ?>
	      </tbody>
	    </table>
    </div>
<?php 
	endif; 
?>
</div><!-- content -->

<?php 
	endif; 
?>
<!-- div footer-->
<?php include ("../include/footer.php") ?>
</div>
</body>
</html>
