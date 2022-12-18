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
  ?>
<html>
  <head>
    <title>GEOLOCALIZATION</title>
    <!-- Prosty CSS -->
    <style> 
	@keyframes example {
  		0%   {background-color:red; left:0px; top:0px;}
  		50%  {background-color:aliceblue; left:200px; top:0px;}
  		100% {background-color:red; left:0px; top:0px;}
}
  img, audio, video {
  width:100%;
    max-width:200px;
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
input[type=text] {
  width: 10%;
  color:red;
  padding: 12px 20px;
  margin: 8px 0;
  outline: none;
}
</style>  
</head>
<body>
    <!--Pole z nazwą uzytkownika i przyciskami do clouda i wylogowania się -->
	<form method="POST" action="cloud.php" enctype="multipart/form-data"><br>
		Zalogowano jako:<input type="text" name="user" maxlength="10" size="10" value="<?php echo $_SESSION['login']?>" readonly>
		<input type="submit" value="MyCloud"/>
	</form>
 	<form action="logout.php"><br> <input type="submit" value="LOGOUT"/></form>
<script>
      //Za pomoca JavaScript pobiera informacje o wlaściwościach przeglądarki
       document.cookie = "resolution = " + screen.width + " x "+ screen.height;
       document.cookie = "screen = " + screen.availWidth + " x "+ screen.availHeight;
       document.cookie = "colors = " + screen.colorDepth;
       document.cookie = "cookies = " + navigator.cookieEnabled;
       document.cookie = "java = " + navigator.cookieEnabled;
       document.cookie = "language = " + navigator.language;
</script>
   <?php
    //Pobranie adresu IP
	$ipaddress = $_SERVER["REMOTE_ADDR"];
    //Funkcja do sprawdzenia geolokalizacji  
	function ip_details($ip) {
		$json = file_get_contents ("http://ip-api.com/json/{$ip}"); 
		$details = json_decode ($json);
		return $details;
	}
    //Przypisanie do zmiennych PHP z JS
	$resolution = $_COOKIE['resolution'];
	$screen = $_COOKIE['screen'];
	$colors = $_COOKIE['colors'];
	$cookies = $_COOKIE['cookies'];
	$java = $_COOKIE['java'];
	$language = $_COOKIE['language'];
    //Sprawdzenie z jakiej przeglądarki korzysta gość
	$agent = $_SERVER['HTTP_USER_AGENT'];
	preg_match('/(?:(?P<CHROME>Chrome)|(?P<MSIE>MSIE)|(?P<IPHONE>iPhone)(?P<ANDROID>Android)|(?P<FIREFOX>Firefox)|(?P<SAFARI>Safari)|(?P<OPERA>Opera))/', $agent, $matches); //Regex do sprawdzenia przeglądarki
    //Połączenie z bazą danych
	$mysqli = new mysqli("localhost", kosierap_z4, Laboratorium123, kosierap_z4); 
	mysqli_query($mysqli, "SET NAMES 'utf8'");
    $user_now = $_SESSION['login'];
    //Dwa zapytania, jedno to dodanie do bazy, a drugie wyświetlenie
	$query = "SELECT * FROM goscieportalu order by datetime desc";
    $break = "Select * from break_ins "; //Komenda MySQL do dodawania uzytkownika
if ($resultBreak = $mysqli->query($break)) {
      while ($rowBreak = $resultBreak->fetch_assoc()) {
        $dateBreak = $rowBreak["datetime"];
        $ipBreak = $rowBreak["ipaddress"];
      }
      echo "<h2 style='color:red'>Ostatnio zablokowano dostęp! Data: $dateBreak  Adres IP: $ipBreak <h2>";
      
} 
	$sql = "INSERT INTO goscieportalu (ipaddress, datetime, browser, resolution, screen, colors, cookies, java, language, user) VALUES ('$ipaddress', CURRENT_TIMESTAMP,'$matches[0]','$resolution','$screen','$colors','$cookies','$java','$language', '$user_now');";
	if ($result = $mysqli->query($sql)) {
    //Prosta tabela
    echo '<table border="0" cellspacing="10" cellpadding="10"
    style="color:blue; 
    border:1px solid;
    background:aliceblue;
    font-size: 16px;
    border-collapse: collapse;
    "> 
      <tr  style="color:red; 
    border:3px solid;
    font-size: 20px;
    font-weight: 400
    ">  <td> <font face="Arial">User</font> </td> 
        <td> <font face="Arial">Data</font> </td> 
        <td> <font face="Arial">IP</font> </td> 
        <td> <font face="Arial">Lokalizacja</font> </td> 
        <td> <font face="Arial">Wspolrzędne</font> </td> 
        <td> <font face="Arial">Mapy Google</font> </td> 
        <td> <font face="Arial">Przeglądarka</font> </td> 
        <td> <font face="Arial">Ekran</font> </td> 
        <td> <font face="Arial">Okno</font> </td> 
        <td> <font face="Arial">Kolory</font> </td> 
        <td> <font face="Arial">Ciasteczka</font> </td> 
        <td> <font face="Arial">Java</font> </td> 
        <td> <font face="Arial">Jezyk</font> </td> 
      </tr>';
    	$i=0;
       if ($result = $mysqli->query($query)) {
         //Pętla, aby wyświetlić każdy rekord z tabeli
    	while ($row = $result->fetch_assoc()) {
         //Przypisz do zmiennych odpowiednie dane z bazy danych
        $details_ip = ip_details($row["ipaddress"]);
        $f1 = $row["datetime"];
      	$f2 = $row["ipaddress"];
      	$f3 = $details_ip -> country.", ". $details_ip -> regionName.", ".$details_ip -> city;
        $f4 = $details_ip -> lat.",".$details_ip -> lon;
        $f5 = "<a href='https://www.google.pl/maps/place/$f4'>LINK</a>";
        $f6 = $row["browser"]; 
      	$f7 = $row["resolution"]; 
      	$f8 = $row["screen"]; 
      	$f9 = $row["colors"]; 
      	$f10 = $row["cookies"]; 
      	$f11 = $row["java"]; 
      	$f12 = $row["language"];
          $user = $row["user"];
          	//Rozdzielenie tabeli, aby pierwszy rekord miał animację
      		if($i==0){
            //Wypisanie w tabeli danych z bazy danych
        	echo '<tr style="animation-name: example;
  						animation-duration: 4s;"> 
                  <td>'.$user.'</td> 
                  <td>'.$f1.'</td> 
                  <td>'.$f2.'</td> 
                  <td>'.$f3.'</td> 
                  <td>'.$f4.'</td> 
                  <td>'.$f5.'</td> 
                  <td>'.$f6.'</td> 
                  <td>'.$f7.'</td> 
                  <td>'.$f8.'</td> 
                  <td>'.$f9.'</td> 
                  <td>'.$f10.'</td> 
                  <td>'.$f11.'</td> 
                  <td>'.$f12.'</td> 
              </tr>';
        	$i=1;
      		}else{
       		 echo '<tr> 
              	  <td>'.$user.'</td> 
                  <td>'.$f1.'</td> 
                  <td>'.$f2.'</td> 
                  <td>'.$f3.'</td> 
                  <td>'.$f4.'</td> 
                  <td>'.$f5.'</td> 
                  <td>'.$f6.'</td> 
                  <td>'.$f7.'</td> 
                  <td>'.$f8.'</td> 
                  <td>'.$f9.'</td> 
                  <td>'.$f10.'</td> 
                  <td>'.$f11.'</td> 
                  <td>'.$f12.'</td> 
              </tr>';
      }
    }
    $result->free();
}  
      }
    
}
?>
  </body>
</html>


