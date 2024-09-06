<?php
    require_once("./config.php");
    redirect_on_logout();
    if(!isSeller()){
        header("Location: /bookia/home");
        exit;
    }
    switch($_REQUEST['action']){
        case 'add':
            $sql->query("INSERT INTO books(publisher,name,cover,category,price,pages_number) values({$_REQUEST['name']})");
            break;
        case 'delete':
            break;
        case 'modify':
            break;
    }