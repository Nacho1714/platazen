<?php

require_once __DIR__ . '/../bootstrap/autoload.php';

// Capturo la variable pasada por GET
// isset() es una función que verifica si una variable está definida (true) o no (false)
// $vista = isset($_GET['s']) ? $_GET['s'] : 'home'; // Con operador ternario
$vista = $_GET['s'] ?? 'dashboard'; // Con operador de fusión de null (null coalesce). Si $_GET['s'] es "null" entonces $vista = 'home'

// Defino una "whitelist" de vistas válidas que permito que se carguen
$rutas = [
	'log-in' => [
		'title' => 'Ingresar al Panel Administrador',
		'requiresAuth' => false,
		'scripts' => [
			'js/password-eye.js'
		],
	],
	'password-recovery' => [
		'title' => 'Restablecer Contraseña',
		'requiresAuth' => false,
	],
	'password-update' => [
		'title' => 'Actualizar Contraseña',
		'requiresAuth' => false,
		'scripts' => [
			'js/password-eye.js'
		],
	],
	'404' => [
		'title' => 'Página no Encontrada',
		'requiresAuth' => false,
	],
	'dashboard' => [
		'title' => 'Dashboard',
		'requiresAuth' => true,
	],
	'create-product' => [
		'title' => 'Agregar Nuevo Producto',
		'requiresAuth' => true,
	],
	'edit-product' => [
		'title' => 'Editar Producto',
		'requiresAuth' => true,
	],
];

// Verifico que la vista solicitada esté dentro de la "whitelist"
if (!isset($rutas[$vista])) {
	$vista = '404';
};

// Guardo en una variable los datos de la vista solicitada para facilitar su uso
$rutaConfig = $rutas[$vista];

// flie_exists() es una función que verifica si un archivo/directorio existe (true) o no (false)
$filename = __DIR__ . '/vistas/' . $vista . '.php';
if (!file_exists($filename)) {
	$filename = __DIR__ . '/vistas/404.php';
}

// Autenticación
$authManager = new \App\Auth\AuthManager;
$requiresAuth = $rutaConfig['requiresAuth'] ?? false;

if ($requiresAuth && !$authManager->checkAuth()) {
	$_SESSION['errors'] = 'Se requiere iniciar sesión para acceder a esta página';
	header('Location: index.php?s=log-in');
	exit;
}

// Mensajes de éxito y error
$successMessage = $_SESSION['success'] ?? null;
$errorsMessage = $_SESSION['errors'] ?? null;

unset($_SESSION['success'], $_SESSION['errors']);

?>



<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<title><?= $rutaConfig['title'] ?? '' ?> :: Panel de Administración</title>

	<!-- CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
	<link rel="stylesheet" href="../css/admin.css">

	<!--google fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

	<!--google material icon-->
	<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">

</head>

<body>

	<?php
		if ($authManager->checkAuth()) :
	?>

	<div class="wrapper">

		<div class="body-overlay"></div>

		<!-------sidebar--design------------>
		<div id="sidebar">

			<!------accessibility-menu-start----------->
			<nav class="navbar navbar-expand-md navbar-light bg-light visually-hidden-focusable" id="accessibility-menu">
				<div class="container">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#accessibilityMenu">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="accessibilityMenu">
						<ul class="navbar-nav">
							<li class="nav-item visually-hidden-focusable">
								<a class="nav-link" href="#main-content">Contenido Principal</a>
							</li>
							<li class="nav-item visually-hidden-focusable">
								<a class="nav-link" href="#sidebar-menu">Barra Lateral</a>
							</li>
							<li class="nav-item visually-hidden-focusable">
								<a class="nav-link" href="#user">Usuario</a>
							</li>
							<li class="nav-item visually-hidden-focusable">
								<a class="nav-link" href="#footer">Pie de Página</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
			<!------accessibility-menu-end----------->

			<div class="sidebar-header">
				<h3><img src="../images/admin-img/logo.png" class="img-fluid" /><span>vishweb design</span></h3>
			</div>
			<ul class="list-unstyled component m-0" id="sidebar-menu">
				<li class="active">
					<a href="index.php?s=dashboard" class="dashboard"><i class="material-icons">dashboard</i>dashboard </a>
				</li>

				<li class="dropdown">
					<a href="#collapseExample" class="dropdown-toggle" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseExample">
						<i class="material-icons">aspect_ratio</i>Layouts
					</a>
					<ul class="collapse" id="collapseExample">
						<li><a href="#">layout 1</a></li>
						<li><a href="#">layout 2</a></li>
						<li><a href="#">layout 3</a></li>
					</ul>
				</li>

				<li class="dropdown">
					<a href="#homeSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
						<i class="material-icons">apps</i>widgets
					</a>
					<ul class="collapse list-unstyled menu" id="homeSubmenu2">
						<li><a href="#">Apps 1</a></li>
						<li><a href="#">Apps 2</a></li>
						<li><a href="#">Apps 3</a></li>
					</ul>
				</li>

				<li class="dropdown">
					<a href="#homeSubmenu3" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
						<i class="material-icons">equalizer</i>charts
					</a>
					<ul class="collapse list-unstyled menu" id="homeSubmenu3">
						<li><a href="#">Pages 1</a></li>
						<li><a href="#">Pages 2</a></li>
						<li><a href="#">Pages 3</a></li>
					</ul>
				</li>


				<li class="dropdown">
					<a href="#homeSubmenu4" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
						<i class="material-icons">extension</i>UI Element
					</a>
					<ul class="collapse list-unstyled menu" id="homeSubmenu4">
						<li><a href="#">Pages 1</a></li>
						<li><a href="#">Pages 2</a></li>
						<li><a href="#">Pages 3</a></li>
					</ul>
				</li>

				<li class="dropdown">
					<a href="#homeSubmenu5" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
						<i class="material-icons">border_color</i>forms
					</a>
					<ul class="collapse list-unstyled menu" id="homeSubmenu5">
						<li><a href="#">Pages 1</a></li>
						<li><a href="#">Pages 2</a></li>
						<li><a href="#">Pages 3</a></li>
					</ul>
				</li>

				<li class="dropdown">
					<a href="#homeSubmenu6" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
						<i class="material-icons">grid_on</i>tables
					</a>
					<ul class="collapse list-unstyled menu" id="homeSubmenu6">
						<li><a href="#">table 1</a></li>
						<li><a href="#">table 2</a></li>
						<li><a href="#">table 3</a></li>
					</ul>
				</li>


				<li class="dropdown">
					<a href="#homeSubmenu7" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
						<i class="material-icons">content_copy</i>Pages
					</a>
					<ul class="collapse list-unstyled menu" id="homeSubmenu7">
						<li><a href="#">Pages 1</a></li>
						<li><a href="#">Pages 2</a></li>
						<li><a href="#">Pages 3</a></li>
					</ul>
				</li>


				<li class="">
					<a href="#" class=""><i class="material-icons">date_range</i>copy </a>
				</li>
				<li class="">
					<a href="#" class=""><i class="material-icons">library_books</i>calender </a>
				</li>

			</ul>

		</div>
		<!-------sidebar--design- close----------->

		<!-------page-content start----------->
		<div id="content">

			<!------top-navbar-start----------->
			<nav class="top-navbar">
				<div class="xd-topbar">
					<div class="row justify-content-between">
						<div class="col-md-5 col-lg-3 order-3 order-md-2">
							<div class="xp-searchbar">
								<form>
									<div class="input-group">
										<input type="search" class="form-control" placeholder="Search">
										<div class="d-flex">
											<button class="btn" type="submit" id="button-addon2">Go
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>


						<div class="col-12 col-md-6 col-lg-8 order-1 order-md-3">
							<div class="xp-profilebar text-right">
								<nav class="navbar p-0 justify-content-end">
									<ul class="nav navbar-nav flex-row ml-auto">

										<li class="d-flex flex-row dropdown nav-item">
											<p><?= $authManager->getUserByAuth()->getEmail() ?></p>
											<a href="#collapseProfile" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseProfile" id="user">
												<img src="../images/admin-img/user.jpg" style="width:40px; border-radius:50%;" />
												<span class="xp-user-live"></span>
											</a>
											<!-- class="dropdown-menu small-menu" -->
											<ul class="collapse dropdown-menu small-menu" id="collapseProfile">
												<li>
													<a href="#">
														<span class="material-icons">person_outline</span>
														Profile
													</a>
												</li>
												<li>
													<a href="#">
														<span class="material-icons">settings</span>
														Settings
													</a>
												</li>
												<li>
													<a href="actions/auth/log-out.php">
														<span class="material-icons">logout</span>
														Logout
													</a>
												</li>

											</ul>
										</li>


									</ul>
								</nav>
							</div>
						</div>

					</div>

					<div class="xp-breadcrumbbar text-center">
						<h4 class="page-title">Dashboard</h4>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#">Vishweb</a></li>
							<li class="breadcrumb-item active" aria-curent="page">Dashboard</li>
						</ol>
					</div>


				</div>
			</nav>
			<!------top-navbar-end----------->

	<?php
		endif;
	?>

			<!------main-content-start----------->
			<main id="main-content">

				<?php
				if ($successMessage) :
				?>

					<div class="toast-container position-fixed bottom-0 end-0 p-3">
						<div id="liveToast" class="toast text-bg-success" role="alert" aria-live="assertive" aria-atomic="true">
							<div class="toast-header">
								<i class="material-icons me-1">check_circle</i>
								<strong class="me-auto">¡Atenión!</strong>
								<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
							</div>
							<div class="toast-body text-bg-success fw-bold">
								<?= $successMessage?>
							</div>
						</div>
					</div>

				<?php
				endif;
				?>

				<?php
				if ($errorsMessage) :
				?>

					<div class="toast-container position-fixed bottom-0 end-0 p-3">
						<div id="liveToast" class="toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
							<div class="toast-header">
							<i class="material-icons me-1">dangerous</i>
								<strong class="me-auto">¡Atenión!</strong>
								<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
							</div>
							<div class="toast-body text-bg-danger fw-bold">
								<?= $errorsMessage?>
							</div>
						</div>
					</div>

				<?php
				endif;
				?>

				<?php
				require $filename;
				?>

			</main>
			<!------main-content-end----------->

	<?php
		if ($authManager->checkAuth()) :
	?>

			<!----footer-design------------->
			<footer class="footer" id="footer">
				<div class="container-fluid">
					<div class="footer-in">
						<p class="mb-0">&copy 2021 Vishweb Design . All Rights Reserved.</p>
					</div>
				</div>
			</footer>

		</div>

	</div>
	<!-------complete html----------->

	<?php
		endif;
	?>

	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<!-- <script src="js/jquery-3.3.1.slim.min.js"></script> -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			window.onload = (event) => {
				let myAlert = document.querySelector('.toast')
				let bsAlert = new bootstrap.Toast(myAlert)
				bsAlert.show()
			}
		})
	</script>
	
	<?php
		foreach (($rutaConfig['scripts'] ?? []) as $script) :
	?>
		<script src="<?= "../" . $script ?>"></script>
	<?php
		endforeach;
	?>

</body>

</html>