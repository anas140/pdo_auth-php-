<?php 
	//include db config
	require_once 'db.php';

	// Init vars 
	$email = $password = "";
	$email_error = $password_error = "";

	//process form when form submitted
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		// sanitize post array
		$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		// Put post vars ar regular vars
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);

		// validate email
		if(empty($email)) {
			$email_error = 'Please Enter Your Email';
		}
		
		// pwd error
		if(empty($password)) {
			$password_error = 'Please Enter a Password';
		} 

		// Check validation_errors are empty
		if(empty($email_error) && empty($password_error)) {
			// prepare query
			$sql = 'SELECT name, email, password from users where email = :email';
			if($stmt = $pdo->prepare($sql)) {
				$stmt->bindParam(':email', $email, PDO::PARAM_STR);
				// Attempt to exicute
				if($stmt->execute()) {
					if($stmt->rowCount() == 1) {
						if($row = $stmt->fetch()) {
							$hashed_password = $row['password'];
							if(password_verify($password, $hashed_password)) {
								// Success Login
								session_start();
								$_SESSION['email'] = $email;
								$_SESSION['name'] = $row['name'];
								header('location: index.php');
							} else {
								// Display wrong password msg
								$password_error = 'The password you entereS is not valid';
							}
						}
					} else {
						$email_error = "No Account found fo that email";
					}
				} else {
					die('something went wrong');
				}
			}
			unset($stmt);
		}
		// Close Connection
		unset($pdo);
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
			<div class="col-md-6 mx-auto">
				<div class="card card-body bg-light mt-5">
					<h2>Login to your Accoun</h2>
					<p>Fill your credentials</p>
					<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
						 <div class="form-group">
						 	<label>Email Address</label>
						 	<input type="email" name="email" class="form-control form-control-lg <?= (!empty($email_error) ? 'is-invalid' : '') ?>" value="<?= $email ?>" value="">
						 	<span class="invalid-feedback"><?= $email_error ?></span>
						 </div>
						 <div class="form-group">
						 	<label>Password</label>
						 	<input type="password" name="password" class="form-control form-control-lg <?= (!empty($password_error) ? 'is-invalid' : '') ?>" value="<?= $password ?>" value="">
						 	<span class="invalid-feedback"><?= $password_error ?></span>
						 </div>
						 <div class="form-row">
						<div class="col">
							<input type="submit" value="Login" class="btn btn-success btn-block">
						</div>
						<div class="col">
							<a href="register.php" class="btn btn-light btn-block">No Account? Register</a>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>