<?php
    require_once("./checklogin.php");
    require_once("./config.php");
    redirect_on_signin();
    if($_SERVER['REQUEST_METHOD'] === "POST"){
        if(empty($_REQUEST['username']) || empty($_REQUEST['password'])){
            echo json_encode(["message"=>"Please Enter a username and a password","status"=>"401"]);
        }else{
            echo init_session($_REQUEST['username'],$_REQUEST['password'])=="success" ? json_encode(["message"=>"Success","redirect"=>"/bookia/home.php","status"=>"200"]) : json_encode(["message"=>"invalid username or/and password","status"=>"400"]);
        }
    }else{
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/bookia/style/signin.css">
    <title>Sign in</title>
</head>
<body>
    <div class="container" id="container">
        <h2>Sign In</h2>
        <form id="signInForm">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <div class="button-group">
                <input type="submit" value="Log in">
            </div>
        </form>
        <div id="response" class="response"></div> <!-- response div inside container -->
    </div>

    <script>
        document.forms[0].onsubmit = function(event){
            event.preventDefault();
            var responseDiv = document.getElementById("response");
            var data = new FormData(document.getElementById("signInForm"));
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(){
                if (this.readyState == 4) {
                    var response = JSON.parse(this.responseText);
                    if (response.status == "200") {
                        responseDiv.className = "response success";
                        responseDiv.innerText = response.message;
                        window.location = response.redirect;
                    } else if (response.status == "401") {
                        responseDiv.className = "response warning";
                        responseDiv.innerText = response.message;
                    } else {
                        responseDiv.className = "response error";
                        responseDiv.innerText = response.message;
                    }
                    responseDiv.style.display = "block";
                }
            };
            xhr.open("POST", "./signin.php");
            xhr.send(data);
        };
    </script>
</body>
</html>
<?php } ?>