<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- NUEVO PERFIL -->
<html>
<head>
    <!--cabecera estandar-->
    <?php include ("../include/head.php")?>

    <!-- validamos el formulario -->
    <script type="text/javascript">
    $(document).ready(function(){
    $("#myform").validate();
    });
    </script>
</head>
<body>

<?php
//Si existe una sesion abierta del admin, muestro el contenido
if ( isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil'] == 'admin')) :?>

<div id="container">

<!-- div menu -->
<?php include ("../include/menu.php"); ?>

<div id="content">

    <?
    include('../include/conect.php');

    $db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
    mysql_select_db($config['db_database']);
    mysql_query("SET NAMES 'utf8'");

    if (isset($_POST['submitted'])) {
    foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }

    //Agregamos uno nuevo
    $sql= "INSERT INTO `perfil`
    (`idperfil`, `nombre`, `descripcion`)
    VALUES (NULL, '{$_POST['nombre']}', '{$_POST['descripcion']}');";


    $result = mysql_query($sql);
    if (! $result){
                    echo "La Inserción contiene errores. <br /> Datos mal ingresados";
                    echo "<a href='admin_perfiles.php' title='Administrar Perfiles'>volver a Perfiles</a><br />";
                    exit();
                  }

    echo "Fila Agregada.<br />";
    echo "<a href='admin_perfiles.php' title='Administrar Perfiles'>volver a Perfiles</a><br />";
    }

    ?>

    <h2>Nuevo perfil</h2>
    <form id="myform"  action='' method='POST'>
    <p><b>Nombre del perfil:</b><br /><input type='text' name='nombre' class='required'/>
    <p><b>Descripci&oacute;n:</b><br /><input  type='text' name='descripcion' class='required' />
    <p>
    <input class='submit btn btn-primary' type='submit' value='Agregar perfil' /><input type='hidden' value='1' name='submitted' /></p>
    </form>

</div>

<?php
//se acabo la sesion           
else :                         
?>
<br /><center><h3>Tu sesi&oacute;n a expirado...</h3><br /><br />
<p><a href='../../obras/index.php'>volver a Inicio</a></p></center>
<?php 
endif; 
?>

<!-- div footer-->
<?php include ("../include/footer.php") ?>

</div>

</body>
</html>
