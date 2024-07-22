<?php
    require_once("./checklogin.php");
    require_once("./config.php");
    redirect_on_logout();
    require_once("./header.php");
    if(!empty($_REQUEST['id']) && $_REQUEST['id'] !== $_SESSION['id']){
        $info = $sql->query("SELECT username,name,image FROM users WHERE id=".$_REQUEST['id'])->fetch_row();
        if(!empty($info)){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/profile.css">
    <title>Profile</title>
</head>
<body>
    <div class="container">
        <h2>Profile</h2>
        <img src="<?php $info[2] ?>" alt="">
        <form id="profileForm">
            <label for="username">Username</label>
            <input type="text" name="username" readonly value="<?php echo $info[0]?>">
            <label for="name">Name</label>
            <input type="text" name="name" readonly value="<?php echo $info[1] ?>">
        </form>
    </div>
</body>
<?php
        }
    }else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/profile.css">
    <title>Profile</title>
</head>
<body>
    <div class="container">
        <h2>Profile</h2>
        <img src="<?php image_path($_SESSION['id']) ?>" alt="">
        <div id="response" class="response"></div>
        
        <form id="profileForm">
            <input type="file" id="image" name="image">
            <label for="username">Username</label>
            <input type="text" name="username" value="<?php echo $_SESSION['username']; ?>">
            <label for="name">Name</label>
            <input type="text" name="name" value="<?php echo $_SESSION['name']; ?>">
            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo $_SESSION['email']; ?>">
            <div class="radio-group">
                <?php
                    $r1 = $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'seller' ? 'seller' : 'buyer';
                    $r2 = $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'seller' ? 'buyer' : 'seller';
                ?>
                <input type="radio" name="role" id="role1" value="<?php echo $r1; ?>" checked>
                <label for="role1"><?php echo $r1; ?></label>

                <input type="radio" name="role" id="role2" value="<?php echo $r2; ?>">
                <label for="role2"><?php echo $r2; ?></label>
            </div>
            <label for="password">Password</label>
            <input type="password" name="password">
            <label for="repassword">Retype Password</label>
            <input type="password" name="repassword">
            <input type="submit" value="Update" id="update">
        </form>
    </div>
</body>
<script>
    document.getElementById('profileForm').onsubmit = function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = JSON.parse(this.responseText);
                var responseDiv = document.getElementById("response");
                responseDiv.innerHTML = response.message;
                responseDiv.style.display = "block";
                if (response.message.indexOf("Image Update Successfully")>-1) {
                    window.location.reload();
                }
            }
        };
        xhr.open("POST", "./update_profile.php");
        xhr.send(formData);
    };
</script>
<?php } ?>
</html>
