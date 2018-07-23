<?php
require_once __DIR__ . '/www/html/vendor/autoload.php';

// add db connection details
$servername = "localhost";
$username = "root";
$password = "super-secret-password";
$dbname = "farewell_peter";

define('APPLICATION_NAME', 'Google Sheets API PHP Quickstart');
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/sheets.googleapis.com-php-quickstart.json
define('SCOPES', implode(' ', array(
				Google_Service_Sheets::SPREADSHEETS_READONLY)
			));

if (php_sapi_name() != 'cli') {
	die('Error: 404');
}

// Get the API client and construct the service object.
putenv('GOOGLE_APPLICATION_CREDENTIALS=/home/ubuntu/farewellpeter.json');
$client = new Google_Client();
$client->setApplicationName(APPLICATION_NAME);
$client->setScopes(SCOPES);
$client->useApplicationDefaultCredentials();
$service = new Google_Service_Sheets($client);

try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//get how many messages
	$sql = "select count(author) from message";

	$numOfMessages = 2;
	foreach ($conn->query($sql) as $row) {
		// output data of each row
		if ($row["count(author)"] > 2){
		$numOfMessages = $row["count(author)"];
		}
	}

	$spreadsheetId = '1Q1wgu7Duc8ngd9O8oSGXV8H2EQlUmKuL2E4osH4Dw9Q';
	$range = 'Form responses 1!A'. $numOfMessages. ':C';
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$values = $response->getValues();

	if (count($values) == 0) {
		print "No data found.\n";
	} else {
		// prepare sql and bind parameters
		$stmt = $conn->prepare("INSERT INTO message (date, author, message)
				VALUES (:date, :author, :message)");
		$stmt->bindParam(':date', $date);
		$stmt->bindParam(':author', $author);
		$stmt->bindParam(':message', $message);

		$iterator = 0;

		foreach ($values as $row) {
			//insert each row of spread sheet data
			if ($row[0] != "")
			{
				$author = htmlspecialchars(trim($row[1]), ENT_QUOTES);
				$search = array("principle", "Principle");
				$replace = array("principal", "Principal");
				$message = str_replace($search, $replace, htmlspecialchars(trim($row[2]), ENT_QUOTES));

				$sql = "select * from message where author = '". $author.
					"' AND message = '". $message. "'";
				echo $sql. "\n";
				if ($conn->query($sql)->rowCount() == 0)
				{
					$stamp = explode(" ", $row[0]);
					$stampDate = explode("/", $stamp[0]);
					$time = $stampDate[2]. "-". $stampDate[1]. "-". $stampDate[0]. " ". $stamp[1];
					$stmt->execute();
				}
			}
		}
		echo "New records created successfully\n";
	}

} catch(PDOException $e) {
	echo "Error: " . $e->getMessage();
}
//close db
$conn = null;
