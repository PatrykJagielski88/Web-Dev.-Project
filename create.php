<!-- Patryk Jagielski
September 29, 2021
Assignment 3 -->

<?php
  require('connect.php');
  require('authenticate.php');
  // print_r($_SESSION);
  if($_POST && !empty($_POST['title']) && !empty($_POST['content']) && is_numeric($_POST['userId']) 
      && filter_input(INPUT_POST, "userId", FILTER_SANITIZE_NUMBER_INT) && is_numeric($_POST['categoryId']) 
      && filter_input(INPUT_POST, "categoryId", FILTER_SANITIZE_NUMBER_INT)) {
print_r($_POST);
    // First we need to sanitize our input first
    $sanitized_post = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // prepare the query that we'll want to use
    $insert_query = "INSERT INTO post (title, content, userId, categoryId) VALUES (:title, :content, :userId, :categoryId)";

    // Prepare the Database Object with the query
    $statement = $db->prepare($insert_query); // Returns a PDOStatement object.

    // bind our values to our placeholders
    $statement->bindValue(':title', $sanitized_post['title']);
    $statement->bindValue(':content', $sanitized_post['content']);
    $statement->bindValue(':userId', $sanitized_post['userId']);
    $statement->bindValue(':categoryId', $sanitized_post['categoryId']);

    // Finally execute the query
    $statement->execute(); // The query is now executed.

  }
  elseif ($_POST && empty($_POST['title']) && empty($_POST['content'])) {
    exit("The title and content can NOT be empty.");
  }
  
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Create post</title>
    <link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body>
  <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">"Polish Pat's potatoe pancakes"</a></h1>
        </div> 
    <ul id="menu">
        <li><a href="index.php" >Home</a></li>
        <li><a href="create.php" class='active'>New Post</a></li>
    </ul> 
    <div id="all_blogs">
      <form action="create.php" method="post">
        <fieldset>
          <legend>New Blog Post</legend>
          <p>
            <label for="title">Title</label>
            <input name="title" id="title" />
          </p>
          <p>
            <label for="userId">Please type in your user Id:</label>
            <input name="userId" id="userId" />
          </p>
          <p>
            <label for="content">Content</label>
            <textarea name="content" id="content"></textarea>
          </p>
          <p>
          <p>
            <label for="categoryId">Please choose the category:</label>
            <select name="categoryId" id="categoryId">
              <option value="1">Breakfast</option>
              <option value="2">Lunch</option>
              <option value="3">Dinner</option>
              <option value="4">Dessert</option>
          </p>
          <p>
            <input type="submit" name="command" value="Create" />
          </p>
        </fieldset>
      </form>
        <?php if (isset($statement) && $statement->rowCount() > 0): ?>
              <p class="success">New Post Created!</p>
        <?php endif; ?>
      </div>
          <div id="footer">
              Copywrong 2021 - No Rights Reserved
          </div> 
    </div>
</body>
</html>