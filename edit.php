<?php
  require('connect.php');
  require('authenticate.php');
  // I have the ID for the post that I need to get

  if (!empty($_GET['postId']) && is_numeric($_GET['postId']) && filter_input(INPUT_GET, "postId", FILTER_SANITIZE_NUMBER_INT)) {

    $select_query = "SELECT * FROM post WHERE postId = :postId LIMIT 1";

    // Prepare the Database Object with the query
    $fetch_statement = $db->prepare($select_query); // Returns a PDOStatement object.
  
    $fetch_statement->bindValue(':postId', $_GET["postId"], PDO::PARAM_INT);
  
    $fetch_statement->execute();
  
    $row = $fetch_statement->fetch();

   }
   else {
    header('Location: index.php');
    exit();
   }

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Post</title>
    <link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body>
    <div id="wrapper">
          <div id="header">
              <h1><a href="index.php">"Polish Pat's potatoe pancakes"</a></h1>
          </div> 
      <ul id="menu">
          <li><a href="index.php" >Home</a></li>
          <li><a href="create.php" >New Post</a></li>
      </ul> 
      <div id="all_blogs">
        <form action="update.php" method="post">
          <fieldset>
            <legend>Edit Blog Post</legend>
            <p>
              <label for="title">Title</label>
              <input name="title" id="title" value="<?= $row['title'] ?>" />
            </p>
            <p>
              <label for="content">Content</label>
              <textarea name="content" id="content"><?= $row['content'] ?></textarea>
            </p>
            <p>
              <input type="hidden" name="postId" value="<?= $row['postId'] ?>" />
              <input type="submit" name="command" value="Update" />      
              <input type="submit" formaction="delete.php?id=<?= $row['postId'] ?>" value="Delete">
            </p>
          </fieldset>
        </form>        
      </div>
          <div id="footer">
              Copywrong 2021 - No Rights Reserved
          </div> 
    </div> 
</body>
</html>

