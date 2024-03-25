<?php

session_start();

$db = new PDO('mysql:host=localhost;dbname=nostalgiarea;charset=utf8','root','');

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//required files
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (!isset($_SESSION['id'])) {
    header('Location: ./customer/login.php');
    exit();
} else{

    $req = $db->prepare('SELECT * FROM orders');
    $req->execute();
    $Orders = $req->fetchAll(PDO::FETCH_OBJ);

    $req2 = $db->prepare('SELECT * FROM products');
    $req2->execute();
    $Products = $req->fetchAll(PDO::FETCH_OBJ);

    if (!isset($_SESSION['cart'])){
        header("Location: articleoverview.php");
        exit();
    }

    else{

        $cart = $_SESSION['cart'];

        if (isset($_POST['submit'])) {

            $uid = $_SESSION['id'];
            $orderNumber = count($Orders) + 1;
            $updatedAmount = floatval($_POST['updatedAmount']);
            $address = $_POST['Address'];
            $shippingChoice = $_POST['shippingChoice'];

            $stmt = $db->prepare('INSERT INTO orders (id, user_id, price, date, address, shipping) VALUES (:orderNumber, :user, :updatedAmount, NOW(), :addressEntered, :shippingChoice)');
            $stmt->bindParam(':orderNumber', $orderNumber, PDO::PARAM_INT);
            $stmt->bindParam(':user', $uid, PDO::PARAM_INT);
            $stmt->bindParam(':updatedAmount', $updatedAmount, PDO::PARAM_STR);
            $stmt->bindParam(':shippingChoice', $shippingChoice, PDO::PARAM_STR);
            $stmt->bindParam(':addressEntered', $address, PDO::PARAM_STR);

            $stmt->execute();

            foreach ($cart as $item) {

                $stmt = $db->prepare('INSERT INTO order_details (order_id, product_id, quantity) VALUES (:orderNumber, :product, :quantity)');
                $stmt->bindParam(':orderNumber', $orderNumber, PDO::PARAM_INT);
                $stmt->bindParam(':product', $item['product_id'], PDO::PARAM_INT);
                $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);

                $stmt->execute();

                $stmt = $db->prepare('UPDATE products SET quantity = quantity - :qtyToDecrease WHERE id = :productid');
                $stmt->bindParam(':qtyToDecrease', $item['quantity'], PDO::PARAM_INT);
                $stmt->bindParam(':productid', $item['product_id'], PDO::PARAM_INT);

                $stmt->execute();

                }

                $fetchOrderDetails = $db->prepare('SELECT * FROM order_details WHERE order_id = ?');
                $fetchOrderDetails->execute(array($orderNumber));
                $OrderDetails = $fetchOrderDetails->fetchAll(PDO::FETCH_ASSOC);

                $invoice = "";

                foreach ($OrderDetails as $orderDetail) {
        
                $fetchProductName = $db->prepare('SELECT name FROM products WHERE id = ?');
                $fetchProductName->execute(array($orderDetail['product_id']));
                $productName = $fetchProductName->fetchColumn();

        
                $invoice .= ($orderDetail['quantity'] . " x " . $productName . ", " ) ;

        
    }
            
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
				$mail->addAddress($_SESSION['username']);     //Add a recipient email  

				//Content
				$mail->isHTML(true);               //Set email format to HTML
				$mail->Subject = "Order sent !";   // email subject headings
				$mail->Body    = "Thank you for your order. Here are the details : order number " . $orderNumber .". Your products are ". $invoice . " and your shipping is " . $_POST['shippingChoice'] . "." . " Total amount : " . $updatedAmount . " euros."; //email message
					
				// Success sent message alert
				$mail->send();

            unset($_SESSION['cart']);
            
            header("Location: thankyou.php");
            exit();
        }

        }
    }

    ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<header id="header">
    <nav class="navbar navbar-expand-lg">
        <a href="index.php" class="navbar-brand">
            <h3 class="px-5">
                Nostalgiarea
            </h3>
        </a>
        <button class="navbar-toggler"
            type="button"
                data-toggle="collapse"
                data-target = "#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup"
                aria-expanded="false"
                aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="mr-auto"></div>
            <div class="navbar-nav">
                <div class="nav-item nav-link active">
                    <h5 class="px-5 cart">
                    <i class="fas fa-shopping-basket"></i>  Checkout page
                    </h5>
                </div>
            </div>
        </div>

    </nav>
</header>

<div class="container-fluid">
        <div class="border rounded mt-5 bg-white h-25 text-center">

            <div class="pt-4">
                <h6>Order details</h6>
                <hr>
                <div class="row price-details">
                    <div class="col-md-6">
                        <?php
                        
                            if (isset($_SESSION['cart'])){

                                $cart = $_SESSION['cart'];
                                
                                echo "<h6>Total without delivery costs</h6>";
                            }else{
                                echo "<h6>No articles</h6>";
                            }
                        ?>
                        <h6>Shipping choice</h6>
                        <hr>
                        <h6>Final amount</h6>
                        <br><br>
                        <h6>Address</h6>
                    </div>
                    <div class="col-md-6">
                        <h6><?php 
                        $finalAmount = $_SESSION['pbdc'];
                        echo $_SESSION['pbdc']; ?> €</h6>
                        <h6 class="text-dark">
                        <form method="POST" action="">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="shippingChoice" id="inlineRadio1" value="DPD" data-cost="5">
                                <label class="form-check-label" for="inlineRadio1">DPD (+5 €)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="shippingChoice" id="inlineRadio2" value="DHL" data-cost="24">
                                <label class="form-check-label" for="inlineRadio2">DPD (+24 €)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="shippingChoice" id="inlineRadio3" value="DHL Express" data-cost="68">
                                <label class="form-check-label" for="inlineRadio3">DHL Express (+68 €)</label>
                            </div>
                        </h6>
                        <hr>
                        <h6 id="finalAmount"><?php echo $finalAmount; ?> €</h6>
                        <input type="hidden" name="updatedAmount" id="updatedAmount" value="<?php echo $finalAmount; ?>">
                            <br>
                        <textarea name="Address" rows="4" cols="50" style="resize: none;" required></textarea>
                        <br><br>
                        <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                        <label class="form-check-label" for="flexCheckDefault">
                            I agree with the data protection terms of Nostalgiarea.
                        </label>
                        </div><br>
                        <button class="btn btn-dark mx-2 mb-3" name="submit">Validate your order</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var finalAmountElement = document.getElementById('finalAmount');
        var updatedAmountField = document.getElementById('updatedAmount');
        var shippingChoices = document.getElementsByName('shippingChoice');

        shippingChoices.forEach(function (choice) {
            choice.addEventListener('change', function () {
                var selectedOption = document.querySelector('input[name="shippingChoice"]:checked');
                var shippingCost = selectedOption ? parseInt(selectedOption.getAttribute('data-cost')) : 0;

                var updatedAmount = <?php echo $finalAmount; ?> + shippingCost;
                finalAmountElement.textContent = updatedAmount + ' €';
                updatedAmountField.value = updatedAmount;
            });
        });
    });
</script>



<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>