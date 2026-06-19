<!doctype html>
<html lang="en">
<?php $companyInfo = $this->db->query("select * from tbl_company c order by c.Company_SlNo desc limit 1")->row(); ?>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $companyInfo->Company_Name; ?> - Shop. Smile. Repeat. </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="/assets/login/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/login/css/login-font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/login/css/style.css">

	<link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/sopnojhuri_mobile_logo.png">
</head>
<style>
	body,
	html {
		height: 100%;
		margin: 0;
	}

	.ftco-section {
		background-image: url("/assets/login/img/13237930.jpg");
		height: 100%;
		background-position: center;
		background-repeat: no-repeat;
		background-size: cover;
	}
</style>

<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
						<div class="icon d-flex align-items-center justify-content-center">
							<img src="<?php echo base_url(); ?>assets/sopnojhuri_mobile_logo.png" alt="SopnoJhuri"
								style="height: 75px;width: 75px;">
						</div>
						<h3 class="text-center mb-4">Welcome Sir,</h3>

						<form class="login-form" method="post" action="<?php echo base_url(); ?>Login/procedure">
							<div class="form-group">
								<?php echo form_error('user_name'); ?>
								<input type="text" name="user_name" class="form-control rounded-left text-center"
									placeholder="User Name" required />
							</div>
							<div class="form-group d-flex">
								<?php echo form_error('password'); ?>
								<input type="password" name="password" class="form-control rounded-left text-center"
									placeholder="Password" required>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary rounded submit p-3 px-5">Get
									Started</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="/assets/login/js/jquery.min.js"></script>
	<script src="/assets/login/js/popper.js"></script>
	<script src="/assets/login/js/bootstrap.min.js"></script>
	<script src="/assets/login/js/main.js"></script>
</body>

</html>