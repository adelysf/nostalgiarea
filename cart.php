<?php

session_start();

$db = new PDO('mysql:host=localhost;dbname=nostalgiarea;charset=utf8','root','');
$req = $db->prepare('SELECT * FROM products');
$req->execute();
$Products = $req->fetchAll(PDO::FETCH_OBJ);

if (isset($_POST['remove'])){
  if ($_GET['action'] == 'remove'){
      foreach ($_SESSION['cart'] as $key => $value){
          if($value["product_id"] == $_GET['id']){
              unset($_SESSION['cart'][$key]);
              echo "<script>alert('Product has been removed !')</script>";
              echo "<script>window.location = 'cart.php'</script>";
          }
      }
  }
}

function cartElement($productimg, $productname, $productprice, $productid, $quantity){
    $element = "
    
    <form action=\"cart.php?action=remove&id=$productid\" method=\"post\" class=\"cart-items\">
                    <div class=\"border rounded\">
                        <div class=\"row bg-white\">
                            <div class=\"col-md-3 pl-0\">
                                <img src=\"./products/images/$productimg\" alt=\"Image1\" class=\"img-fluid\">
                            </div>
                            <div class=\"col-md-6\">
                                <h5 class=\"pt-2\">$productname</h5>
                                <h5 class=\"pt-2\">$productprice € per unit</h5>
                            </div>
                            <div class=\"col-md-3 py-5\">
                                <div>
                                    $quantity
                                    <button type=\"submit\" class=\"btn btn-danger mx-2\" name=\"remove\">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
    
    ";
    echo  $element;
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">
    <style>
    .navbar {
        background-color: #5f4415; 
        color: #FFFFFF;
    }

    .navbar-brand {
        color: #ffffff; 
    }

    .navbar-brand:hover {
        color: #cccccc; 
    }

    .navbar-nav .nav-link {
        color: #ffffff; 
    }

    .navbar-nav .nav-link:hover {
        color: #cccccc; 
    }

    </style>
</head>
<body class="bg-light">

<header id="header">
    <nav class="navbar navbar-expand-lg">
            <h3 class="px-5">
                Nostalgiarea
            </h3>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="articleoverview.php">Articles</a>
        </li>
        <li class="nav-item">
            <?php

            if (!isset($_SESSION['id'])){
                echo "<a class=\"nav-link\" href=\"./customer/login.php\">Log in</a>";
            }else{
                echo "<a class=\"nav-link\" href=\"./customer/profile.php\">My orders</a>";
            }

            ?>
          
        </li>
      </ul>
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
                    <i class="fas fa-shopping-basket"></i>  Shopping cart page
                    </h5>
                </div>
            </div>
        </div>

    </nav>
</header>

<div class="container-fluid">
    <div class="row px-5">
        <div class="col-md-7">
            <div class="shopping-cart">
                <br>
                <h6>Article details</h6>
                <hr>

                <?php

            $total = 0;
            if (isset($_SESSION['cart'])) {
                $product_ids = array_column($_SESSION['cart'], 'product_id');

                foreach ($Products as $product) {
                    if (in_array($product->id, $product_ids)) {
                        $cartItem = current(array_filter($_SESSION['cart'], function ($item) use ($product) {
                            return $item['product_id'] == $product->id;
                        }));
                        
                        $quantity = $cartItem['quantity'];
                        
                        cartElement($product->image, $product->name, $product->price, $product->id, $quantity);
                        $total += (float)$product->price * (int)$quantity;
                        
                    }
                }
            } else {
                echo "<h5>Your cart is empty.</h5>";
            }
                            

                ?>

            </div>
        </div>
        <div class="col-md-4 offset-md-1 border rounded mt-5 bg-white h-25">

            <div class="pt-4">
                <h6>Price details</h6>
                <hr>
                <div class="row price-details">
                    <div class="col-md-6">
                        <?php
                        
                            if (isset($_SESSION['cart'])){

                                $cart = $_SESSION['cart'];
                                
                                echo "<h6>Total cost</h6>
                                <h6>Discount</h6>
                                <h6>To pay</h6>
                                
                                ";

                            }else{
                                echo "<h6>No articles</h6>";
                            }
                        ?>

                    </div>
                    <div class="col-md-6">
                        <h6><?php echo $total; ?> €</h6>
                        <h6 class="text-success">
                            <?php 
                            if (isset($_SESSION['cart'])){
                                $totalQuantity = 0;
                                $maxQuantity = 1;
                                $topay = 1;
                                foreach ($cart as $item) {
                                        if ($item['quantity'] > $maxQuantity){
                                            $maxQuantity = $item['quantity'];
                                        }
                                        $totalQuantity += $item['quantity'];
                                    } if ($totalQuantity >= 16){
                                        $topay = 0.84;
                                        echo "16%";
                                    } elseif($maxQuantity >= 8){
                                        $topay = 0.92;
                                        echo "8%";
                                    } else{
                                        echo "<h6 class=\"black\">No discount</h6>";
                                    }
                                    $_SESSION['pbdc'] = $total*$topay;
                                    echo "<h6>";
                                    echo $total*$topay;
                                    echo" €</h6><br>";
                                    echo "<a href=\"checkout.php\" type=\"button\"  class=\"btn btn-dark mx-2 mb-3\" name=\"Checkout\">Proceed to checkout</a>";
                                } else{
                                    echo "<br>";
                                }
                            ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>