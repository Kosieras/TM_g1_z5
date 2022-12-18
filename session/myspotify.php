<?php 
//Spradzanie sesji
declare(strict_types=1);
session_start();

if (!isset($_SESSION['loggedin'])) //Jezeli nie ma sesji
{
  	header('Location: index3.php'); //Powrot do panelu logowania
	exit(); 
}
else{
  $connection = mysqli_connect('localhost', 'kosierap_z5', 'Laboratorium123', 'kosierap_z5');
  	if (!$connection){
		echo " MySQL Connection error." . PHP_EOL;
		echo "Errno: " . mysqli_connect_errno() . PHP_EOL; echo "Error: " . mysqli_connect_error() . PHP_EOL; exit;
	}
        $findUser = mysqli_query($connection, "Select * from user") or die ("DB error 2: $dbname");
 while ($row = mysqli_fetch_array ($findUser)) if($row[1] == $_SESSION['login'])  $_SESSION['userid'] = $row[0];
  ?>
    <!-- Prosty CSS -->
<html>
  <head>
<style>
  body {
  
  }
  .tdFile{
   min-width:200px; 
  }
  .imgDel, .addB {
   max-width:25px; 
    float: left;
  }
  label > img {
    max-width:50px;
  }
  form {
   float:left; 
     display:flex; 
   
  }
  .up{
   margin-top:50px;
  }
  img,audio,video {

  }
  
input[type=submit] {
  background-color: white;
  color: black;
  border: 2px solid #555555;
}

input[type=submit]:hover {
  background-color: #555555;
  color: white;
}
  .tab {
   float:left; 
    margin-left:10%;
  }
  table {
    float:left; 
    margin-right:2%;
  }
.post{
  width: 50%;
  padding: 12px 20px;
  margin: 8px 0;
  box-sizing: border-box;
  border: 3px solid #ccc;
  -webkit-transition: 0.5s;
  transition: 0.5s;
  outline: none;
}

.post:focus {
  border: 3px solid #555;
}
  .user, .post:disabled {
  width: 10%;
  color:red;
  padding: 12px 20px;
  margin: 8px 0;
  outline: none;
}
</style>  
  </head>
  <body>

<br> 
    <!--Przycisk do wylogowania sie -->
   <form action="logout.php"><br> <input type="submit" value="LOGOUT"/></form><br><br><br>
    <!--Przycisk do odswiezenia strony -->
    <label for="refresh">
    	<img src="./icons/refresh.png"/>
   		<button id="refresh" name="refresh" onClick="window.location.reload();" style="display:none"></button>
    </label>
    <!--Komunikat o zalogowanym uzytkowniku -->
    <br>Zalogowano jako:<input type="text" class="user" id="user" name="user" maxlength="10" size="10" value="<?php   echo $_SESSION['login'];?>" readonly><br><br><br>
    <!--Dodanie utworu -->
	<label id="aF" class="af" for="addFolder">
   		<img src="./icons/add.png"/>
     	<input type="checkbox" id="addFolder" name="addFolder" value="value1" style="display:none">
	</label>	
    <form name="formAdd" id="formAdd" method="POST" action="upload.php" enctype="multipart/form-data"> <div class="up" id="up" style="visiblity:hidden; position:absolute;">
    	<input type="text" name="title" id="title" value="Tytul"><br>
      	<input type="text" name="musician" id="musician" value="Wykonawca"><br>
      	<input type="text" name="lyrics" id="lyrics" value="Tekst utworu" style="line-height: 4em;"><br>
        <select name="user_idmt"><?php
    		$odbiorca = mysqli_query($connection, "Select * from musictype") or die ("DB error: $dbname");
    		while ($row = mysqli_fetch_array ($odbiorca)){
    		$user_option = $row[1];
    		$user_idmt = $row[0];
   			print "<option value='$user_idmt'>$user_option</option>\n";   
			}?>
  		</select><br>
     	<input type="file" name="fileToUpload" id="fileToUpload" accept="audio/*"><br>
    	<input type="submit" id="createFolder" name="createFolder" value="Add"/></div> </form>
     <!--Przycisk dodania nowej playlisty-->
   <label id="playlist" for="addPlaylist">
     <img src="./icons/addFolder.png"/>
     <input type="checkbox" id="addPlaylist" name="addPlaylist" value="value1" style="display:none">
  </label>
    <!--Przycisk do powrotu -->
    <form method="POST" action="myspotify.php" enctype="multipart/form-data">
  	<label id="labelBack" for="back">
    	<img src="./icons/return.png"/>
    	<input type="submit" name="back" id="back" style="display:none">
  	</label>
    </form>
    <!--Pole dodawania playlisty -->
	<div id="playlistDiv" style="visiblity:hidden; position:absolute;">
	<form id="aP" class="aP" method="POST" action="myspotify.php" enctype="multipart/form-data">
    <input type="text" name="playlistName" id="playlistName" value="Playlista">
      Publiczna: <input type="checkbox" id="public" name="public" value="1">
    <input type="submit" id="createPlaylist" name="createPlaylist" value="Create"/></form></div>  
       <!--Dodanie utworu do playlisty -->
    <label id="addToLabel" for="addToPlaylist">
     	<img src="./icons/addB.png"/>
     	<input type="checkbox" id="addToPlaylist" name="addPlaylist" value="value1" style="display:none">
  	</label>
    <div id="addTo" style="visiblity:hidden; position:absolute;">
		<form method="POST" enctype="multipart/form-data">
        <select name="playlistValue"><?php
   			$odbiorca = mysqli_query($connection, "Select * from song") or die ("DB error: $dbname");
    		while ($row = mysqli_fetch_array ($odbiorca)){
    		$playlistOption = $row[1];
    		$playlistValue = $row[0];
    		print "<option value='$playlistValue'>$playlistOption</option>\n";}?>
    </select><br> 
    <input type="submit" id="subToPlaylist" name="subToPlaylist" value="Add"/></form></div>
    <br> <br> <br> <br> <br> <br><br> <br><br> <br>
<script>
  	//Zmienne JS
	var dir = "<?php echo $_GET['dir']; ?>";
  var playlist = "<?php echo $_GET['playlist']; ?>";
  	var labelAdd = document.getElementById("labelAdd");
   var play = document.getElementById('playlist');
  	var labelBack = document.getElementById("labelBack");
    var checkbox = document.getElementById('addFolder');
   var checkbox3 = document.getElementById('addToPlaylist');
	var delivery_div = document.getElementById('up');
 var playlistDiv = document.getElementById('playlistDiv');
   var checkbox2 = document.getElementById('addPlaylist');
   var addP = document.getElementById('addP');
  var addTo = document.getElementById('addTo');
  var addToLabel = document.getElementById('addToLabel');
   	//Wyswietlanie lub nie odpwowiednich przyciskow
   document.addEventListener("DOMContentLoaded", checkbox.onclick =function(event) {
   if(this.checked) {
     delivery_div.style['visibility'] = 'visible';
     playlistDiv.style['visibility'] = 'hidden';
     addTo.style['visibility'] = 'hidden';
     playlistDiv.style['visibility'] = 'hidden';
   } else {
     delivery_div.style['visibility'] = 'hidden';
     
   }
});
     document.addEventListener("DOMContentLoaded", checkbox3.onclick =function(event) {
   if(this.checked) {
     addTo.style['visibility'] = 'visible';
     delivery_div.style['visibility'] = 'hidden';
   } else {
     addTo.style['visibility'] = 'hidden';
     
   }
});
   document.addEventListener("DOMContentLoaded", checkbox2.onclick =function(event) {
   if(this.checked) {
     playlistDiv.style['visibility'] = 'visible';
     delivery_div.style['visibility'] = 'hidden';
     
   } else {
     playlistDiv.style['visibility'] = 'hidden';
      
   }
});
    if(!playlist) {
      play.style['visibility'] = 'visible';
      addToLabel.style['visibility'] = 'hidden';
      labelBack.setAttribute("hidden", "hidden");}
	else {
      play.style['visibility'] = 'hidden';
      addToLabel.style['visibility'] = 'visible';
      labelBack.removeAttribute("hidden"); }
    </script>
<?php
   //Tabela od wyswietlania playlist
  	print "<TABLE CELLPADDING=5 BORDER=1>";
  	print "<TR><TD style='color:red'><b>Prywatne playlisty</b></TD></TR>\n"; 
    $findPlaylist = mysqli_query($connection, "Select * from playlistname") or die ("DB error 2: $dbname");
 	while ($row = mysqli_fetch_array ($findPlaylist)) if($row[1] == $_SESSION['userid'])  print "<TR><TD><a href='myspotify.php?playlist=$row[0]'>$row[2]</a></TD></TR> ";
  	print "<TR><TD style='background-color:black'></TD></TR><TR><TD style='color:red'><b>Publiczne playlisty</b></TD></TR>\n"; 
    //Publiczna playlista bez powtorzen
    $findPlaylist = mysqli_query($connection, "Select * from playlistname") or die ("DB error 2: $dbname");
	while ($row = mysqli_fetch_array ($findPlaylist)) if($row[3] == true && $row[1] != $_SESSION['userid'])  
    print "<TR><TD><a href='myspotify.php?playlist=$row[0]'>$row[2]</a></TD></TR> ";
    //Zmienne
    $userSession = $_SESSION['login'];
    $useridu = $_SESSION['userid'];
    $playlistName = $_POST['playlistName'];
    $createPlaylist = $_POST['createPlaylist'];
  	$subToPlaylist = $_POST['subToPlaylist'];
    $userid = $_POST['userid'];
 	$public = $_POST['public'];
  	$playlist = $_GET['playlist'];
  	$playlistValue = $_POST['playlistValue'];
    //Jezeli knliknie sie tworzenie playlisty
    if(isset($createPlaylist))
    	$addPlaylist = mysqli_query($connection, "INSERT INTO playlistname (name, idu, public) values ('$playlistName', '$useridu', '$public') ") or die ("DB error 2:  $connection->error);");
	//Jezeli kliknie sie dodanie do playlisty
    if(isset($subToPlaylist))
    	$addToPlaylist = mysqli_query($connection, "INSERT INTO playlistdatabase (idpl, ids) values ('$playlist', '$playlistValue') ") or die ("DB error 2:  $connection->error);");
	$findPlaylist2 = mysqli_query($connection, "Select * from playlistname") or die ("DB error 2: $dbname");
	 while ($row3 = mysqli_fetch_array ($findPlaylist2)) {
   			if($playlist == $row3[0])$playlistNow = $row3[2];
   			if(!isset($playlist)) $playlistNow = "WSZYSTKIE UTWORY";
 }
	print "<br><br><TABLE id='tab' class='tab' CELLPADDING=5 BORDER=1>";
	print "<TR><TD style='color:red'><b>$playlistNow</b></TD><TD>Tytuł</TD><TD>Wykonawca</TD><TD>Gatunek</TD></TR>\n"; 
	$dir = "songs/";
	$files = array_diff(scandir($dir), array('.', '..'));
    if(!isset($_GET['playlist'])){
    $findP = mysqli_query($connection, "Select * from song join musictype on song.idmt = musictype.idmt;") or die ("DB error 2:  $connection->error);");
    while($row = mysqli_fetch_array ($findP))
    	print "<TR><TD> <audio controls src='$dir/$row[5]'><a href='$dir/$row[5]'>Download audio</a></audio></TD><TD><a href='$dir/$row[5]' download>$row[1]</a></TD><TD>$row[2]</TD><TD>$row[9]</TD></TR>\n";
    }
    else {
   	$findP = mysqli_query($connection, "Select * from song join musictype on song.idmt = musictype.idmt join playlistdatabase on song.ids = playlistdatabase.ids where (playlistdatabase.idpl='$playlist')") or die ("DB error 2:  $connection->error);");
    while($row = mysqli_fetch_array ($findP))
    	print "<TR><TD> <audio controls src='$dir/$row[5]'><a href='$dir/$row[5]'>Download audio</a></audio></TD><TD><a href='$dir/$row[5]' download>$row[1]</a></TD><TD>$row[2]</TD><TD>$row[9]</TD></TR>\n";
 	}
	print "</TABLE>"; 
  	mysqli_close($connection);
}
?>
    

  </body>
</html>
