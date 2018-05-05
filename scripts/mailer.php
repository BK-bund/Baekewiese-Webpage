<?php

// ======= Konfiguration:

$mailTo = 'vermietung@baekewiese.de';
$mailFrom = '"Reservierungsanfrage" <webmaster@baekewiese.de>';
$mailSubject    = 'Neue Reservierungsanfrage: ';
$returnPage = '/reservierung/success/';
$returnErrorPage = '/reservierung/failure/';
$mailText = "";

$header  = "MIME-Version: 1.0\n";
//$header .= "Content-type: text/html;\n";// "; charset=iso-8859-1\r\n";
$header .="Content-Type: text/plain; charset=utf-8\n";
$header .= "From:" .$mailFrom ."\n";

$error = False;
/*
if(isset($_POST['g-recaptcha-response'])){
  $captcha=$_POST['g-recaptcha-response'];
}
*/

// ======= Text der Mail aus den Formularfeldern erstellen:

// Wenn Daten mit method="post" versendet wurden:
if(isset($_POST)) {
   // alle Formularfelder der Reihe nach durchgehen:
   foreach($_POST as $name => $value) {
     if($name == "Organisation" && $value != "")  {
       $mailSubject .= $value . ",";
     }
     if($name == "Vorname")  {
       $mailSubject .= $value . " ";
     }
     if($name == "Nachname")  {
       $mailSubject .= $value;
     }
     if($name == "E-Mail_1") {
       $mailCustomer = $value;
     }
     if($name == "E-Mail_2" && $value != $mailCustomer) {
       $error = True;
     }
      // Wenn der Feldwert aus mehreren Werten besteht:
      // (z.B. <select multiple>)
      if(is_array($value)) {
          // "Feldname:" und Zeilenumbruch dem Mailtext hinzufügen
          $mailText .= $name . ":\n";
          // alle Werte des Feldes abarbeiten
          foreach($valueArray as $entry) {
             // Einrückungsleerzeichen, Wert und Zeilenumbruch
             // dem Mailtext hinzufügen
             $mailText .= "   " . $value . "\n";
          } // ENDE: foreach
      } // ENDE: if
      // Wenn der Feldwert ein einzelner Feldwert ist:
      else {
          // "Feldname:", Wert und Zeilenumbruch dem Mailtext hinzufügen
          $mailText .= $name . ": " . $value . "\n";
      } // ENDE: else
   } // ENDE: foreach
} // if

// ======= Korrekturen vor dem Mailversand

// Wenn PHP "Magic Quotes" vor Apostrophzeichen einfügt:
 if(get_magic_quotes_gpc()) {
     // eventuell eingefügte Backslashes entfernen
     $mailtext = stripslashes($mailtext);
 }

// ======= Mailversand

 /*
 if(!$captcha){
   echo '<h2>Please check the the captcha form.</h2>';
   exit;
 }


 $secretKey = "6LfA1R4TAAAAAGzh344G_JH0Q5KBu_byskTGsA7i";
 $ip = $_SERVER['REMOTE_ADDR'];
 $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
 $responseKeys = json_decode($response,true);
 if(intval($responseKeys["success"]) !== 1) {
   echo '<h2>You are spammer ! Get the @$%K out</h2>';
   exit;
 }
 */

 if($error) {
   header("Location: " . $returnErrorPage);
   exit;
 }
 // Mail versenden und Versanderfolg merken
 $mailSent = @mail($mailTo, $mailSubject, $mailText, $header);

 // ======= Return-Seite an den Browser senden

 // Wenn der Mailversand erfolgreich war:
 if($mailSent == TRUE) {
   // Seite "Formular verarbeitet" senden:
   header("Location: " . $returnPage);
 }
 // Wenn die Mail nicht versendet werden konnte:
 else {
   // Seite "Fehler aufgetreten" senden:
   header("Location: " . $returnErrorPage);
 }

// ======= Ende
exit();
?>
