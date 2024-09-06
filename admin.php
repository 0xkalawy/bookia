<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/admin.css">
    <title>Admin Portal</title>
</head>
    <?php
        require_once("./header.php");
        require_once("./config.php");
    ?>
<body>
<div id="body">
    <?php

    if($_SESSION['role']!=="admin"){
        echo("<h1>Access Denied</h1>");
    } else {
        if($_SERVER['REQUEST_METHOD'] == "GET"){
        
?>
    <div id="manageUsersDiv" style="display: none;">
        <?php
            $users = $sql->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
            if(count($users)>1){
                foreach($users as $user){
                    if($user['role'] !=='admin'){
        ?>
        <div>
            <img src="<?php echo file_exists($user['image']) ? $user['image'] : './images/default.jpg'; ?>" alt="User Image">
            <p><?php echo $user['name']; ?></p>
            <p><?php echo $user['username']; ?></p>
            <p><?php echo $user['email']; ?></p>
            <p><?php echo $user['role']; ?></p>
            <button onclick="del(<?php echo $user['id']?>,'users')">Delete</button>
        </div>
        <?php
                    }
                }
            }
        ?>
    </div>

    <div id="manageBooksDiv" style="display: none;">
        <?php
            $books = $sql->query("SELECT * FROM books")->fetch_all(MYSQLI_ASSOC);
            foreach($books as $book){
        ?>
        <div>
            <img src="<?php echo $book['cover']; ?>" alt="Book Cover">
            <p><?php echo $book['name']; ?></p>
            <p><?php echo $book['author']; ?></p>
            <p><?php echo $book['publisher']; ?></p>
            <p><?php echo $book['pages_number']." pages"; ?></p>
            <p><?php echo $book['price']."$"; ?></p>
            <p><?php echo $book['category']; ?></p>
            <button onclick="del(<?php echo $book['id']?>,'books')">Delete</button>
        </div>
        <?php
            }
        ?>
    </div>

    <button onclick='document.getElementById("manageBooksDiv").style.display="None"; document.getElementById("manageUsersDiv").style.display="block";'>Manage Users</button>
    <button onclick='document.getElementById("manageBooksDiv").style.display="block"; document.getElementById("manageUsersDiv").style.display="None"; '>Manage Books</button>
</div>
</body>
<?php
    } 
    else if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if($_SESSION['role']=='admin'){
            $type = $_POST['type'];
            $id = $_POST['id'];

            if ($type === 'users' || $type === 'books') {
                $query = $sql->prepare("SELECT * FROM $type WHERE id=?");
                $query->bind_param("d",$id);
                $query->execute();
                $result = $query->get_result()->fetch_row();
                if($type == 'users'){
                    unlink($result['image']);
                }else if($type == 'books'){
                    unlink($result['cover']);
                }
                $query = $sql->prepare("DELETE FROM $type WHERE id = ?");
                $query->bind_param("d", $id);
                if($query->execute()){
                    echo ("Deleted Done Successfully");
                    http_response_code(200);
                    exit;
                }
            }
        }else{
            http_response_code(403);
        }
    }
}
?>
<script>
    function del(id, type) {
        event.preventDefault();
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "./admin.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("type=" + type + "&id=" + id);
        xhr.onreadystatechange = function(){
            if(this.readyState==4){
                if(this.status==200){
                    window.location.reload();
                }
            }
        }
    }
</script>
</html>
