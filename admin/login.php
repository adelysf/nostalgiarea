<?php
session_start();

$db = new PDO('mysql:host=localhost;dbname=nostalgiarea;charset=utf8','root','');


if(isset($_POST['connect'])){
	if (!empty($_POST['username']) AND !empty($_POST['password'])){
        $username = htmlspecialchars($_POST['username']);
        $password = hash('sha512', $_POST['password']);
        $fetchAdmin = $db->prepare('SELECT * FROM customers WHERE username = ? and password = ?');
        $fetchAdmin->execute(array("admin",$password));

        if($fetchAdmin->rowCount() > 0){
            $_SESSION['admin'] = $username;
			header('Location: index.php');
            exit();
        }

	    else{
			header('Location: ./error.php');
            exit;
		}


	}
    
    else{
		header('Location: ./error.php');
            exit;
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">

</head>
<body>

    <div class="container form-container login-container">
                <a href="../Index.php" class="back-button">Back to homepage</a>
                <form  method="POST" action="">
                    <h1>Admin login area</h1>
                    <input type="text" name="username" placeholder="Username" autocomplete="off">
                    <input type="password" name="password" placeholder="Password" autocomplete="off">
                    <button input type="submit" name="connect">Log in</button>
                </form>
            </div>
    </div>
    
</body>
</html>