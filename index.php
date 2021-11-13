<?php
    
    // if ($_SESSION !== true){
         session_start();
    // }
    require('connect.php');
print_r(session_status());
    $query = "SELECT * FROM post p, category c WHERE c.categoryId = p.categoryId ORDER BY p.postId DESC";
    $statement = $db->prepare($query); // Returns a PDOStatement object.
    $statement->execute(); // The query is now executed.
    $result = $statement->fetchAll();
    // print_r($row['categoryId']);
    
        //print_r($row['categoryId']);
    // $query2 = "SELECT * FROM category where categoryId = `{$row['categoryId']}`";
    // $statement2 = $db->prepare($query2); // Returns a PDOStatement object.
    // $statement2->execute(); // The query is now executed.
    
    // $row2 = $statement2->fetch();
    //   print_r($row2);
?>
 
 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <link rel="stylesheet" href="styles.css" type="text/css">
     <title>Document</title>
 </head>
 <body>     
 <div id="wrapper">
    <?php if (isset($_SESSION["loggedin"])):?>
        You are logged in as <?= $_SESSION['username'] ?>, userid <?= $_SESSION['userId'] ?>.</br>
    <?php endif; ?>
    <a href="signup.php">Signup</a>
    <a href="login.php" >Login</a><br/>
    <a href="logout.php">Sign Out of Your Account</a>    
    <div id="header">
        <h1><a href="index.php">"Polish Pat's potatoe pancakes"</a></h1>
    </div> 
    <ul id="menu">
        <li><a href="index.php" class='active'>Home</a></li>
        <li><a href="create.php" >New Post</a></li>
    </ul>
    <div id="all_blogs">
        <?php foreach($result as $row): ?>               
            <div class="blog_post">            
                <!-- <?=print_r($row['commentContent']);?> -->
                <h2><a href="show.php?postId=<?= $row["postId"] ?>"><?= $row['title'] ?></a></h2>
                    PostId:<?= $row["postId"] ?></br>                    
                    Date:<?= $row["date"] ?></br>
                    Category name:<?= $row['categoryName'] ?>
                </div>  
                <div class='blog_content'>
                    <p>Content:<?= $row["content"] ?></p>
                
                    <p>UserId:<?= $row["userId"] ?></p>
                    <a href="edit.php?postId=<?= $row["postId"] ?>">edit</a>
                </div>                       
        <?php endforeach; ?>  
    </div> 
        <div id="footer">
            Copywrong 2021 - No Rights Reserved
        </div>         
  </div>
 </body>
 </html>