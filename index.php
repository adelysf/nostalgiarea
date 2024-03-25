<?php

session_start();

$db = new PDO('mysql:host=localhost;dbname=nostalgiarea;charset=utf8','root','');
$req = $db->prepare('SELECT * FROM products');
$req->execute();
$Products = $req->fetchAll(PDO::FETCH_OBJ);

if (isset($_SESSION['id'])){

    $req = "SELECT firstname, lastname FROM customers WHERE id = :userId";
    $UserInfo = $db->prepare($req);
    $UserInfo->bindParam(':userId', $_SESSION['id'], PDO::PARAM_INT);
    $UserInfo->execute();
    $userInfo = $UserInfo->fetch(PDO::FETCH_ASSOC);

    $log = "Hello " . $userInfo['firstname'] . " " . $userInfo['lastname'] . ".";
    $sql = "SELECT COUNT(*) as count FROM connections WHERE user = :userId";
    $req = $db->prepare($sql);
    $req->bindParam(':userId', $_SESSION['id'], PDO::PARAM_INT);
    $req->execute();
    $result = $req->fetch(PDO::FETCH_ASSOC);

if ($result['count'] == 1) {
    $log .= " This is your first connexion.";
} else {
    $sql = "SELECT date FROM connections WHERE user = :userId ORDER BY date DESC LIMIT 1";
    $req = $db->prepare($sql);
    $req->bindParam(':userId', $_SESSION['id'], PDO::PARAM_INT);
    $req->execute();
    $result = $req->fetch(PDO::FETCH_ASSOC);

    $log .= " You were last online on " . $result['date']. ".";
}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="YOUSSOUF ALI Adel">
    <title>NostalgiArea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css">

    
    <style>
    body {
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
    }

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
          <a class="nav-link active" aria-current="page" href="">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="articleoverview.php">Articles</a>
        </li>
        <li class="nav-item">
            <?php

            if (!isset($_SESSION['id'])){
                echo "<a class=\"nav-link\" href=\"./customer/login.php\">Log in</a>";
            }else{
                echo "<p class=\"nav-link\">$log</p>";
            }

            ?>
          
        </li>
      </ul>
    </nav>
</header>

<div class="container mt-3">
    <div class="row">
        <div class="col text-center">
            <h2>Welcome to Nostalgiarea</h2>
            <p>The best seller of your childhood games !</p>
        </div>
    </div>
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <?php
                $i = 0;
                foreach ($Products as $product) {
                    $actives = ($i == 0) ? 'active' : '';
                    $i++;
                }
                ?>
            </ol>
            <div class="carousel-inner">
                <?php
                $i = 0;
                foreach ($Products as $product) {
                    $actives = ($i == 0) ? 'active' : '';
                    if ($i == 3) {
                        break;
                    }
                ?>
                    <div class="carousel-item <?= $actives; ?>" text=center>
                        <img src="<?= "./products/images/" . $product->image ?>" class="d-block w-100" alt="<?= $product->name ?>">
                        <div class="carousel-caption">
                            <h5><?= $product->name ?></h5>
                            <p><?= $product->description ?></p>
                        </div>
                    </div>
                <?php
                    $i++;
                }
                ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>

