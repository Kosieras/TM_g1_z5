<?php
//Pobranie zmiennych
session_start();
$dirSession = $_SESSION['dirSession'];
$title = $_POST["title"];
$songName = basename($_FILES["fileToUpload"]["name"]);
$lyrics = $_POST["lyrics"];
$musician = $_POST["musician"];
$userid = $_SESSION["userid"];
$user_idmt = $_POST["user_idmt"];
$target_file = "songs/". basename($_FILES["fileToUpload"]["name"]); 
  $connection = mysqli_connect('localhost', 'kosierap_z5', 'Laboratorium123', 'kosierap_z5');
if (!$connection)
{
echo " MySQL Connection error." . PHP_EOL;
echo "Error: " . mysqli_connect_errno() . PHP_EOL; echo "Error: " . mysqli_connect_error() . PHP_EOL; exit;
}
else {
	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){ 
  $result = mysqli_query($connection, "INSERT INTO song (title, musician, idu, filename, lyrics, idmt) VALUES ('$title','$musician','$userid','$songName','$lyrics','$user_idmt');") or die ("DB error 2:  $connection->error);");
   echo "Uploading...\n";

  header('Refresh: 2; URL=myspotify.php');
} 
else echo "Error uploading file.";
}
?>
