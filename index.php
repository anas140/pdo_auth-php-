<?php
	session_start();
	if(empty($_SESSION['email']) || !isset($_SESSION['name'])) {
		header('location: login.php');
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Php PDO Authentication</title>
	<!-- Bootstap Css CDN -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body class="bg-primary">
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="card card-body bg-light mt-5">
					<h2>Dashboard <small class="text-muted"><?= $_SESSION['email'] ?></small> </h2>
					<p>Welcome to the Dashboard <?= $_SESSION['name'] ?></p>
					<p><a href="logout.php" class="btn btn-danger">Logout</a></p>
				</div>
			</div>
		</div>
	</div>
</body>
</html>