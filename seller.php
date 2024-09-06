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
        require_once("./header.php");
        if($_SERVER['REQUEST_METHOD'] == "GET"){

?>
<body>
    <?php 
        $books = $sql->prepare("SELECT * FROM books WHERE publisher=?");
        $books->bind_param('s', $_SESSION['username']);
        $books->execute();
        $books = $books->get_result()->fetch_all(MYSQLI_ASSOC);
        if(empty($books)){
            echo "<h1>You Have No Books, Come on add one</h1>";
        }else{
            foreach($books as $book) {
    ?>
    <div class="book">
        <img src="<?php echo $book['cover']; ?>" alt="Book Cover">
        <p><?php echo $book['name']; ?></p>
    </div>
    <?php
            }
        }
    ?>
    <button onclick='document.getElementById("addBookDiv").style.display = "block";'>Add Book</button>
</body>

<div id="addBookDiv" style="display: none;">
    <form method="POST" enctype="multipart/form-data" id="addBookForm">
        <label for="author">Author</label>
        <input type="text" name="author" required>
        <label for="bookName">Book Name</label>
        <input type="text" name="bookName" required>
        <label for="category">Category</label>
        <input type="text" name="category" required>
        <label for="price">Price</label>
        <input type="number" name="price" required>
        <label for="pages_number">Pages Number</label>
        <input type="number" name="pages_number" required>
        <label for="cover">Cover</label>
        <input type="file" name="cover" required>
        <input type="submit" value="Upload">
    </form>
</div>

<script>
    function addBook(event){
        event.preventDefault();
        var xhr = new XMLHttpRequest();
        var data = new FormData(document.getElementById("addBookForm"));
        xhr.open("POST", "/bookia/seller.php");
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert("Book uploaded successfully!");
                location.reload();
            } else {
                alert("Error uploading book.");
            }
        };
        xhr.send(data);
    }
    document.getElementById("addBookForm").onsubmit = addBook;
</script>
<?php 
} 

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $target_dir = "./images/books_covers/";
    $target_file = $target_dir . basename($_FILES["cover"]["name"]);
    
    if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
        $query = $sql->prepare("INSERT INTO books(publisher, author, name, cover, category, price, pages_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param('sssssdi', $_SESSION['username'],$_POST['author'] ,$_POST['bookName'], $target_file, $_POST['category'], $_POST['price'], $_POST['pages_number']);
        $query->execute();
        exit;
    } else {
        echo "Error uploading the cover image.";
    }
}
} ?>
</html>
