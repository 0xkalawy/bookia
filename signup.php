<?php
    require_once("./checklogin.php");
    require_once("./config.php");
    redirect_on_signin();
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        if(empty($_REQUEST['name']) || empty($_REQUEST['username']) || empty($_REQUEST['email']) || empty($_REQUEST['password'])){
            echo json_encode(["message" => "Please Enter all credentials"]);
        }else if(strlen($_REQUEST['password'])<=8){
            echo json_encode(["message" => "Password Should be more than 8 characters"]);
        }else if(!preg_match("@[A-Z]@",$_REQUEST['password']) || !preg_match("@[a-z]@",$_REQUEST['password'])|| !preg_match("@[0-9]@",$_REQUEST['password']) || !preg_match("@[^\w]@",$_REQUEST['password'])) {
            echo json_encode(["message" => "Passowrd should contain Uppercases, Lowercases, Digits, and Special Characters"]);
        }else{
            $result = $sql->query("SELECT * FROM users WHERE username='{$_REQUEST['username']}' OR email= '{$_REQUEST['email']}'")->fetch_row();
            if(!empty($result)){
                echo json_encode(["message" => "Username or/and Email is already exist"]);
            }else if($_REQUEST['password']!=$_REQUEST['repassword']){
                echo json_encode(["message"=>"Password isn't identical"]);
            }else{
                $sql->query("INSERT into users(name,username,email,password,role) VALUES('".$_REQUEST["name"]."','".$_REQUEST["username"]."','".$_REQUEST["email"]."','".$_REQUEST["password"]."','".$_REQUEST['role']."')");
                echo init_session($_REQUEST['username'],$_REQUEST['password'])=="success"? json_encode(["message" => "Registered Successfully","redirect"=>"/bookia/home.php"]):json_encode(["message" => "There is a problem"]);
            }
        }
    }
    else{   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/signup.css">
    <title>Sign Up</title>
</head>
<body>
    <div class="container">
        <h2>Sign Up Form</h2>
        <form id="signupForm">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="repassword">Retype your password:</label>
            <input type="password" id="repassword" name="repassword" required>
            <input type="radio" name="role" id="role" value="buyer" required>Buyer
            <input type="radio" name="role" id="role" value="seller" required>Seller
            <div class="button-group">
                <input type="submit" value="Sign Up">
                <input type="reset" value="Reset">
            </div>
        </form>
        <div id="response" class="response"></div>
    </div>
</body>
<script>
    document.getElementById('signupForm').onsubmit = function (event) {
        event.preventDefault();
        var data = new FormData(document.getElementById('signupForm'));
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function (){
            var response = JSON.parse(this.responseText);
            if(response.redirect){
                window.location.replace(response.redirect);
            }else if(this.status == 200 && this.readyState == 4){
                var responseDiv = document.getElementById("response");
                responseDiv.innerHTML = response.message;
                responseDiv.classList.add("success"); // Add success class
                responseDiv.style.display = "block";
            }else if (this.readyState == 4) {
                var responseDiv = document.getElementById("response");
                responseDiv.innerHTML = "An error occurred";
                responseDiv.classList.add("error"); // Add error class
                responseDiv.style.display = "block";
            }
        }
        xhr.open("POST", "http://localhost/bookia/signup.php");
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest"); // Indicate AJAX request
        xhr.send(data);
    }
</script>
</html>
<?php } ?>
