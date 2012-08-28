<?php session_start(); ?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
        <div  style="width: 440px;margin: 80px auto 100px;">
                
                <h2 style="text-align:center;">Sesi&oacute;n</h2>
                <?php
                if ( isset($_SESSION['error']) )
                {
                    echo '<p class=error>', $_SESSION['error'], '</p>';
                    unset($_SESSION['error']);
                }

                // Si hay sesiÃƒÂ³n y se estÃƒÂ¡ logueado...
                if ( isset($_SESSION['usuario']) ) :
                  // Mostrar enlace para log-out
                  echo '<h2 style="text-align: center;">', $_SESSION['usuario']['nombre'], '</h2> ';
                else:
                // Se muestra el formulario de login:
              ?>
                <form id="login" style="border:1px solid #CCC;padding: 20px 20px 10px;" action="../cmp/include/login.php" method="POST">
                    <p>
                     <label for="login_usuario">Usuario:</label>
                     <input name="usuario" id="login_usuario" type="text" value="">
                    </p>
                    <p>
                     <label for="login_password">Contrase&ntilde;a:</label>
                     <input name="password" id="login_password" type="password" value="">
                    </p>
                    <p class="espacio-submit"><input class="boton btn btn-primary" type="submit" value="Entrar"></p>
                </form>
               <?php endif; ?>
            
        </div>
    </div>
    <?php include ("include/footer.php") ?>
</div>

</body>
</html> 
