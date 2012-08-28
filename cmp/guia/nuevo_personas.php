<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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

    if (isset($_POST['submitted'])) {
    foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }
	
    //Agregamos el elemento
    $sql= "INSERT INTO `guia_personas`
    (`idguia_personas`, `anexo`, `nombre`,  `apellido`, `unidad`, `localidad`, `centro_de_costo` )
    VALUES (NULL, '{$_POST['anexo']}', '{$_POST['nombre']}', '{$_POST['apellido']}', '{$_POST['unidad']}', '{$_POST['localidad']}', '{$_POST['centro_de_costo']}');";

    //echo $sql.'<br>';

    $result = mysql_query($sql);
    if (!$result){
                    echo "La Inserción contiene errores. <br> Datos mal ingresados";
                    ?>
                    <br>
                    <a href="admin_personas.php">Volver a Agenda</a><br><br>
                    <?php
                    exit();
                  }

    echo "Fila Agregada.<br>";
    echo "<a href='admin_personas.php'>Volver a Agenda</a><br><br>";
    }

    ?>

    <a class="btn" href="admin_personas.php">volver</a>

    <h2>Nuevo registro</h2>

    <form id="myform"  action='' method='POST'>
    <fieldset>
    <legend>Datos de la gu&iacute;a telef&oacute;nica</legend>
    <p><b>anexo:</b><br><input type='text' name='anexo' class='required'></input>
    <p><b>nombre:</b><br><input type='text' name='nombre' class='required'></input>
    <p><b>apellido:</b><br><input type='text' name='apellido' class='required '></input>
    <p><b>unidad:</b><br><input type='text' name='unidad' class='required'></input>
    <p><b>localidad:</b><br><input type='text' name='localidad' class='required'></input>
    <p><b>centro de costo:</b><br><input type='text' name='centro_de_costo' class='required'></input>
    </fieldset>
    <p><input class='submit btn btn-primary' type='submit' value='Agregar Registro'></input><input type='hidden' value='1' name='submitted'></input>
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
