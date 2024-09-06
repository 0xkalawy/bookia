<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/home.css">
    <title>Bookia - Home</title>
</head>
<body>
    <?php
        require_once('./header.php');
        require_once('./config.php');
        $books = $sql->prepare("SELECT * FROM books");
        $books->execute();
        $books = $books->get_result()->fetch_all(MYSQLI_ASSOC);
        if(empty($books)){
            echo "<h1>There is no books yet</h1>";
        }else{
            foreach($books as $book) {
    ?>
    <div class="book">
        <img src="<?php echo $book['cover']; ?>" alt="Book Cover">
        <p><?php echo $book['name']; ?></p>
        <p><?php echo $book['price']."$"; ?></p>
    </div>
    <?php
            }
        }
    ?>
</body>
</html>