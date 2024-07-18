<?php
    require_once("./checklogin.php");
    if (isLogged()){
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/header.css">
</head>
<header>
    <ul>
        <li><a href="/bookia/logout.php">Logout</a></li>
        <li><a href="/bookia/profile.php"><?php echo $_SESSION['username'] ?></a></li>
    </ul>
</header>
<?php
    }
?>