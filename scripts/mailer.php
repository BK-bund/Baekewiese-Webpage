<?php

// ======= Konfiguration:

$returnPage           = "/reservierung/success/";
$returnErrorPage      = "/reservierung/failure/";
$cc_mailTo            = "vermietung@baekewiese.de";
//$cc_mailTo            = "admin@cjf-berlin.de";
$cc_mailFrom          = "Baekewiesen Vermietungen <vermietung@baekewiese.de>";
$cc_mailSubject       = "Neue Reservierungsanfrage: ";
$customer_mailSubject = "Deine Reservierungsanfrage für die Bäkewiese";
$customer_mailText    = "Vielen Dank für deine Reservierungsanfrage. Wir melde uns in Kürze bei dir.\nDeine Anfrage war:\n\n";
$mailText = "";


$header  = "From:" .$cc_mailFrom ."\n";
//$header .= "Reply-To: Baekewiesen Vermietungen <vermietung@baekewiese.de> \n";
$header .= "MIME-Version: 1.0\n";
$header .= "Content-Type: text/plain; charset=utf-8\n";
//$header .= "Content-type: text/html;\n";// "; charset=iso-8859-1\r\n";


$error = False;
// ======= Text der Mail aus den Formularfeldern erstellen:

// Wenn Daten mit method="post" versendet wurden:
if(isset($_POST)) {
   // alle Formularfelder der Reihe nach durchgehen:
   foreach($_POST as $name => $value) {
     if($name == "Organisation" && $value != "")  {
       $cc_mailSubject .= $value . ",";
     }
     if($name == "Vorname")  {
       $cc_mailSubject .= $value . " ";
     }
     if($name == "Nachname")  {
       $cc_mailSubject .= $value;
     }
     if($name == "E-Mail_1") {
       $customer_mailTo = $value;
     }
     if($name == "E-Mail_2" && $value != $customer_mailTo) {
       $error = True;
     }
      // Wenn der Feldwert aus mehreren Werten besteht:
      // (z.B. <select multiple>
      if ($name == "E-Mail_2") {
        continue;
      }
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
          if ($name == "E-Mail_1") {
            $name = "E-Mail";
          }
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
 if($error) {
   header("Location: " . $returnErrorPage);
   exit;
 }

 // Mail versenden und Versanderfolg merken
 $mailSent1 = @mail($cc_mailTo, $cc_mailSubject, $mailText, $header);
 $mailSent2 = @mail($customer_mailTo, $customer_mailSubject, $customer_mailText . $mailText, $header);

 // ======= Return-Seite an den Browser senden

 // Wenn der Mailversand erfolgreich war:
 if($mailSent1 == TRUE && $mailSent2 == TRUE) {
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
