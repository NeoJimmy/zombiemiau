<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- NUEVO USUARIO -->
<html>
<head>
    <!--cabecera estandar-->
    <?php include ("../include/head.php")?>
    <script type="text/javascript">
    //validar rut
    jQuery.validator.addMethod("rut", function(value, element) {
                return this.optional(element) || /^\d{1,2}\d{3}\d{3}[-][0-9kK]{1}$/.test(value);
	}, "Por favor ingrese un rut valido."
    );
    </script>

    <!-- validamos el formulario -->
    <script type="text/javascript">
    $(document).ready(function(){
    $("#myform").validate();
    });
    </script>
</head>
<body>

<div id="container">

<!-- div menu -->
<?php include ("../include/menu.php"); ?>


<?php
//Si existe una sesion abierta del admin, muestro el contenido
if ( isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil'] == 'admin')) :?>

<div id="content">

    <?
    include('../include/conect.php');

    $db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
    mysql_select_db($config['db_database']);
    mysql_query("SET NAMES 'utf8'");

    if (isset($_POST['submitted'])) {
    foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); }

    //echo $_POST['nombre_us'].$_POST['contrasena']."<br />";


    $contrasena=hash('sha256', $_POST['nombre_us'].$_POST['contrasena']);
    //Agregamos uno nuevo
    $sql= "INSERT INTO `usuario`
    (`idUsuario` , `perfil_idperfil` , `rut`, `nombre`, `contrasena`, `email`)
    VALUES (NULL, '{$_POST['perfil']}', '{$_POST['rut']}', '{$_POST['nombre_us']}', '$contrasena', '{$_POST['email']}');";

    //echo $sql;

    $result = mysql_query($sql);
    if (! $result){
                    echo "La Inserción contiene errores. <br /> Datos mal ingresados";
                    echo "<a href='admin_usuarios.php' title='Administrar usuarios'>volver a usuarios</a><br />";
                    exit();
                  }

    echo "Fila Agregada.<br />";
    echo "<a href='admin_usuarios.php' title='Administrar usuarios'>volver a usuarios</a><br />";
    }

    ?>

    <form id="myform"  action='' method='POST'>
    <br>
    <p><b>rut:</b><br><input type='text' name='rut' class='required rut'>
    <p><b>nombre:</b><br><input type='text' name='nombre_us' class='required'>
    <p><b>contrase&ntilde;a:</b><br><input type='text' name='contrasena' class='required'>
    <p><b>email:</b><br><input type='text' name='email' class='required email'>
    <p><b>perfil:</b><br>
    <select name="perfil">
    <?php
        $query="SELECT idperfil, nombre FROM perfil ORDER BY nombre";
        $result = mysql_query($query) or die(mysql_error());
        if ($result)
        while($renglon = mysql_fetch_array($result))
        {
            $valor=$renglon['idperfil'];
            $nombre=$renglon['nombre'];
                echo "<option value=".$valor.">".$nombre."</option>\n";
        }
    ?>
    </select>
    <p>
    <input class='submit' style="position: absolute; left: -103px;" type='submit' value='Agregar usuario'></input><input type='hidden' value='1' name='submitted'></input></p>
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
