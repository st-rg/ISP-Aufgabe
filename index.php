<?php

require 'funktionen-neu.php';

$file_last_mod_time = filemtime(__FILE__);
$content_last_mod_time = 7;
$etag = '"'.$file_last_mod_time.'.'.$content_last_mod_time.'"';
// fügt Header mit Datum und Uhrzeit mit der letzten Änderung des Dokuments hinzu
echo header("Last-Modified: ".date("D, d M Y H:i:s")."GMT");
/* fügt Header hinzu: Antworten vom Server müssen im Cache aufgehoben werden und können für
* die nächsten 24 Stunden wiederverwendet werden
*/
echo header("Cache-control: max-age=84600");
/*
* fügt Header mit eTag hinzu: ein unverwechselbares Merkmal für den Inhalt der Seite.
*/

echo header('ETag: '.$etag);

echo <<<EOT
<head> 
<style type="text/css"> @import "./css/normalize.css";</style>
<style type="text/css"> @import "./css/stylingcss.css";</style>
</head>
EOT;


if(isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
    if($_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {
        header('HTTP/1.1 304 Not Modified', true, 304);
        //exit();
    }
}




if (count($_POST) == 0) {

    $leereFelder = [
        '<label for="urlaubsziel-1">Urlaubsziel 1:</label><input type="text" name="urlaubsziel-1."/>'.'<br>',
        '<label for="urlaubsziel-2">Urlaubsziel 2:</label><input type="text" name="urlaubsziel-2."/>'.'<br>',
        '<label for="urlaubsziel-2">Urlaubsziel 3:</label><input type="text" name="urlaubsziel-3."/>'.'<br>',
    ];

    erstelleFormularSeite1($leereFelder);

}

else if (!isset($_POST['name'])) {
    pruefeReiseziele($_POST);
} else {
    pruefeKontaktdaten($_POST);
}




