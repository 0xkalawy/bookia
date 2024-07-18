<?php
    session_start();
    function isLogged(){
        return !empty($_SESSION['email']);
    }
    function redirect_on_signin(){
        if(isLogged()){
            header("Location: /bookia/home.php?user=".$_SESSION['username']);
            exit;
        }
    }
    function redirect_on_logout(){
        if(!isLogged()){
            header("Location: /bookia/signin.php");
            exit;
        }
    }