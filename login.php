<?php
session_start();
// If the user is  logged in redirect to the home page...
if (isset($_SESSION['loggedin'])) {
	header('Location: home.php');
	exit;
}
$_SESSION['error']= "";

// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'mytutors';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	$_SESSION['error'] = 'Failed to connect to MySQL : ' . mysqli_connect_error();
}
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (!isset( $_POST['Username'],$_POST['Password']))  {
	// Could not get the data that should have been sent.
	$_SESSION['error'] = "Username and/or Password required ! ";
}
else
{
	// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
	if ($stmt = $con->prepare('SELECT Username, Password ,Position FROM users WHERE Username = ?')) {
		// Bind parameters (s = string, i = int, b = blob, etc), in our case the Username is a string so we use "s"
		$stmt->bind_param('s', $_POST['Username']);
		$stmt->execute();
		// Store the result so we can check if the account exists in the database.
		$stmt->store_result();
		
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($Username, $Password,$Position);
			$stmt->fetch();
			if ($_POST['Password'] === $Password) {
				session_regenerate_id();
				$_SESSION['loggedin'] = TRUE;
				$_SESSION['Username'] = $_POST['Username'];
				$_SESSION['Position'] = $Position;
				header('Location: home.php');
			} else {
				$_SESSION['error'] = "Incorrect Username and/or Password  ! ";
			}
		} else {
			$_SESSION['error'] = "Incorrect Username and/or Password  ! ";
		}
		$stmt->close();
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login Page</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="login">
			<h1>Login</h1>
			<form action="login.php" method="post">
				<label for="nom">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="Username" placeholder="Enter ID" id="Username" required>
				<label for="Password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="Password" placeholder="Enter Password" id="Password" required>
				<input type="submit" value="Ok">
			</form>
			<?php  if($_SESSION['error']) {?>
			<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<?php echo $_SESSION['error']; ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			<?php  } ?>

		</div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
	</body>
</html>