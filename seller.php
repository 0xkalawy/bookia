<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/seller.css">
    <title>Bookia - Seller Portal</title>
</head>

<?php
    require_once("./checklogin.php");
    require_once("./config.php");
    if(!isSeller()){
        header("Location: /bookia/");
        exit;
    }else{
?>
<body>
    <?php 
        require_once("./header.php");
        $books = $sql->prepare("SELECT * FROM books WHERE publisher=?");
        $books->bind_param('s',$_SESSION['username']);
        $books->execute();
        $books = $books->get_result()->fetch_all();
        if(empty($books)){
            echo "<h1>You Have No Books, Come on add one</h1>";
        }else{
            for($i = 0 ; $i < count($books) ; $i++){
    ?>
    <div>
        <img src="<?php echo $books['cover'] ?>">
    </div>
    <?php
            }
        }
    ?>
    <button onclick='document.getElementById("addBookDiv").style.display = "block";'>add</button>
</body>
<div id="addBookDiv" style="display: None">
    <form method="POST" onsubmit="addBook()" id="addBookForm">
        <label for="bookName">Book Name</label><input type="text" name="bookName">
        <label for="cover">Cover</label><input type="file" name="cover">
        <input type="submit" value="Upload">
    </form>
</div>
<script>
    function addBook(event){
        event.preventDefault();
        var xhr = new XMLHttpRequest();
        var data = new FormData(document.getElementById("addBookForm"))l
        xhr.open("POST","/addBook");
        xhr.send()
    }
</script>
<?php } ?>
</html>