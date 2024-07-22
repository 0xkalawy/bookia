<?php
    $sql = new mysqli('localhost','root','','bookia');
    $images_path = "./images/";

    // Get profile image path
    function image_path($id){
        global $sql,$images_path;
        $path = $sql->query("SELECT image FROM users WHERE id=".$id)->fetch_row()[0];
        echo $path == null ? $images_path."default.jpg" : $path;
    }
    
    // Update profile image
    function update_image($image){
        global $sql,$images_path;
        $path = $images_path.$_SESSION['username'].".".explode(".",$image['name'])[1];
        move_uploaded_file($image['tmp_name'],$path);
        $sql->query("UPDATE users SET image='".$path."' WHERE username='".$_SESSION['username']."'");
    }

    // Initialize session cookie
    function init_session($username,$password){
        global $sql;
        $result = $sql->query("SELECT * FROM users WHERE username='".$username."'")->fetch_row();
        if (!empty($result)){
            $_SESSION['id'] = $result[0];
            $_SESSION['name'] = $result[1];
            $_SESSION['username'] = $result[2];
            $_SESSION['email'] = $result[3];
            $_SESSION['role'] = $result[4];
            return "success";
        }else{
            return "invalid username and/or password";
        }
    }
    
    // Check Password Strength and identicality
    function check_password($password,$repassword){
        if(strlen($password)<=8){
            return ["Password Should be more than 8 characters"];
        }else if(!preg_match("@[A-Z]@",$password) || !preg_match("@[a-z]@",$password)|| !preg_match("@[0-9]@",$password) || !preg_match("@[^\w]@",$password)) {
            return ["Passowrd should contain Uppercases, Lowercases, Digits, and Special Characters"];
        }else if($password!==$repassword){
            return ["Passwords aren't identical"];
        }else{
            return true;
        }
    }