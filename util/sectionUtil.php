<?php
include("db.php");

if($_SERVER['REQUEST_METHOD'] === "GET") {
	if(!isset($_GET['Category'])) { // No category requested
		return; // TODO: Error reporting to web-side
	}
	// TODO: Need more test articles published to see if this offset feature really works
	if(isset($_GET['offset'])) {
		$sql = "SELECT * FROM Article WHERE IsPublished = 1 AND IsDraft = 0 AND Category='" . mysqli_real_escape_string($db, $_GET['Category']) . "' AND PublishDate > ".date('Y-m-d H:i:s', $_GET['offset'])." ORDER BY PublishDate DESC LIMIT 3";
	} else {
		$sql = "SELECT * FROM Article WHERE IsPublished = 1 AND IsDraft = 0 AND Category='" . mysqli_real_escape_string($db, $_GET['Category']) . "' ORDER BY PublishDate DESC LIMIT 3";
	}

} else { return; } // Reject all other request types

$result = mysqli_query($db, $sql);
if(!$result || mysqli_num_rows($result) == 0) { // No rows returned
	return; //TODO: Error reporting to web-side
}

$data = array();
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $data[] = $row;
}
print json_encode($data);
?>