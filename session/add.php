<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
  <HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
  </HEAD>
<BODY>
<?php
$user = htmlentities ($_POST['user'], ENT_QUOTES, "UTF-8"); 
$pass = htmlentities ($_POST['pass'], ENT_QUOTES, "UTF-8");
$pass2 = htmlentities ($_POST['pass2'], ENT_QUOTES, "UTF-8");
$link = mysqli_connect(localhost, kosierap_z5, Laboratorium123, kosierap_z5);

if(!$link) { echo"Error: ". mysqli_connect_errno()." ".mysqli_connect_error(); }
mysqli_query($link, "SET NAMES 'utf8'");
  if($pass==$pass2){ //Jezeli hasla z rejestruj.php sa takie same
     $sql = "INSERT INTO user (login, password) VALUES ('$user', '$pass');"; //Komenda MySQL do dodawania uzytkownika
    if (mysqli_query($link, $sql)) {
      echo "Utworzono użytkownika.";
      mkdir("../users/$user", 0755, true);
	  echo '<form action="index3.php"><br> <input type="submit" value="Zaloguj się"/></form>'; //Wyswietlenie przycisku do przejscia do panelu logowania z "pelnymi" zabezpieczeniami
} else {
   echo "Error: " . $sql . "<br>" . mysqli_error($link); //Wyswietl blad z MySQL
}
 }
  else{
    mysqli_close($link); 
    echo "Hasła nie są takie same!";
  
 }
?> </BODY> </html>