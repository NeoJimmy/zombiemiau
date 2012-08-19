<?php
  session_start();

  include("conect.php");


  // Si se reciben los datos, se procede a intentar el login
  if ( isset($_POST['usuario']) && isset($_POST['password']) )
  {
    // Sanitizar la entrada
    $usuario      = htmlspecialchars( trim($_POST['usuario']) );

    $password = htmlspecialchars( trim($_POST['password']) );

    // Construir sha2 del password
    $password=hash('sha256',$_POST['usuario'].$_POST['password']);

    // Conexión a la base de datos
    $db_connection = mysql_connect($config['db_server'], $config['db_user'], $config['db_password']);
    mysql_select_db($config['db_database']);
    mysql_query("SET NAMES 'utf8'");

    // Consulta. Debe retornar sólo (y solo) un resultado
    // $query  = "SELECT U.idUsuario, U.nombre, U.email, P.nombre as perfil FROM Usuario U, Perfil P WHERE U.Perfil_idPerfil = P.idPerfil";
    $query  = sprintf( "SELECT U.idUsuario, U.rut, U.nombre, U.email, P.nombre as perfil FROM usuario U, perfil P WHERE U.nombre = '%s' && U.contrasena = '%s' && U.perfil_idperfil = P.idperfil", mysql_real_escape_string($usuario), mysql_real_escape_string($password));
    $result = mysql_query($query);
    // Usuario encontrado
    if (mysql_num_rows($result) == 1)
    {
      // Se almacena en la sesión el id del usuario
      $_SESSION['usuario'] = mysql_fetch_assoc($result);
      
    }
    // No encontrado o error (no hay distinción para no dar pistas sobre los datos del usuario)
    else
    {
      //$_SESSION['error'] = "Su usuario y/o contraseña no coinciden. Por favor, intente de nuevo.".$query;
        $_SESSION['error'] = "Su usuario y/o contraseña no coinciden. Por favor, intente de nuevo.";
    }
  }

  // En cualquier caso, redirigir a la página de login
  header('Location: ../index.php');

?>