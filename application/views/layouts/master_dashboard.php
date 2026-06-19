<!doctype html>
<?php
if (!function_exists('html_escape')) {
	function html_escape($value)
	{
		return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo isset($title) ? html_escape($title) . ' - Bangladesh Secretariat' : 'Visitor Pass - Bangladesh Secretariat'; ?></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/app.css'); ?>">

	<!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/axios@1.6.8/dist/axios.min.js"></script> -->

	<script src="<?php echo base_url('assets/js/vue/vue.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/vue/axios.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/vue/moment.min.js') ?>"></script>

	<link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/images/Bangladesh_Secretariat.png">
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-navy app-navbar">
		<a class="navbar-brand app-brand" href="<?php echo site_url(); ?>">
			<img src="<?php echo base_url(); ?>assets/images/Bangladesh_Secretariat.png" style="height: 30px;" alt="Bangladesh Secretariat">
			<span>Bangladesh Secretariat</span>
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="mainNav">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('/'); ?>">Apply</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('visitor/status'); ?>">Status</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('gate/scanner'); ?>">Gate</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo site_url('admin/dashboard'); ?>">Admin</a></li>
				<?php if ($this->session->userdata('user_id')): ?>
					<li class="nav-item"><a class="nav-link" href="<?php echo site_url('auth/logout'); ?>">Logout</a></li>
				<?php endif; ?>
			</ul>
		</div>
	</nav>
	<main class="page-shell">
		<?php echo $content; ?>
	</main>
	<footer class="fat-footer" role="contentinfo">
		<div class="fat-footer__bottom">
			<p class="fat-footer__copyright">&copy; <?= date('Y') ?> Bangladesh Secretariat. All Rights Reserved.</p>
			<nav class="fat-footer__social" aria-label="Social Media">
				<p class="fat-footer__copyright">Design & Developed By: Lalon Hossain (25208) & Rakib Hossain (25202)</p>
			</nav>
		</div>
	</footer>

	<script src="<?php echo base_url(); ?>assets/js/jquery.slim.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>