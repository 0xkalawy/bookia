<?php
    require_once("./config.php");
    if($_SERVER['REQUEST_METHOD']=="POST"){
        session_start();
        $response = array();
        if(!empty($_FILES['image']['tmp_name'])){
            update_image($_FILES['image']);
            array_push($response,"Image Update Successfully");
        }if(!empty($_REQUEST['password'])){
            $check_pass = check_password($_REQUEST['password'],$_REQUEST['repassword']);
            if($check_pass!== true){
                array_push($response,$check_pass);
            }else{
                $sql->query("UPDATE users SET password='".$_REQUEST['password']."' WHERE username='".$_SESSION['username']."'");
                array_push($response, "Password Updated Correctly");
            }
        }if($_REQUEST['username']!==$_SESSION['username']){
            if (empty($sql->query("SELECT * FROM users WHERE username='".$_REQUEST['username']."'")->fetch_row())){
                $sql->query("UPDATE users SET username='".$_REQUEST['username']."' WHERE email='".$_SESSION['email']."'");
                $_SESSION['username'] = $_REQUEST['username'];
                array_push($response,"Username Updated Successfully");
            }else{
                array_push($response,"Username is taken by another user");
            }
        }if($_REQUEST['name']!==$_SESSION['name']){
            $sql->query("UPDATE users SET name='".$_REQUEST['name']."' WHERE email='".$_SESSION['email']."'");
            $_SESSION['name'] = $_REQUEST['name'];
            array_push($response,"Name Updated Successfully");
        }if($_REQUEST['email']!==$_SESSION['email']){
            if (empty($sql->query("SELECT * FROM users WHERE email='".$_REQUEST['email']."'")->fetch_row())){
                $sql->query("UPDATE users SET email='".$_REQUEST['email']."' WHERE username='".$_SESSION['username']."'");
                $_SESSION['email'] = $_REQUEST['email'];
                array_push($response,"Email Updated Successfully");
            }else{
                array_push($response,"Email is already exist");
            }
        }
        echo !empty($response) ? json_encode(["message"=>implode("<br>",$response)]):null;
    }