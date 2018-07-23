<?php

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

$sql = "SELECT message, author FROM message";
$result = $conn->query($sql);
$fh = fopen("output.txt", 'a');
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
       fwrite($fh, str_replace("&#039;", "'", htmlspecialchars_decode("----\n". $row["message"]))."\n\n\n".
               str_replace("&#039;", "'", htmlspecialchars_decode($row["author"])). "\n");
    }
}
fclose($fh);
$conn->close();
