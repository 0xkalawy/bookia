<link rel="stylesheet" href="./style/header.css">
<header>
    <ul>
        <li><a href="/bookia/">Bookia</a></li>
        <?php
            require_once("./checklogin.php");
            if (isLogged()){
        ?>
                <li><a href="/bookia/logout.php">Logout</a></li>
                <li><a href="/bookia/profile.php"><?php echo $_SESSION['username'] ?></a></li>
                <?php 
                    if ($_SESSION['role'] === 'seller'){
                ?>
                        <li><a href="/bookia/seller.php">Seller Portal</a></li>
                <?php
                    }else if($_SESSION['role'] === 'admin'){
                ?>
                        <li><a href="./admin.php">Admin Portal</a></li>
                <?php
                    }
                ?>
        <?php
            }else{
        ?>
        <li><a href="/bookia/signin.php">Login</a></li>
        <li><a href="/bookia/signup.php">Signup</a></li>
    </ul>
    <?php } ?>

</header>