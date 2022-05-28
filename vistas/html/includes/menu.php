<!-- Top Bar Start -->
<div class="topbar">
	<!-- LOGO -->
	<div class="topbar-left">
		<div class="text-center">
			<a href="#" class="logo"><i class="mdi mdi-radar"></i> <span>ENJOI</span></a>
		</div>
	</div>

	<!-- Button mobile view to collapse sidebar menu -->
	<nav class="navbar-custom">

		<ul class="list-inline float-right mb-0">
			<li class="list-inline-item notification-list hide-phone">
				<a class="nav-link waves-light waves-effect" href="#" id="btn-fullscreen">
					<i class="mdi mdi-crop-free noti-icon"></i>
				</a>
			</li>

			<li class="list-inline-item dropdown notification-list">
				<a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
				aria-haspopup="false" aria-expanded="false">
					<img src="../../assets/images/users/user.png" alt="user" class="rounded-circle">
				</a>
				<div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">

					<!-- item-->
					<a href="javascript:void(0);" class="dropdown-item notify-item">
						<i class="mdi mdi-account-star-variant"></i> <span>Perfil</span>
					</a>

					<!-- item-->
					<a href="../../login.php?logout" class="dropdown-item notify-item">
						<i class="mdi mdi-logout"></i> <span>Salir</span>
					</a>

				</div>
			</li>

		</ul>

		<ul class="list-inline menu-left mb-0">
			<li class="float-left">
				<button class="button-menu-mobile open-left waves-light waves-effect">
					<i class="mdi mdi-menu"></i>
				</button>
			</li>
		</ul>

	</nav>

</div>
<!-- Top Bar End -->
<!-- ========== Left Sidebar Start ========== -->

<div class="left side-menu">
	<div class="sidebar-inner slimscrollleft">
		<!--- Divider -->
		<div id="sidebar-menu">
			<ul>
				<li class="menu-title">Menú</li>

				<li>
					<a href="principal.php" class="waves-effect waves-primary"><i
						class="ti-home"></i><span> Inicio </span></a>
				</li>
				<li>
					<a href="../html/clientes.php" class="waves-effect waves-primary"><i class="ti-user"></i><span> Clientes </span></a>
				</li>
				<!-- <li>
					<a href="../html/proveedores.php" class="waves-effect waves-primary"><i class="ti-truck"></i><span> Proveedores </span></a>
				</li> -->
				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect waves-primary"><i class="ti-package"></i><span> Productos  </span>
					<span class="menu-arrow"></span></a>
					<ul class="list-unstyled">
						<!-- <li><a href="../html/lineas.php">Categorias</a></li> -->
						<li><a href="../html/productos.php">Productos</a></li>
						<li><a href="../html/lineas.php">Categorias</a></li>
						<li><a href="../html/proveedores.php">Proveedores</a></li>
						<li><a href="../html/control_inventario.php">Control de Inventario</a></li>
						<li><a href="../html/ajuste_inventario.php">Ajuste de Inventario</a></li>
					</ul>
				</li>

				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect waves-primary"><i class="ti-panel"></i><span> Servicios  </span>
					<span class="menu-arrow"></span></a>
					<ul class="list-unstyled">
						<li><a href="../html/servicios.php">Servicios</a></li>
						<li><a href="../html/objetos_servicios.php">Objetos</a></li>
						<li><a href="../html/estados_servicios.php">Estados de servicio</a></li>
						<li><a href="../html/grupos_servicios.php">Grupos de servicio</a></li>
					</ul>
				</li>

				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect waves-primary"><i class="ti-bag"></i><span> Compras </span> <span class="menu-arrow"></span></a>
					<ul class="list-unstyled">
						<li><a href="../html/new_compra.php">Nueva Compra</a></li>
						<li><a href="../html/bitacora_compras.php">Lista de Compras</a></li>
					</ul>
				</li>

				<li>
					<a href="../html/gastos.php" class="waves-effect waves-primary"><i class="ti-bar-chart"></i><span> Gastos </span></a>
				</li>

				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect waves-primary"><i class="ti-shopping-cart-full"></i><span> Ventas
					</span> <span class="menu-arrow"></span></a>
					<ul class="list-unstyled">
						<li><a href="../html/new_venta.php">Nueva Venta</a></li>
						<li><a href="../html/bitacora_ventas.php">Lista de Ventas</a></li>
						<!--<li><a href="../html/caja.php">Caja</a></li>-->
					</ul>
				</li>
				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect waves-primary"><i class="ti-receipt"></i><span> Presupuesto
					</span> <span class="menu-arrow"></span></a>
					<ul class="list-unstyled">
						<li><a href="../html/new_presupuesto.php">Nuevo Presupuesto</a></li>
						<li><a href="../html/bitacora_presupuesto.php">Lista de Presupuestos</a></li>
					</ul>
				</li>
				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect waves-primary"><i class="ti-agenda"></i><span> Créditos </span>
					<span class="menu-arrow"></span></a>
					<ul class="list-unstyled">
						<li><a href="../html/cxc.php">Cuenta Clientes</a></li>
						<li><a href="../html/cxp.php">Pago Proveedor</a></li>
					</ul>
				</li>

				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect waves-primary"><i class="ti-files"></i><span> Reportes </span> <span class="menu-arrow"></span></a>
					<ul class="list-unstyled">
						<!--<li><a href="../html/rep_producto.php">Reporte Productos</a></li>-->
						<li><a href="../html/rep_ventas.php">Reporte de Ventas</a></li>
						<!--<li><a href="../html/rep_ventas_users.php">Ventas por Usuarios</a></li>-->
						<li><a href="../html/rep_compras.php">Reporte de Compras</a></li>
						<li><a href="../html/rep_caja_chica.php">Reporte Caja chica</a></li>
						<li><a href="../html/rep_caja_general.php">Arqueo de Caja</a></li>
						<li><a href="../html/rep_financiero.php">Reporte Financiero</a></li>
						<li><a href="../html/rep_gastos.php">Reporte Gastos</a></li>
					</ul>
				</li>
				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect waves-primary"><i class="ti-wallet"></i><span> Finanzas </span> <span class="menu-arrow"></a>
					<ul class="list-unstyled">
						<li><a href="../html/finanzas_compras.php">Compras</a></li>
						<li><a href="../html/finanzas_ventas.php">Ventas</a></li>
						<li><a href="../html/finanzas_gastos.php">Gastos</a></li>
					</ul>
				</li>
				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect waves-primary"><i class="ti-settings"></i><span> Configuración </span> <span class="menu-arrow"></span></a>
					<ul class="list-unstyled">
						<li><a href="../html/empresa.php">Empresa</a></li>
						<li><a href="../html/sucursales.php">Sucursales</a></li>
						<li><a href="../html/comprobantes.php">Comprobantes</a></li>
						<!--<li><a href="../html/impuestos.php">Impuestos</a></li>-->
						<li><a href="../html/grupos.php">Grupos de Usuarios</a></li>
						<li><a href="../html/usuarios.php">Usuario</a></li>
						<li><a href="../html/backup.php">Backup</a></li>
						<li><a href="../html/restore.php">Restore</a></li>
					</ul>
				</li>

			</ul>

			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<!-- Left Sidebar End -->
