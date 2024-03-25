<?php
session_start();

$db = new PDO('mysql:host=localhost;dbname=nostalgiarea;charset=utf8','root','');


if (isset($_SESSION['id'])) {
        header('Location: ../index.php');
        exit();
    }


function validPassword($password) {

    if (strlen($password) < 9) {
        return false;
    }


    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        return false;
    }

    return true;
}



if(isset($_POST['login'])){
    if (!empty($_POST['username']) AND !empty($_POST['password']) AND strlen($_POST['username']) >= 6 ){
        if(validPassword($_POST['password'])){
            $username = htmlspecialchars($_POST['username']);
            $password = hash('sha512', $_POST['password']);
            $fetchCustomer = $db->prepare('SELECT * FROM customers WHERE username = ? and password = ?');
            $fetchCustomer->execute(array($username, $password));

            if($fetchCustomer->rowCount() > 0){
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                $_SESSION['id'] = $fetchCustomer->fetch()['id'];

                header('Location: loginsuccessful.php');
                exit();

            } else {
                header('Location: wrong.php');
                exit;
            }
        } else{
            header('Location: badpassword.php');
            exit;
        }
    } else{
        header('Location: wrong.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in</title>
    <link rel="stylesheet" type="text/css" href="styles.css">



    <style>

        .container {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            margin-top: 30px;
            
        }

        a:link, a:visited {
            color: black; 
        }

    </style>

</head>
    
<body>
	<a href="../index.php">Back to homepage</a>
    <div class="container">
        <div class="form-container login-container">
            <form method="POST" action="">
                <h1>Sign in</h1>
                <br>
                <input type="text" name="username" placeholder="Username (e-mail)" autocomplete="off">
                <input type="password" name="password" placeholder="Password" autocomplete="off">
                <button type="submit" name="login">Log in</button>
                <p>Don't have an account yet ? Don't wait any longer and create one!</p>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-right">
                    <h1>Welcome !</h1>
                    <p>Create your account & buy the games of your childhood !</p>
                    <a href="register.php"><button id="signUp">Create an account</button></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
