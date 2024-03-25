<?php

$db = new PDO('mysql:host=localhost;dbname=nostalgiarea;charset=utf8','root','');

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//required files
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';


function generatePassword() {

    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    $length = rand(9, 12);

    $password = '';

    $password .= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1); 
    $password .= substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 1); 
    $password .= substr(str_shuffle('0123456789'), 0, 1); 

    for ($i = 3; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }

    $password = str_shuffle($password);

    return $password;
}



if(isset($_POST['create'])){
	if (!empty($_POST['username']) AND strlen($_POST['username']) >= 6){
		$username = htmlspecialchars($_POST['username']);
		$firstname = htmlspecialchars($_POST['firstname']);
		$lastname = htmlspecialchars($_POST['lastname']);
		$fetchCustomer = $db->prepare('SELECT username FROM customers WHERE username = ?');
		$fetchCustomer->execute(array($username));
		if($fetchCustomer->rowCount() > 0){
			header('Location: registererror.php');
        	exit;

		}	else{
				$password = generatePassword();
				$hashedPassword = hash('sha512', $password);
				$reassigned = 0;
				$insertCustomer = $db->prepare('INSERT INTO customers(username,firstname,lastname,password,reassigned)VALUES(?,?,?,?,?)');
				$insertCustomer->execute(array($username,$firstname,$lastname,$hashedPassword,$reassigned));

				$mail = new PHPMailer(true);

				//Server settings
				$mail->isSMTP();                              //Send using SMTP
				$mail->Host       = 'smtp.gmail.com';       //Set the SMTP server to send through
				$mail->SMTPAuth   = true;             //Enable SMTP authentication
				$mail->Username   = 'adelyoussouf.ay@gmail.com';   //SMTP write your email
				$mail->Password   = 'cvjkzabucvekygen';      //SMTP password
				$mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
				$mail->Port       = 465;                                    

				//Recipients
				$mail->addAddress($_POST['username']);     //Add a recipient email  

				//Content
				$mail->isHTML(true);               //Set email format to HTML
				$mail->Subject = "Registration successful !";   // email subject headings
				$mail->Body    = "Welcome to our Nostalgiarea ". $firstname ." ". $lastname . " ! Your temporary password is ". $password. "."; //email message
					
				// Success sent message alert
				$mail->send();
				echo "<script>alert('Registration successful !');</script>";
			}
		}

		else{
			header('Location: registererror.php');
        	exit;
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>

<style>


    .back-button {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #5f4415;
        color: #00203f;
        padding: 5px 10px;
        font-size: 14px;
		border-radius: 10px;
		border: 0.5px solid #6c6ea0;
    }

	a:link {
		color: white; /* Couleur des liens non visités */
    }

	body{
		background: #5f4415;
	}

</style>

<body>
	
	<div class="form-container login-container">
		 <a class="back-button" href="../index.php">Back to homepage</a>
			<form method="POST" action="">
				<h1>Sign up</h1>
				<br>
				<input type="email" name="username" placeholder="Username (e-mail)" autocomplete="off">
				<input type="text" name="firstname" placeholder="First name" autocomplete="off">
				<input type="text" name="lastname" placeholder="Last name" autocomplete="off">
				<button input type="submit" name="create">Create an account</button>
			</form>	
			
		</div>
		
		<div class="overlay-panel overlay-right">
					<h1>Already registered ?</h1>
					<p>Just sign in below !</p>
					<a href="login.php"><button class="ghost" id="login">Sign in</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>