<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
	header('Location: home.php');
	exit();
}

$error_message = isset($_SESSION["error"]) ? $_SESSION["error"] : '';

?>

<?php include("header.php"); ?>

			<form action="login-submit.php" method="post" class='inputForm'>
				<h1><strong>Login</strong></h1>

				<?php if (!empty($error_message)) : ?>
					<p style="color: red;"><?php echo $error_message; ?></p>
					<?php $_SESSION["error"]="";
					endif; ?>

				<label for="username">Username:</label>
				<input type="text" id="username" name="username" placeholder="Enter your username" required>

				<label for="password">Password:</label>
				<input type="password" id="password" name="password" placeholder="Enter your password" required>

				<button type="submit">Login</button>
				<p>Don't have an account? <a href="signup.php" style="color: red;">Register here</a></p>
			</form>
<?php 
$_SESSION["error"]="";
include("footer.html");
?>
