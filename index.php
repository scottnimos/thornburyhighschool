<?php
require_once __DIR__.'/vendor/autoload.php';

// add db connection details
$servername = "localhost";
$username = "root";
$password = "super-secret-password";
$dbname = "farewell_peter";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<title>Thankyou Peter Egeberg</title>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css" rel="stylesheet">
	<!--<link href="./css/toast.css" rel="stylesheet">-->
	<link href="./css/style.css" rel="stylesheet">
	<meta content="width=device-width" name="viewport">
</head>
<body>

	<h1>Hi Peter.</h1>
	<p class="about">
		You are an amazing human being.<br/>Thankyou for your service,<br/>here we curated personal messages from THS and the community.
	</p>
	<a href="https://goo.gl/forms/H7W1zFE0bw3XIWLn2" class="post">Post Your Message</a>
	<div class="container">

   <?php
   $next = true;
   $page = 0;
   if (isset($_GET["page"])) {
      $page =  $_GET["page"];
      ($page > 0)? true: $page = 0;
   }

   $sql = "SELECT message, author FROM message ORDER BY id DESC LIMIT 10 OFFSET ". $page*10;
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
       // output data of each row
       while($row = $result->fetch_assoc()) {
          echo "<div class=\"grid__col--1-of-2 grid__col inc-margin\"><p>";
          echo($row["message"]. "</p><span>". $row["author"]. "</span></div>");
       }
   } else {
       $next = false;
   }
   $conn->close();

   echo "<div class=\"clearfix\">";
   if ($page != 0)
      echo "<a href=\"index.php?page=". ($page -1). "\" class=\"next\">Previous</a>";
   if ($next)
      echo "<a href=\"index.php?page=". ($page + 1). "\" class=\"next\">Next</a>";
   ?>
   </div>
  </div>
  <footer>
      Made with &hearts; by <a href="http://scottnimos.com">Scott Nimos</a>
  </footer>
</body>
</html>
