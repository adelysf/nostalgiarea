<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=nostalgiarea;charset=utf8','root','');
if(!$_SESSION['admin']){
	header('Location: login.php');
}

$message = ""; // Variable for error messages

if(isset($_POST['submit'])){
	if (!empty($_POST['Name']) AND !empty($_POST['Price']) AND !empty($_POST['Quantity']) AND !empty($_POST['Description']) AND !empty($_POST['Platform'])){
		$name = htmlspecialchars($_POST['Name']);
		$platform = htmlspecialchars($_POST['Platform']);
		$price = htmlspecialchars($_POST['Price']);
		$description = nl2br(htmlspecialchars($_POST['Description']));
		$quantity=htmlspecialchars($_POST['Quantity']);
	}else{
		$message .= "Please complete all fields.<br>";
	}

	if(isset($_FILES['Image']) AND !empty($_FILES['Image']['name'])){
		$validFormats = array('jpg','jpeg','png');
			$imageFormat = strtolower(substr(strrchr($_FILES['Image']['name'], '.'), 1));
			if(in_array($imageFormat, $validFormats)){
				$path = "../products/images/".$_POST['Name'].".".$imageFormat;
				$result = move_uploaded_file($_FILES['Image']['tmp_name'], $path);
				if($result){
					$addAProduct = $db->prepare('INSERT INTO products (name, platform, image, price, quantity, description) VALUES (?, ?, ?, ?, ?, ?)');
					$addAProduct->execute(array($name, $platform, $_POST['Name'].".".$imageFormat, $price, $quantity, $description));
					ob_clean();
					$message .= "The product has been added.<br>";
				}else{
					$message .= "Error while importing your file.<br>";
				}
			}else{
				$message .= "Your file must be at format jpg, jpeg or png.<br>";
			}
		}
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<title>Add a product</title>
	<style>
		
        form{
			background-color: #fff;
			display: flex;
			flex-direction: column;
			padding: 0 50px;
			height: 100%;
			text-align: center;
		}

		.container {
            max-width: 750px;
			max-height: 1000px;
            background-color: #fff;
            border-radius: 5px;
			margin-top: 20px;
			margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

		body{
			background: linear-gradient(to bottom, #FFFFFF, #5f4415);
			display: flex;
			height: 100vh;
		}

		.back-button {
			background-color: #000000;
			color: #ffffff;
			padding: 5px 10px;
			font-size: 14px;
			border-radius: 10px;
			border: 0.5px solid #615643;
		}

		

	</style>

</head>

<body>

	<div class=container>
	<form method="POST" action="" enctype="multipart/form-data">
		<div>
			Name :<br>
			<input type="text" name="Name" size="50">
			<br>
			<br>
			Platform :
			<select name ="Platform" class="form-select form-select-sm-2">
				<option value="pc">PC</option>
				<option value="psp">PlayStation Portable</option>
				<option value="ps2">PlayStation 2</option>
				<option value="ps3">PlayStation 3</option>
				<option value="xbox 360">Xbox 360</option>
				<option value="xbox one">Xbox One</option>

			</select>
			<br>
			<br>
			Image :
			<input type="file" name="Image">
			<br>
			<br>
			Price :
			<textarea name="Price" rows="1" cols="3" style="resize: none;"></textarea>
			Quantity :
			<textarea name="Quantity" rows="1" cols="5" style="resize: none;"></textarea>
			<br>
			<br>
			Description :<br>
			<textarea name="Description" rows="4" cols="50" style="resize: none;"></textarea>
			<br>
			<br>
			<a href="." class="back-button">Back to the admin area</a>
			<input type="submit" name="submit">
			<br>
			<br>
			<?php echo $message; ?>
	</form>
	</div>
</body>
</html>
