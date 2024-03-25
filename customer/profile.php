<?php
session_start();

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//required files
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$db = new PDO('mysql:host=localhost;dbname=nostalgiarea;charset=utf8','root','');



$getid = $_SESSION['id'];
$fetchCustomerOrders = $db->prepare('SELECT * FROM orders WHERE user_id = ?');
$fetchCustomerOrders->execute(array($getid));
$orders = $fetchCustomerOrders->fetchAll(PDO::FETCH_ASSOC);

echo '<a href="signout.php"><button>Signout</button></a><a href="../index.php"><button>Back to home page</button></a><br><br>';

if (!$_SESSION['password']) {  
    header('Location: login.php');
    exit();
}

foreach ($orders as $order) {
    $fetchOrderDetails = $db->prepare('SELECT * FROM order_details WHERE order_id = ?');
    $fetchOrderDetails->execute(array($order['id']));
    $OrderDetails = $fetchOrderDetails->fetchAll(PDO::FETCH_ASSOC);

    echo "Order ID: " . $order['id'] . "<br>";
    echo "Total Amount: " . $order['price'] . " €<br>";
    echo "Shipping: " . $order['shipping'] . "<br>";
   
    foreach ($OrderDetails as $orderDetail) {
        
        $fetchProductName = $db->prepare('SELECT name FROM products WHERE id = ?');
        $fetchProductName->execute(array($orderDetail['product_id']));
        $productName = $fetchProductName->fetchColumn();

        echo $orderDetail['quantity'] . " x " . $productName . "<br>" ;
        
    }
    echo "<br>
    <form method=\"POST\" action=\"\">
        <input type=\"hidden\" name=\"order_id\" value=\"" . $order['id'] . "\">
        <button name=\"order_again\">Order it again</button>
    </form>";

    echo "<hr>";
}

if (isset($_POST['order_again'])) {

    $invoice = "";

    $req = $db->prepare('SELECT * FROM orders');
    $req->execute();
    $Orders = $req->fetchAll(PDO::FETCH_OBJ);
    $ordertoCopy = $_POST['order_id'];
    $newOrderId = count($Orders) + 1;

    
    $selectOrder = $db->prepare('SELECT * FROM orders WHERE id = :order_id');
    $selectOrder->bindParam(':order_id', $ordertoCopy, PDO::PARAM_INT);
    $selectOrder->execute();
    $Order = $selectOrder->fetch(PDO::FETCH_ASSOC);

    $insertOrder = $db->prepare('INSERT INTO orders (id, user_id, price, shipping, date, address) VALUES (:new_order_id, :user_id, :price, :shipping, NOW(), :address)');
    $insertOrder->bindParam(':new_order_id', $newOrderId, PDO::PARAM_INT);
    $insertOrder->bindParam(':user_id', $Order['user_id'], PDO::PARAM_INT);
    $insertOrder->bindParam(':price', $Order['price'], PDO::PARAM_STR);
    $insertOrder->bindParam(':shipping', $Order['shipping'], PDO::PARAM_STR);
    $insertOrder->bindParam(':address', $Order['address'], PDO::PARAM_STR);

    
    $selectOrderDetails = $db->prepare('SELECT * FROM order_details WHERE order_id = :order_id');
    $selectOrderDetails->bindParam(':order_id', $ordertoCopy, PDO::PARAM_INT);
    $selectOrderDetails->execute();
    $orderDetails = $selectOrderDetails->fetchAll(PDO::FETCH_ASSOC);

    $quantitiesCheck = true;

    foreach ($orderDetails as $orderDetail) {
        $checkQuantity = $db->prepare('SELECT quantity FROM products WHERE id = :product_id');
        $checkQuantity->bindParam(':product_id', $orderDetail['product_id'], PDO::PARAM_INT);
        $checkQuantity->execute();
        $currentQuantity = $checkQuantity->fetchColumn();

        $fetchProductName = $db->prepare('SELECT name FROM products WHERE id = ?');
        $fetchProductName->execute(array($orderDetail['product_id']));
        $productName = $fetchProductName->fetchColumn();


        $invoice .= ($orderDetail['quantity'] . " x " . $productName . ", " ) ;

        if ($currentQuantity < $orderDetail['quantity']) {
            $quantitiesCheck = false;
            break;
        }
    }    
    if (!$quantitiesCheck) {
        echo '<script>alert("Sorry, we are running out of stock so we cannot validate your order.");</script>';
        exit();  
    }
    
    $insertOrder->execute();

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
    $mail->Body    = "Thank you for your order. Here are the details : order number " . $newOrderId .". Your products are ". $invoice . " and your shipping is " . $Order['shipping'] . "." . " Total amount : " . $Order['price'] . " euros."; //email message
        
    // Success sent message alert
    $mail->send();

    foreach ($orderDetails as $orderDetail) {
        $insertOrderDetail = $db->prepare('INSERT INTO order_details (order_id, product_id, quantity) VALUES (:new_order_id, :product_id, :quantity)');
        $insertOrderDetail->bindParam(':new_order_id', $newOrderId, PDO::PARAM_INT);
        $insertOrderDetail->bindParam(':product_id', $orderDetail['product_id'], PDO::PARAM_INT);
        $insertOrderDetail->bindParam(':quantity', $orderDetail['quantity'], PDO::PARAM_INT);
        $insertOrderDetail->execute();

        $req = $db->prepare('UPDATE products SET quantity = quantity - :qtyToDecrease WHERE id = :productid');
        $req->bindParam(':qtyToDecrease', $orderDetail['quantity'], PDO::PARAM_INT);
        $req->bindParam(':productid', $orderDetail['product_id'], PDO::PARAM_INT);

        $req->execute();
    }
      

    echo '<script>window.location.href = window.location.href;</script>';
    exit();  

}

?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My orders</title>
    <style>
        body {
            background: white;
            margin: 20px;
        }

        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #B788F0;
            color: #white;
            padding: 5px 10px;
            font-size: 14px;
	        border-radius: 10px;
	        border: 0.5px solid #6c6ea0;
        }
    </style>

</head>
<body>
</body>
</html>