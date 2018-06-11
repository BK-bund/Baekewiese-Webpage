<?php

// ======= Konfiguration:
$returnPage             = "/reservierung/success/";
$returnErrorPage        = "/reservierung/failure/";
$cc_mailTo              = "vermietung@baekewiese.de";
$admin_mailTo           = "admin@cjf-berlin.de";
$sendCCToAdmin          = True;
$mailFrom               = "Baekewiesen Vermietungen <vermietung@baekewiese.de>";
$cc_mailSubject         = "Neue Reservierungsanfrage: ";
$customer_mailSubject   = "Deine Reservierungsanfrage für die Bäkewiese";
$customer_mailText      = "Vielen Dank für deine Reservierungsanfrage für die Bäkewiese! Wir melde uns in Kürze bei dir.\n\nDeine Anfrage war:\n";

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
 $mailSent1 = @mail($cc_mailTo, $cc_mailSubject, $mailText, $header);
 $mailSent2 = @mail($customer_mailTo, $customer_mailSubject, $customer_mailText . $mailText, $header);

// Sende eine Kopie an den Admin
if($sendCCToAdmin) {
  @mail($admin_mailTo, $cc_mailSubject, $mailText, $header);
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
