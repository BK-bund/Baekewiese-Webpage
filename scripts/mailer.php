<?php

// ======= Konfiguration:
$returnPage             = "/reservierung/success/";
$returnErrorPage        = "/reservierung/failure/";
$mailTo_cc              = "vermietung@baekewiese.de";
$mailTo_admin           = "admin@cjf-berlin.de";
$sendCCToAdmin          = True;
$mailFrom               = "Baekewiesen Vermietungen <vermietung@baekewiese.de>";
$mailSubject_cc         = "Neue Reservierungsanfrage: ";
$mailSubject_customer   = "Deine Reservierungsanfrage für die Bäkewiese";
$mailText_customer      = "Vielen Dank für deine Reservierungsanfrage für die Bäkewiese! Wir melde uns in Kürze bei dir.\n\nDeine Anfrage war:\n";

$header  = "From:" .$mailFrom ."\n";
$header .= "MIME-Version: 1.0\n";
$header .= "Content-Type: text/plain; charset=utf-8\n";

// Initialisiere Variablen
$mailText = "";
$error = False;
// ======= Text der Mail aus den Formularfeldern erstellen:

// Wenn Daten mit method="post" versendet wurden:
if(isset($_POST)) {
   // alle Formularfelder der Reihe nach durchgehen:
   foreach($_POST as $name => $value) {
     if($name == "Organisation" && $value != "")  {
       $mailSubject_cc .= $value . ", ";
     }
     if($name == "Vorname")  {
       $mailSubject_cc .= $value . " ";
     }
     if($name == "Nachname")  {
       $mailSubject_cc .= $value;
     }
     if($name == "E-Mail_1") {
       $mailTo_customer = $value;
     }
     if($name == "E-Mail_2" && $value != $mailTo_customer) {
       $error = True;
     }
     if ($name == "E-Mail_2" || $name == "Nutzungsbedingungen") {
       continue;
     }
      // Wenn der Feldwert aus mehreren Werten besteht:
      // (z.B. <select multiple>
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
 $mailSent1 = @mail($mailTo_cc, $mailSubject_cc, $mailText, $header);
 $mailSent2 = @mail($mailTo_customer, $mailSubject_customer, $mailText_customer . $mailText, $header);

// Sende eine Kopie an den Admin
if($sendCCToAdmin) {
  @mail($mailTo_admin, $mailSubject_cc, $mailText, $header);
}

 // ======= Return-Seite an den Browser senden

 // Wenn der Mailversand erfolgreich war:
 if($mailSent1 && $mailSent2) {
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
