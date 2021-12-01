<?php
require('connect.php');
?>


<html>
<head>
<title>Search functionality in dynamic website using php, MySQL | Technopoints</title>
<link rel="stylesheet" href="css/w3.css">
</head>
<body>
<div class="w3-panel w3-pink">
  <h1 class="w3-opacity">
  <b>Search Functionality using php, MySQL</b></h1>
</div>
<?php 
//load database connection
    // $host = "localhost";
    // $user = "root";
    // $password = "";
    // $database_name = "search";
    // $db = new PDO("mysql:host=$host;dbname=$database_name", $user, $password, array(
    // PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    // ));
// Search from MySQL database table
$search=$_POST['search'];
$query = $db->prepare("select * from post where Title LIKE '{$_POST['search']}' OR content LIKE '{$_POST['search']}'");
//$query->bindValue("%$search%", PDO::PARAM_STR);
$query->execute();

print_r($_POST['search']);
// Display search result
 
         if (!$query->rowCount() == 0) {
		 		echo "Search found :<br/>";
				echo "<table class='w3-table-all'>";	
                echo "<tr class='w3-red'><td>Employee name</td><td>Email</td><td>Company</td></tr>";				
            while ($results = $query->fetch()) {
				echo "<tr><td>";			
                echo $results['title'];
				echo "</td><td>";
                echo $results['postId'];
				echo "</td><td>";
                echo $results['content'];
				echo "</td></tr>";				
            }
				echo "</table>";		
        } else {
            echo 'No results found!';
        }
?>
</div>

</body>
</html>