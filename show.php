<!-- Patryk Jagielski
September 29, 2021
Assignment 3 -->

<?php
    session_start();

    require('connect.php');
    // I have the ID for the post that I need to get
print_r($_GET);
    if (!empty($_GET['postId']) && is_numeric($_GET['postId']) && filter_input(INPUT_GET, "postId", FILTER_SANITIZE_NUMBER_INT)) {

        //$sanitized_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        $select_query = "SELECT * FROM post WHERE postId = :postId LIMIT 1";

        // Prepare the Database Object with the query
        $fetch_statement = $db->prepare($select_query); // Returns a PDOStatement object.

        $fetch_statement->bindValue(':postId', $_GET["postId"], PDO::PARAM_INT);

        $fetch_statement->execute();
    
        $row = $fetch_statement->fetch();

        $select_query2 = "SELECT * FROM images WHERE postId = :postId LIMIT 1";

        // Prepare the Database Object with the query
        $fetch_statement2 = $db->prepare($select_query2); // Returns a PDOStatement object.

        $fetch_statement2->bindValue(':postId', $_GET["postId"], PDO::PARAM_INT);

        $fetch_statement2->execute();
    
        $row2 = $fetch_statement2->fetch();
    }
    else {
        header('Location: index.php');
        exit();
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css" type="text/css">
    <title>Show post</title>
</head>
<body>
    <div id="wrapper">
    <?php if (isset($_SESSION["loggedin"])):?>
        <?php include('search.php'); ?>
        You are logged in as <?= $_SESSION['username'] ?>, userid <?= $_SESSION['userId'] ?>.</br>
    <?php endif; ?>
        <div id="header">
            <h1><a href="index.php">"Polish Pat's potatoe pancakes" Recipe for: <?= $row['title'] ?></a></h1>
        </div> 
        <ul id="menu">
            <li><a href="index.php" >Home</a></li>
            <li><a href="create.php" >New Post</a></li>
        </ul>    
        <div id="all_blogs">
            <div class="blog_post">
                <h2><?= $row['title'] ?></h2>
                <p>
                    <small>
                    <?= date_format(date_create($row['date']), 'F d, Y, h:i a') ?>
                    <a href="edit.php?postId=<?= $row['postId'] ?>">edit</a>
                    </small>
                </p>
                <div class='blog_content'>
                    <?= $row['content'] ?>
                </div>
                <?php if(isset($row2['imageId'])): ?>
                    Image:<img src="uploads/<?= $row2["imageName"] ?>" alt="">
                <?php endif; ?>
            </div>
        </div>
        <div id="footer">
            Copywrong 2021 - No Rights Reserved
        </div> 
    </div>
</body>
</html>