<?php 
	//include db config
	require_once 'db.php';

	// Init vars 
	$name = $email = $password = $confirm_password = "";
	$name_error = $email_error = $password_error = $confirm_password_error = "";

	//process form when form submitted
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		// sanitize post array
		$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		// Put post vars ar regular vars
		$name = trim($_POST['name']);
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);
		$confirm_password = trim($_POST['confirm_password']);

		// validate email
		if(empty($email)) {
			$email_error = 'Please Enter Your Email';
		} else { // check if email alreasy apc_exists
			// Prepare a select oci_statement
			$sql = 'SELECT id FROM users WHERE email = :email';
			if($stmt = $pdo->prepare($sql)) {
				// Bind Variables
				$stmt->bindParam(':email', $email, PDO::PARAM_STR);
				// aTTEMT TO EXICUTE
				if($stmt->execute()) {
					// echo $stmt->rowCount();
					// check if email exists(rowcount = 1)
					if($stmt->rowCount() === 1) {
						$email_error = 'Email is already taken';
					}
				} else {
					die('Something wrong');
				}
			}
			unset($stmt);	
		}
		
		// name Error
		if(empty($name)) {
			$name_error = 'Please Enter Your Name';
		}

		// pwd error
		if(empty($password)) {
			$password_error = 'Please Enter a Password';
		} elseif(strlen($password) < 6 ) {
			$password_error = 'Password must be atleast 6 charaters';
		}

		// Validate Confirm Password 
		if(empty($confirm_password)) {
			$confirm_password_error = "Please Confirm your Password";
		} elseif($password != $confirm_password) {
			$confirm_password_error = 'Password do not match';
		}

		// Make Sure errors are emty
		if(empty($name_error) && empty($email_error) && empty($password_error) && 	empty($confirm_password_error)) {
			$password = password_hash($password, PASSWORD_DEFAULT);
			// Prepare insert Query
			$sql = 'INSERT INTO users (name, email, password) VALUES(:name, :email, :password)';
			if($stmt = $pdo->prepare($sql)) {
				$stmt->bindParam(':name', $name, PDO::PARAM_STR);
				$stmt->bindParam(':email', $email, PDO::PARAM_STR);
				$stmt->bindParam(':password', $password, PDO::PARAM_STR);
				if($stmt->execute()) {
					// Redirect to login
					header('location: login.php');
				} else {
					die('Something Went Wrong');
				}
			}
			unset($stmt);
		}
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
					<h2>Create Account</h2>
					<p>Fill in this form to register</p>
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
						 <div class="form-group">
						 	<label>Name</label>
						 	<input type="text" name="name" class="form-control form-control-lg <?= (!(empty($name_error)) ? 'is-invalid' : '') ?>" value="<?= $name ?>">
						 	<span class="invalid-feedback"><?= $name_error ?></span>
						 </div>
						 <div class="form-group">
						 	<label>Email Address</label>
						 	<input type="email" name="email" class="form-control form-control-lg <?= (!empty($email_error) ? 'is-invalid' : '') ?>" value="<?= $email ?>">
						 	<span class="invalid-feedback"> <?= $email_error ?> </span>
						 </div>
						 <div class="form-group">
						 	<label>Password</label>
						 	<input type="password" name="password" class="form-control form-control-lg <?= (!empty($password_error) ? 'is-invalid' : '') ?>" value="<?= $password ?>">
						 	<span class="invalid-feedback"><?= $password_error ?></span>
						 </div>
						 <div class="form-group">
						 	<label>Confirm Password</label>
						 	<input type="password" name="confirm_password" class="form-control form-control-lg <?= (!empty($confirm_password_error) ? 'is-invalid' : '') ?>" value="">
						 	<span class="invalid-feedback"><?= $confirm_password_error ?></span>
						 </div>
						 <div class="form-row">
							<div class="col">
								<input type="submit" value="Register" class="btn btn-success btn-block">
							</div>
							<div class="col">
								<a href="login.php" class="btn btn-light btn-block">Have an account? Login</a>
							</div>
						 </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>