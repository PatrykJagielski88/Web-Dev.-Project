<?php
    session_start();
    require('connect.php');
    require('authenticate.php');

    if ($_POST && filter_input(INPUT_POST, 'search', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $search=$_POST['search'];
        $query = $db->prepare("select * from post where Title LIKE '{$_POST['search']}' OR content LIKE '{$_POST['search']}'");
        $query->execute();
    }
    
?>


<html>
<head>
<title>Search functionality in dynamic website using php, MySQL | Technopoints</title>
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
<?php if (isset($_SESSION["loggedin"])):?>
    <?php if (!empty($query) && !$query->rowCount() == 0): ?>
        Your search <?= ($_POST['search']) ?> found :<br/> 
        <?php foreach($query as $row): ?> 
            
            <table class="blog_post">
            <h2><a href="show.php?postId=<?= $row["postId"] ?>"><?= $row['title'] ?></a></h2>
            PostId:<?= $row["postId"] ?></br>                    
            Date:<?= $row["date"] ?></br>
            
            <!-- Category name:<?= $row['categoryName'] ?>       </br> -->
            <div class='blog_content'>
                <!--  -->
                    <p>Content:<?= $row["content"] ?></p>          
                    <p>UserId:<?= $row["userId"] ?></p>
                    <a href="edit.php?postId=<?= $row["postId"] ?>">edit</a>
                <!--  -->
            </div>               
        <?php endforeach; ?> 
    <?php endif; ?>
<?php endif; ?>
</div>

</body>
</html>