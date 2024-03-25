<?php

session_start();

$db = new PDO('mysql:host=localhost;dbname=nostalgiarea;charset=utf8','root','');
$req = $db->prepare('SELECT * FROM products');
$req->execute();
$Products = $req->fetchAll(PDO::FETCH_OBJ);

if (isset($_POST['add'])) {

    if(!isset($_SESSION['id'])){
        echo "<script>alert('Please log in to add items to your cart.');</script>";
    }

    else{


    $product_id = $_POST['product_id'];
    $product_rem = $_POST['quantity_rem'];

    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];

        $found = false;
        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $product_id) {
                if ($cart[$key]['quantity'] < $product_rem) {
                    $cart[$key]['quantity']++;
                    $_SESSION['cart'] = $cart;
                } else {
                    echo "<script>alert('All our remaining products are on your cart !')</script>";
                }
                $found = true;
                break;
            }
        }
        if (!$found) {
            $cart[] = array('product_id' => $product_id, 'quantity' => 1);
            $_SESSION['cart'] = $cart;
        }
    }else {
        $_SESSION['cart'] = array(array('product_id' => $product_id, 'quantity' => 1));
    }
}
}
        

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Adel Youssouf Ali">
    <title>Article overview</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>



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

    .custom-card {
        
        width: 360px;
        height: 350px;
    }
</style>



    
  </head>
  <body>
    
  <header id="header">
    <nav class="navbar navbar-expand-lg">
        <a href="index.php" class="navbar-brand">
            <h5 class="px-5">
                <i class="fas fa-shopping-basket"></i> Nostalgiarea
            </h5>
        </a>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="">Articles</a>
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

        <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a href="./cart.php" class="nav-item nav-link active">
                <h4 class="px-3 cart">
                    <i class="fas fa-shopping-cart"></i>

                    <?php
                    if (isset($_SESSION['cart'])) {
                        $cart = $_SESSION['cart'];

                        
                        $totalQuantity = 0;

                        
                        foreach ($cart as $item) {
                            $totalQuantity += $item['quantity'];
                        }

                        echo "<div class=\"icon-cart\">
                                    <svg aria-hidden=\"true\" xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 18 20\">
                                        <path stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M6 15a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0h8m-8 0-1-4m9 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-9-4h10l2-7H3m2 7L3 4m0 0-.792-3H1\"/>
                                    </svg>
                                    
                            </div>
                            <span>$totalQuantity</span>";
                    } else {
                        echo "<div class=\"icon-cart\">
                                    <svg aria-hidden=\"true\" xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 18 20\">
                                        <path stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M6 15a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0h8m-8 0-1-4m9 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-9-4h10l2-7H3m2 7L3 4m0 0-.792-3H1\"/>
                                    </svg>
                                    
                        </div>
                        <span>0</span>";
                    }
                    ?>

                </h4>
            </a>
        </div>
    </div>

    </nav>
</header>

<main>
    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-3 g-3">
            <?php foreach ($Products as $product): ?>
                <form action="articleoverview.php" method="post">
                    <?php $i = $product->id ?>
                    <div class="col">
                        <div class="card shadow-sm text-center custom-card">
                            <h5><?= $product->name ?></h5>
                            <img href="produit.php?pdt=<?= $product->id ?>" src="<?= "./products/images/" . $product->image ?>" style="width: 35%;" class="mx-auto d-block" alt="Product Image">
                            <div class="card-body d-flex flex-column">
                                <p class="card-text"><?= "Platform : " . $product->platform . " | " . $product->quantity . " remaining" ?></p>
                                <input type='hidden' name='quantity_rem' value=<?=$product ->quantity ?>>
                                <div class="d-flex justify-content-between mt-auto">
                                    <div class="btn-group">
                                        <button type="submit" name="add" class="btn btn-sm btn-warning"><?= "Add " . " to cart" ?></button>
                                        <input type='hidden' name='product_id' value=<?=$product ->id ?>>

                                    </div>
                                    
                                    <small class="text" style="font-weight: bold;"><?= $product->price ?> €</small>
                    
                                    <?php $i++; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>