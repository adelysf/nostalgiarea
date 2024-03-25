<?php

session_start();

if(!$_SESSION['admin']){
	header('Location: login.php');
	exit();
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<script src="https://kit.fontawesome.com/7a9616a72d.js" crossorigin="anonymous"></script>

	<title>Admin area</title>

	<style>
		

	.container {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		height: 100vh;
	}

	.button-container {
		text-align: center;
	}

	button {
		margin: 10px;
	}

	</style>
	
</head>
<body>
	<div class="container">
		<a href=".." class="back-button">Back to homepage</a>

		<h1>Admin area</h1>
		
		<div class="button-container">
			<p>
				<a href="addAProduct.php">
					<button>Add a new product</button>
				</a>
			</p>
			<p>
				<a href="logout.php">
					<button>Log out</button>
				</a>
			</p>
		</div>
	</div>
</body>
</html>
