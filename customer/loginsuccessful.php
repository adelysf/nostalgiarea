<?php

session_start();
$db = new PDO('mysql:host=localhost;dbname=nostalgiarea;charset=utf8','root','');

if (isset($_SESSION['id'])) {

    $firstLogin = $db->prepare('SELECT reassigned FROM customers WHERE username = ?');
    $firstLogin->execute(array($_SESSION['username']));
    $result = $firstLogin->fetch(PDO::FETCH_ASSOC);
    $reassignedValue = (int)$result['reassigned'];
    if ($reassignedValue == 0){
        header('Location: changepassword.php');
        exit();
    }else{
        $insertLoginDetails = $db->prepare('INSERT INTO connections(user, date, os)VALUES(?,?,?)');
        $insertLoginDetails->execute(array($_SESSION['id'],date('Y-m-d H:i:s'), php_uname('s')));
        header('Location: ../index.php');
        exit();
    }
} else{
    header('Location: login.php');
    exit();
}
?>
