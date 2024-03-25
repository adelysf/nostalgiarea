<?php
session_start();

function validPassword($password) {

    if (strlen($password) < 9) {
        return false;
    }


    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        return false;
    }

    return true;
}

$db = new PDO('mysql:host=localhost;dbname=nostalgiarea;charset=utf8','root','');

if (isset($_SESSION['id'])) {
    if(isset($_POST['create'])){
        if(!empty($_POST['password']) AND validPassword($_POST['password'])){
            $reassigned = 1;
            $password = hash('sha512', $_POST['password']);
            $updateQuery = $db->prepare('UPDATE customers SET password = ?, reassigned = ? WHERE id = ?');
            $updateQuery->execute(array($password, $reassigned, $_SESSION['id']));
            header('Location: ./loginsuccessful.php');
            exit();
        }else{
            header('Location: badpassword.php');
            exit();
        }
    }
}else{
    header('Location: login.php');
    exit();    
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Change password</title>
    <style>
        
        body {
            font-family: "Century Gothic", Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;

        }

        .container {
            width: 750px;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #5f4415;
        }

        p {
            color: #666;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #5f4415;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="">
            <h1>Choose a new password</h1>
            Your password must be at least nine characters long and contain one upper case letter, one lower case letter and one number.
            <br>
            <br>
            <input type="password" name="password" placeholder="Password" autocomplete="off">
            <br><br>
            <button class="btn" input type="submit" name="create">Validate your new password</button>
        </form>
    </div>
</body>
</html>
