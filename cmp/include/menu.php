<div id="header">

<h1><img src="http://cliente.local/cmp/images/lwis.celebrity/logo_cmp.png" style="max-width: 100px;vertical-align:top;" alt="CMP"> Control de &oacute;rdenes de trabajo - Compañ&iacute;a Minera del Pac&iacute;fico</h1>

<!--<img alt="portal de la subgerencia de calidad" class="banner" src="http://cliente.local/obras/img/bannerentel.png"></img>-->
<!-- Beginning of compulsory code below -->
<ul id="nav" class="dropdown dropdown-linear dropdown-columnar">
	<li><a href="http://cliente.local/cmp/index.php">Inicio</a></li>
	<?php  if(isset($_SESSION['usuario']) && (($_SESSION['usuario']['perfil']=='admin') || $_SESSION['usuario']['perfil']=='operadora' || $_SESSION['usuario']['perfil']=='evaluador_tecnico' || ($_SESSION['usuario']['perfil']=='admin_cmp')|| ($_SESSION['usuario']['perfil']=='admin_cmp2'))):?>
	<li class="dir">Gu&iacute;a CMP
		<ul>
			<li class="dir"><strong>Listar</strong>
				<ul>
					<li><a href="http://cliente.local/cmp/guia/listar_personas.php">Personas</a></li>
					<li><a href="http://cliente.local/cmp/guia/listar_lugaresycargos.php">Lugares y cargos</a></li>
					<?php  if(isset($_SESSION['usuario']) && (($_SESSION['usuario']['perfil']=='admin') || ($_SESSION['usuario']['perfil']=='evaluador_tecnico'))):?>			
					<li><a href="http://cliente.local/cmp/guia/listar_datostecnicos.php">Datos t&eacute;cnicos de anexos</a></li>
					<?php endif; ?>
				</ul>
			</li>
			<li class="dir"><strong>Buscar</strong>
				<ul>
					<li><a href="http://cliente.local/cmp/guia/buscar_personas.php">Personas</a></li>
					<li><a href="http://cliente.local/cmp/guia/buscar_lugaresycargos.php">Lugares y cargos</a></li>
					<?php  if(isset($_SESSION['usuario']) && (($_SESSION['usuario']['perfil']=='admin') || ($_SESSION['usuario']['perfil']=='evaluador_tecnico'))):?>			
					<li><a href="http://cliente.local/cmp/guia/buscar_datostecnicos.php">Datos t&eacute;cnicos de anexos</a></li>
					<?php endif; ?>
				</ul>
			</li>
		</ul>
	</li>
	<?php endif; ?>
	<?php  if(isset($_SESSION['usuario'])):?>
	<li class="dir">&Oacute;rdenes de trabajo
		<ul>
			<?php  if(isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil']=='admin') || $_SESSION['usuario']['perfil']=='operadora'):?>			
			<li class="dir"><strong>Acciones de operadora</strong>
				<ul>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/crear.php">Crear Orden</a></li>
				</ul>
			</li>
			<?php endif; ?>
			<?php  if(isset($_SESSION['usuario']) && (($_SESSION['usuario']['perfil']=='admin') || ($_SESSION['usuario']['perfil']=='admin_cmp') || ($_SESSION['usuario']['perfil']=='admin_cmp2'))):?>
			<li class="dir"><strong>Acciones de CMP</strong>
				<ul>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/crear.php">Crear Orden</a></li>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/cmp/revisar_generadas.php">Revisar OT generadas</a></li>
					<?php  if( isset($_SESSION['usuario']) && ( ($_SESSION['usuario']['perfil']=='admin') || ($_SESSION['usuario']['perfil']=='admin_cmp') ) ):?>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/cmp/revisar_evaluacionescomerciales.php">Revisar evaluaciones comerciales</a></li>
					<?php endif; ?>
				</ul>
			</li>
			<?php endif; ?>
			<?php  if(isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil']=='admin') || $_SESSION['usuario']['perfil']=='evaluador_tecnico' || $_SESSION['usuario']['perfil']=='evaluador_comercial'):?>
			<li class="dir"><strong>Acciones de Entel</strong>
				<ul>
					<?php  if(isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil']=='admin')) :?>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/entel/generar_evaluaciontecnica.php">Generar evaluaci&oacute;n t&eacute;cnica</a></li>
					<?php endif; ?>
					<?php  if(isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil']=='admin') || $_SESSION['usuario']['perfil']=='evaluador_tecnico'):?>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/entel/validar_evaluaciontecnica.php">Validar evaluaci&oacute;n t&eacute;cnica</a></li>
					<?php endif; ?>
					<?php  if(isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil']=='admin') || $_SESSION['usuario']['perfil']=='evaluador_comercial'):?>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/entel/evaluacion_comercial.php">Evaluaci&oacute;n comercial</a></li>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/entel/generar_ott.php">Generar OTT</a></li>
					<?php endif; ?>
					<?php  if(isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil']=='admin')) :?>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/entel/ingresar_ott.php">Ingresar n&uacute;mero de OTT</a></li>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/cambiarestado.php">Cambiar estado de Orden</a></li>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/entel/actualizar_ejecucion.php">Actualizar ejecuci&oacute;n</a></li>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/entel/cerrar_orden.php">Cerrar Orden</a></li>
					<?php endif; ?>
				</ul>
			</li>
			<?php endif; ?>
			<?php  if(isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil']=='admin') || ($_SESSION['usuario']['perfil']=='evaluador_tecnico') || ($_SESSION['usuario']['perfil']=='admin_cmp')):?>
			<li class="dir"><strong>B&uacute;squeda</strong>
				<ul>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/busqueda_numero.php">Por n&uacute;mero</a></li>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/busqueda_solicitante.php">Por solicitante</a></li>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/busqueda_faena.php">Por faena</a></li>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/busqueda_estado.php">Por estado</a></li>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/busqueda_fecha.php">Por fecha</a></li>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/busqueda_tipo_ot.php">Por tipo de OT</a></li>
				</ul>
			</li>
			<li class="dir"><strong>Reportes</strong>
				<ul>
					<li><a href="http://cliente.local/cmp/reportes/reporte_general.php">Reporte general</a></li>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/imprimir_orden_de_trabajo.php">Imprimir orden de trabajo</a></li>
				</ul>
			</li>
			<?php endif; ?>
		</ul>
	</li>
	<?php  endif;?><!-- usuario -->
	<?php  if(isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil']=='admin')):?>
	<li class="dir">Administrar
		<ul>
			<li class="dir"><strong>Permisos de acceso</strong>
				<ul>
					<li><a href="http://cliente.local/cmp/usuarios/admin_usuarios.php">Usuarios</a></li>
					<li><a href="http://cliente.local/cmp/perfiles/admin_perfiles.php">Perfiles</a></li>
				</ul>
			</li>
			<li class="dir"><strong>Gu&iacute;a CMP</strong>
				<ul>
					<li><a href="http://cliente.local/cmp/guia/admin_personas.php">Gu&iacute;a de personas</a></li>
					<li><a href="http://cliente.local/cmp/guia/admin_lugaresycargos.php">Gu&iacute;a de lugares y cargos</a></li>
					<li><a href="http://cliente.local/cmp/guia/admin_datostecnicos.php">Gu&iacute;a de datos t&eacute;cnicos</a></li>
					<li><a href="http://cliente.local/cmp/guia/admin_tiposdeanexo.php">Tipos de anexo</a></li>
				</ul>
			</li>
			<li class="dir"><strong>Ordenes de trabajo</strong>
				<ul>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/admin_ordenesdetrabajo.php">Ordenes de trabajo</a></li>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/admin_tipodeorden.php">Tipos de &oacute;rdenes de trabajo</a></li>
					<li><a href="http://cliente.local/cmp/ordendetrabajo/admin_faenas.php">Ciudades y faenas de las &oacute;rdenes de trabajo</a></li>
				</ul>
			</li>
		</ul>
	</li>
<?php  endif;?><!--admin -->
<?php if ( isset($_SESSION['usuario'])):?>
	<li><a href="http://cliente.local/cmp/include/logout.php">Cerrar sesi&oacute;n</a></li>
<?php endif;?>
</ul>
</div>