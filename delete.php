<!-- Patryk Jagielski
September 29, 2021
Assignment 3 -->

<?php
    require('connect.php');
    // print_r(true?'yes':'no');
    print_r($_GET['postId']);
    if (!empty($_GET['postId']) && is_numeric($_GET['postId']) && filter_input(INPUT_GET, "postId", FILTER_SANITIZE_NUMBER_INT)) {

        $select_query = "DELETE FROM post WHERE postId = :postId LIMIT 1";

        //Prepare the Database Object with the query
        $fetch_statement = $db->prepare($select_query); // Returns a PDOStatement object.

        $fetch_statement->bindValue(':postId', $_GET["postId"], PDO::PARAM_INT);

        $fetch_statement->execute();

        $row = $fetch_statement->fetch();
        print_r($row);
        header('Location: index.php');
    }
    else {
        // print_r($row);
        // header('Location: index.php');
        exit("wrong");
    }
?>


