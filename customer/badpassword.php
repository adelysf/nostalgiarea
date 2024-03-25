<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bad password</title>
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
            max-width: 500px;
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
        <h1>Houston, we have a problem...  </h1>
        <p>Your password must be at least nine characters long and contain one upper case letter, one lower case letter and one number.</p>
        <p><a class="btn" href="./loginsuccessful.php">Try again</a></p>
    </div>
</body>
</html>
