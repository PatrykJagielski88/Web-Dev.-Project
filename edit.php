<?php
  session_start();

  require('connect.php');
  require('authenticate.php');

  include ('ImageResize.php');
  include ('ImageResizeException.php');

  use \Gumlet\ImageResize;
  // I have the ID for the post that I need to get

  if (!empty($_GET['postId']) && is_numeric($_GET['postId']) && filter_input(INPUT_GET, "postId", FILTER_SANITIZE_NUMBER_INT)) {

    $select_query = "SELECT * FROM post WHERE postId = :postId LIMIT 1";

    // Prepare the Database Object with the query
    $fetch_statement = $db->prepare($select_query); // Returns a PDOStatement object.
  
    $fetch_statement->bindValue(':postId', $_GET["postId"], PDO::PARAM_INT);
  
    $fetch_statement->execute();
  
    $row = $fetch_statement->fetch();
    

    $query2 = "SELECT * FROM images where postId = {$_GET['postId']} LIMIT 1";
    $statement2 = $db->prepare($query2); // Returns a PDOStatement object.
    $statement2->execute(); // The query is now executed.
    
    $result2 = $statement2->fetch();
}

if ($_POST && isset($_POST['to_delete']) && $_POST['to_delete'] === 'delete' && is_numeric($_POST['postId']) && filter_input(INPUT_POST, "postId", FILTER_SANITIZE_NUMBER_INT)) {
  
  $query3 = "SELECT * FROM images where postId = {$_POST['postId']} LIMIT 1";
  $statement3 = $db->prepare($query3); // Returns a PDOStatement object.
  $statement3->execute(); // The query is now executed.
  
  $result3 = $statement3->fetch();

  if (!empty($result3)) {
    $filePath = "C:\\xampp\htdocs\wd2\Project\uploads\\{$result3['imageName']}";

    $deleted = unlink($filePath);

    $delete_query = "DELETE FROM images WHERE postId = :postId LIMIT 1";

    //Prepare the Database Object with the query
    $fetch_statement2 = $db->prepare($delete_query); // Returns a PDOStatement object.

    $fetch_statement2->bindValue(':postId', $_POST["postId"], PDO::PARAM_INT);

    $fetch_statement2->execute();

    $row = $fetch_statement2->fetch();
  }  
}

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

}
elseif ($_POST && empty($_POST['title']) && empty($_POST['content'])) {
    exit("The title and content can NOT be empty.");
}

 //print_r($result2['imageName']);


// file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
// Default upload path is an 'uploads' sub-folder in the current folder.
function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
   $current_folder = dirname(__FILE__);
   
   // Build an array of paths segment names to be joins using OS specific slashes.
   $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
   
   // The DIRECTORY_SEPARATOR constant is OS specific.
   return join(DIRECTORY_SEPARATOR, $path_segments);
}
// print_r("current folder".$current_folder);
// file_is_valid() - Checks the mime-type & extension of the uploaded file for "image-ness".
function file_is_valid($temporary_path, $new_path) {
    $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
    $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
    
    $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
    $actual_mime_type        = mime_content_type($temporary_path);
    
    $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
    $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
    
    return $file_extension_is_valid && $mime_type_is_valid;
}

$upload_detected = isset($_FILES['uploaded_file']) && ($_FILES['uploaded_file']['error'] === 0);
$upload_error_detected = isset($_FILES['uploaded_file']) && ($_FILES['uploaded_file']['error'] > 0);

if ($upload_detected) { 
    $filename        = $_FILES['uploaded_file']['name'];
    $temporary_path  = $_FILES['uploaded_file']['tmp_name'];
    $new_path        = file_upload_path($filename);
    
    if ($is_valid = file_is_valid($temporary_path, $new_path)) { 
        $actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);
// print_r($is_valid ? 'true' : 'not true');
        $path = basename($new_path,$actual_file_extension);
// print_r("path ".$path);

        move_uploaded_file($temporary_path, $new_path);
        
        $actual_file_name = pathinfo($new_path, PATHINFO_FILENAME);
        if ($actual_file_extension !== 'pdf') {
            $image_medium = new ImageResize("{$new_path}");
            $image_medium->resizeToLongSide(250);
            $image_medium->save('uploads'.DIRECTORY_SEPARATOR."{$actual_file_name}.{$actual_file_extension}");

        //     $image_thumbnail = new ImageResize("{$new_path}");
        //     $image_thumbnail->resizeToLongSide(50);
        //     $image_thumbnail->save('uploads'.DIRECTORY_SEPARATOR."{$actual_file_name}_thumbnail.{$actual_file_extension}");

          //rename('uploads'.DIRECTORY_SEPARATOR."{$actual_file_name}.{$actual_file_extension}", 'uploads'.DIRECTORY_SEPARATOR."{$actual_file_name}_original.{$actual_file_extension}");
          //rename('uploads'.DIRECTORY_SEPARATOR."{$actual_file_name}_medium.{$actual_file_extension}", 'uploads'.DIRECTORY_SEPARATOR."{$actual_file_name}.{$actual_file_extension}");
        }                    
    }
}

if ($_POST && isset($_POST['submit']) && $_POST['submit'] == 'Upload Image' && isset($is_valid) && $is_valid
    && filter_var($_FILES['uploaded_file']['name'], FILTER_SANITIZE_STRING) && filter_var($_POST["postId"], FILTER_VALIDATE_INT)) {

    $insert_query2 = "INSERT INTO images (imageName, postId) VALUES (:imageName, :postId)";

    // Prepare the Database Object with the query
    $statement2 = $db->prepare($insert_query2); // Returns a PDOStatement object.
  
    // bind our values to our placeholders
    $statement2->bindValue(':imageName', $_FILES['uploaded_file']['name'] );
    $statement2->bindValue(':postId', $_POST["postId"]);
  
    // Finally execute the query
    $statement2->execute(); // The query is now executed.
  }

//print_r($_POST['submit']);

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
    <?php if (isset($_SESSION["loggedin"])):?>
        <?php include('search.php'); ?>
        You are logged in as <?= $_SESSION['username'] ?>, userid <?= $_SESSION['userId'] ?>.</br>
    <?php endif; ?>
          <div id="header">
              <h1><a href="index.php">"Polish Pat's potatoe pancakes"</a></h1>
          </div> 
      <ul id="menu">
          <li><a href="index.php" >Home</a></li>
          <li><a href="create.php" >New Post</a></li>
      </ul> 
      <div id="all_blogs">
        <form action="edit.php" method="post" enctype='multipart/form-data'>
          <fieldset>
            <legend>Edit Blog Post</legend>
            <p>
              <label for="title">Title</label>
              <?php if (isset($row['title'])): ?>
                <input name="title" id="title" value="<?= $row['title'] ?>" />
              <?php else: ?>
                <input name="title" id="title" value="" />
              <?php endif; ?>
            </p>
            <p>
              <label for="content">Content</label>
              <?php if (isset($row['content'])): ?>
                <textarea name="content" id="content"><?= $row['content'] ?></textarea>
              <?php else: ?>
                <textarea name="content" id="content" value="" ></textarea>
              <?php endif; ?>
            </p>
            <p>
              <input type="hidden" name="postId" value="<?= $row['postId'] ?>" />
              <input type="submit" name="command" value="Update" />      
              <input type="submit" formaction="delete.php?postId=<?= $row['postId'] ?>" value="Delete">
            </p>
          </fieldset>
          <label for='uploaded_file'>Image Filename:</label>
          <input type='file' name='uploaded_file' id='uploaded_file'>
          <input type='submit' name='submit' value='Upload Image'></br></br>
          <?php if(!empty($result2)): ?>
            <label for="delete">Check this checkbox and update your post </br> to delete the image associated with this post.</label>
            <input type="checkbox" id="delete" name="to_delete" value="delete">            
          <?php endif; ?>
        </form>        
        <?php if (isset($is_valid) && !$is_valid): ?>
          Sorry but the file must be a jpg, jpeg or png.</br>
          If your post was successfully updated you can always add an image later.
        <?php endif; ?>
        <?php if ($upload_error_detected && $_FILES['uploaded_file']['error'] !== 4): ?>
          <p>Error Number: <?= $_FILES['uploaded_file']['error'] ?></p>
        <?php elseif ($upload_detected): ?>
          <p>Client-Side Filename: <?= $_FILES['uploaded_file']['name'] ?></p>
          <!-- <p>Apparent Mime Type:   <?= $_FILES['uploaded_file']['type'] ?></p>
          <p>Size in Bytes:        <?= $_FILES['uploaded_file']['size'] ?></p>
          <p>Temporary Path:       <?= $_FILES['uploaded_file']['tmp_name'] ?></p> -->
        <?php endif ?>
        <?php if (isset($statement) && $statement->rowCount() >= 0): ?>
        <?php if ($upload_error_detected && $_FILES['uploaded_file']['error'] !== 4): ?>
          <p>Error Number: <?= $_FILES['uploaded_file']['error'] ?></p>
        <?php endif ?>
        <p class="success">Post Updated!</p>
      <?php endif; ?>
      </div>
          <div id="footer">
              Copywrong 2021 - No Rights Reserved
          </div> 
    </div> 
</body>
</html>

