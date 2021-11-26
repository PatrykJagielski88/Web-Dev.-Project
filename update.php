<!-- Patryk Jagielski
September 29, 2021
Assignment 3 -->

<!-- <?php
  require('connect.php');
  print_r($_POST);
  if($_POST && !empty($_POST['title']) && !empty($_POST['content'])&& is_numeric($_POST['postId']) 
  && filter_input(INPUT_POST, "postId", FILTER_SANITIZE_NUMBER_INT)) {

    // First we need to sanitize our input first
    $sanitized_post = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // prepare the query that we'll want to use
    $insert_query = "UPDATE post SET title=:title, content=:content WHERE postId=:postId LIMIT 1";

    // Prepare the Database Object with the query
    $statement = $db->prepare($insert_query); // Returns a PDOStatement object.

    // bind our values to our placeholders
    $statement->bindValue(':title', $sanitized_post['title']);
    $statement->bindValue(':content', $sanitized_post['content']);
    $statement->bindValue(':postId', $sanitized_post['postId']);
    // $statement->bindValue(':categoryId', $sanitized_post['categoryId']);

    // Finally execute the query
    $statement->execute(); // The query is now executed.

    if ($statement->rowCount() > 0) {
        header('Location: index.php');
        exit();
    }
  }
  else {
    exit("The title and content can NOT be emply.");
  }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Insert post</title>
    <link rel="stylesheet" href="styles.css" type="text/css">
</head>
  <body>
    <?php header('Location: index.php') ?>
  </body>
</html>  -->