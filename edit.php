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
    

  

}
if (isset($_POST)) {
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

    // if ($statement->rowCount() >= 0) {
    //   if (isset($is_valid) && !$is_valid) {
    //     $message = 'Sorry but the file must be a jpg, jpeg or png.</br>
    //               If your post was successfully created you can always update it with an image later.';
    //   }
    //   else {
    //     header('Location: index.php');
    //     exit();
    //   }
        
    // }
  }
  elseif ($_POST && empty($_POST['title']) && empty($_POST['content'])) {
    exit("The title and content can NOT be empty.");
  }

  if ($_POST && isset($_POST['submit']) && $_POST['submit'] == 'Upload Image') {

       
    // $query2 = "SELECT LAST_INSERT_ID()";
    // $statement2 = $db->prepare($query2); // Returns a PDOStatement object.
    // $statement2->execute(); // The query is now executed.
    
    // $row2 = $statement2->fetch();
    // print_r($row2);
 
     $insert_query2 = "INSERT INTO images (imageName, postId) VALUES (:imageName, :postId)";
 
     // Prepare the Database Object with the query
     $statement2 = $db->prepare($insert_query2); // Returns a PDOStatement object.
   
     // bind our values to our placeholders
     $statement2->bindValue(':imageName', $_FILES['uploaded_file']['name'] );
     $statement2->bindValue(':postId', $_POST["postId"]);
   
     // Finally execute the query
     $statement2->execute(); // The query is now executed.
 
   }
}

  print_r('hellolsdkjhs');
 print_r($_POST);







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
    print_r($filename);
    if ($is_valid = file_is_valid($temporary_path, $new_path)) { 
        $actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);

        $path = basename($new_path,$actual_file_extension);
print_r("path ".$path);

        move_uploaded_file($temporary_path, $new_path);
        
        $actual_file_name = pathinfo($new_path, PATHINFO_FILENAME);
        if ($actual_file_extension !== 'pdf') {
            $image_medium = new ImageResize("{$new_path}");
            $image_medium->resizeToLongSide(150);
            $image_medium->save('uploads'.DIRECTORY_SEPARATOR."{$actual_file_name}_medium.{$actual_file_extension}");

        //     $image_thumbnail = new ImageResize("{$new_path}");
        //     $image_thumbnail->resizeToLongSide(50);
        //     $image_thumbnail->save('uploads'.DIRECTORY_SEPARATOR."{$actual_file_name}_thumbnail.{$actual_file_extension}");

          rename('uploads'.DIRECTORY_SEPARATOR."{$actual_file_name}.{$actual_file_extension}", 'uploads'.DIRECTORY_SEPARATOR."{$actual_file_name}_original.{$actual_file_extension}");
          rename('uploads'.DIRECTORY_SEPARATOR."{$actual_file_name}_medium.{$actual_file_extension}", 'uploads'.DIRECTORY_SEPARATOR."{$actual_file_name}.{$actual_file_extension}");
        }                    
    }
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
              <textarea name="content" id="content"><?= $row['content'] ?></textarea>
            </p>
            <p>
              <input type="hidden" name="postId" value="<?= $row['postId'] ?>" />
              <input type="submit" name="command" value="Update" />      
              <input type="submit" formaction="delete.php?postId=<?= $row['postId'] ?>" value="Delete">
            </p>
          </fieldset>
          <label for='uploaded_file'>Image Filename:</label>
          <input type='file' name='uploaded_file' id='uploaded_file'>
          <input type='submit' name='submit' value='Upload Image'>
        </form>        
        <?php if (isset($is_valid) && !$is_valid): ?>
          
        <?php endif; ?>
        <?php if ($upload_error_detected && $_FILES['uploaded_file']['error'] !== 4): ?>
          <p>Error Number: <?= $_FILES['uploaded_file']['error'] ?></p>
        <?php elseif ($upload_detected): ?>
          <p>Client-Side Filename: <?= $_FILES['uploaded_file']['name'] ?></p>
          <p>Apparent Mime Type:   <?= $_FILES['uploaded_file']['type'] ?></p>
          <p>Size in Bytes:        <?= $_FILES['uploaded_file']['size'] ?></p>
          <p>Temporary Path:       <?= $_FILES['uploaded_file']['tmp_name'] ?></p>
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

