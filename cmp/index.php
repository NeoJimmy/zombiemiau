<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <!-- cabecera estandar-->
   <?php include ("include/head.php"); ?>

</head>
<body>
<div id="container">

    <div id="banner"> </div>
    <!-- div menu principal-->
    <?php include ("include/menu.php"); ?>


    <div id="content">
        <div id="main">
            <h2></h2>
            <br><br><br><br><br><br>
        </div>
        <br><br>
        <div id="sidebar">
                
                <h2>Sesi&oacute;n</h2>
                <?php
                if ( isset($_SESSION['error']) )
                {
                    echo '<p class=error>', $_SESSION['error'], '</p>';
                    unset($_SESSION['error']);
                }

                // Si hay sesiÃƒÂ³n y se estÃƒÂ¡ logueado...
                if ( isset($_SESSION['usuario']) ) :
                  // Mostrar enlace para log-out
                  echo '<h2>', $_SESSION['usuario']['nombre'], '</h2> ';
                else:
                // Se muestra el formulario de login:
              ?>
                <form id="login" action="../cmp/include/login.php" method="POST">
                    <p>
                     <label for="login_usuario">Usuario:</label>
                     <input name="usuario" id="login_usuario" type="text" value="">
                    </p>
                    <p>
                     <label for="login_password">Contrase&ntilde;a:</label>
                     <input name="password" id="login_password" type="password" value="">
                    </p>
                    <p><input class="boton" type="submit" value="Entrar"></p>
                </form>
               <?php endif; ?>
            
        </div>
    </div>
    <?php include ("include/footer.php") ?>
</div>

</body>
</html> 
