<?php
    require_once("./checklogin.php");
    redirect_on_signin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./style/index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookia - Home</title>
</head>
<body>
    <a href="./signin.php">Sign in</a>
    <a href="./signup.php">Sign up</a>
</body>
</html>