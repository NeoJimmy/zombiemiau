<?php session_start();ob_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
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
      <link type="text/css" href="../css/cupertino/jquery-ui-1.8.9.custom.css" rel="stylesheet"></link>
      <script type="text/javascript" src="../js/jquery-ui-1.8.9.custom.min.js"></script>
    </head>
<body>
<div id="container">
<!-- div menu -->
<?php include ("../include/menu.php"); ?>
<?php
//Si existe una sesion abierta del admin, muestro el contenido
if ( isset($_SESSION['usuario'] ) && ( $_SESSION['usuario']['perfil'] == 'admin' ) ) : 
?>
  <div id="content">
<?php include('../include/conect.php');
    // Conexion a la base de datos
    $db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
    mysql_select_db($config['db_database']);
    mysql_query("SET NAMES 'utf8'");
?>
      <h2>Administrar perfiles</h2>
<?php
      // Eliminar
        if ( isset( $_GET['delete'] ) )
        {
          $query = 'DELETE FROM perfil WHERE idperfil = '.$_GET['delete'];
          mysql_query($query);
          unset($_GET['delete']);
          header('Location:admin_perfiles.php');
        }
?>
      <div id="dialog-confirm" title="Eliminar perfil:">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>&iquest;Desea eliminar este perfil?</p>
      </div>
        <?php
      //listar
      $query = "SELECT *
      FROM  `perfil`";

      $result = mysql_query($query);
      $rows = mysql_num_rows($result);

  if ( !isset($rows) ) : 
    ?>
       <p>No hay registros en la base de datos</p>
    <?php 
  else : 
    ?>
    <br>
    <p>
      <a class="btn btn-success"href="nuevo.php">Nuevo perfil</a>
    </p>
    <div style="overflow-x: auto; overflow-y: hidden;">
      <br>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th scope="col" >nombre</th>
            <th scope="col">descripci&oacute;n</th>
            <th scope="col">acciones</th>
          </tr>
        </thead>
        <tbody>
      <?php
        for ($i = 0; $i < $rows; $i++)
            $perfiles[] = mysql_fetch_assoc($result);
      ?>
      <?php 
        for ($i = 0; $i < $rows; $i++): 
      ?>
            <tr>
              <td><?php echo $perfiles[$i]['nombre']; ?></td>
              <td><?php echo $perfiles[$i]['descripcion']; ?></td>
              <td>
                <div id="accion">
                  <ul>
                    <li id="accion">
                      <a href="editar.php?id=<?php echo $perfiles[$i]['idperfil']; ?>" >Editar</a>
                    </li>
                    <li id="accion">
                      <a id="opener" href="admin_perfiles.php?delete=<?php echo $perfiles[$i]['idperfil']; ?>"> Eliminar</a>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
      <?php 
        endfor; 
      ?>
          </tbody>
        </table>
    </div>
  <?php 
  endif;
  ?>
    </div><!-- content -->
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