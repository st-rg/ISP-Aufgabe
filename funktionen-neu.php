<?php
/**
 * Created by PhpStorm.
 * User: stefanie_reeg
 * Date: 2019-02-05
 * Time: 10:19
 */

//funktion erstellt ein Eingabefeld
function erstelleEingabefeld($nameEingabefeld) {

    echo $nameEingabefeld.': '.'<input type="text" name="'.$nameEingabefeld. '"/>'.'<br>';

}


function erstelleEingabefeldrot($nameEingabefeld) {

    echo '<div class="red">'.$nameEingabefeld.': '.'</div>'.'<input type="text" name="'.$nameEingabefeld. '"/>'.'<br>';
    echo '<div class="red"> Bitte keine Sonderzeichen verwenden!'.'</div>'.'<br>';
}


//funktion erstellt ein Button
function erstelleInput($inputType, $inputName, $inputWert) {

    echo '<input type="'.$inputType.'" name="'.$inputName.'" value="'.$inputWert.'" class="button float rund"/>';
}


function pruefeName($name) {

    if($name == 'steffi reeg') {
        return true;
    } else {
        return false;
    }

}



function pruefeReiseziele($eingabe) {

    $reisezieleNichtOK = [];
    $reisezieleOK = [];

    $zaehler = 0;
    $nummer = 1;

    $leeresFeld = 0;
    $falscheEingabe = 0;
    $korrekteEingabe = 0;

    $sonderzeichen = '/[^0-9a-zA-ZöüäÖÜÄ]/';


   foreach($eingabe as $schluessel=>$wert) {



              if ($wert == '') {

                  $reisezieleNichtOK[$zaehler] = '<label for="Urlaubsziel'.$nummer.'">'.'Urlaubsziel '.$nummer.': </label>'.'<input type="text" name="'.'Urlaubsziel '.$nummer.'"/>'.'<br>';
                  $reisezieleOK[$zaehler] = '<p class="uppercase">Urlaubziel'.$nummer.': '.$wert.'</p><br>';
                  $leeresFeld++;
                  $zaehler++;
                  $nummer++;
              }

              else if (preg_match($sonderzeichen, $wert)) {

                  $reisezieleNichtOK[$zaehler] = '<label for="Urlaubsziel '.$nummer.'"class="rot">'.'Urlaubsziel '.$nummer.'</label>'.'<input type="text" name="'.'Urlaubsziel '.$nummer.'" value="'.$wert.'"/>'.'<br>'.
                  '<p class="rot">Sonderzeichen sind nicht erlaubt!!!</p>';
                  $falscheEingabe++;
                  $zaehler++;
                  $nummer++;


            } else {

                  $reisezieleNichtOK[$zaehler] = 'Urlaubsziel '.$nummer.': '.$wert.'<br>';
                  $reisezieleOK[$zaehler] = '<p class="uppercase">Urlaubziel '.$nummer.': '.$wert.'</p><br>';
                  $korrekteEingabe++;
                  $zaehler++;
                  $nummer++;

              }
   }

   if($falscheEingabe>0) {
       erstelleFormularSeite1($reisezieleNichtOK);
   }

   else if ($falscheEingabe == 0 && $korrekteEingabe>0) {
       file_put_contents('reisezieleOK.txt',$reisezieleOK);
       $kontaktdaten = ['<label for="name">Name:</label>'.'<input type="text" name="name"/>'.'<br>', '<label for="adresse">Adresse:</label>'.'<input type="text" name="adresse"/>'.'<br>', '<label for=telefon">Telefon: </label>'.'<input type="text" name="telefon"/>'.'<br>'];
       erstelleFormularSeite2($kontaktdaten);
   }

   else {
       erstelleFormularSeite1($reisezieleNichtOK);
   }

}


function pruefeKontaktdaten($eingabe) {

    $fehlerGefunden = 0;
    $zaehler = 0;
    $kontaktdatenOK = [];
    $kontaktdatenNichtOK = [];

    $pruefeName = '/^[A-ZÄÖÜ]{1}[a-zäüö]+(\-|\s)[A-ZÄÜÖ]{1}[a-zäüö]+\s[A-ZÄÜÖ]{1}[a-zöüä]+(\-|\s)[A-ZÄÜÖ]{1}[a-zäöü]+/';
    $pruefeAdresse = '/ /';
    $pruefeTelefon = '/ /';


    foreach ($eingabe as $schluessel=>$wert) {

        if($schluessel == 'name') {

                if($wert !== '' && preg_match($pruefeName, $wert)) {
                    $kontaktdatenOK[$zaehler] = '<p class="uppercase">Name: '.$wert.'</p><br>';
                    $kontaktdatenNichtOK[$zaehler] = '<p class="uppercase">Name: '.$wert.'</p><br>';
                    $zaehler++;
                } else {
                    $kontaktdatenNichtOK[$zaehler] = '<label for"name" class="rot">Falscher Name:</label><input type="text" name="name" />'.'<br>'
                                                     .'<p class="rot">Bitte geben Sie einen korrekten Namen ein!</p>';
                    $fehlerGefunden++;
                }
        }

    }

    if($fehlerGefunden == 0) {

        erstelleFormularSeite3($kontaktdatenOK);

    } else {
        erstelleFormularSeite2($kontaktdatenNichtOK);

    }

}



function erstelleFormularSeite1($felder) {


    echo '<h2 id="formular_headline">Meine Urlaubsziele</h2>';

    echo '<form action="index.php" method="Post">';

    echo '<fieldset>';

    foreach($felder as $value) {
        echo $value;
    }

    erstelleInput("reset", "","reset");
    erstelleInput("submit", "","submit");

    echo '</fieldset>';

    echo '</form>';

}


function erstelleFormularSeite2($daten) {

        echo '<h2 id="formular_headline">Kontaktdaten eingeben!</h2>';

        print_r((file_get_contents('reisezieleOK.txt')));

        echo '<form action="index.php" method="Post">';

        echo '<fieldset>';

        foreach($daten as $wert) {
            echo $wert;
        }

        erstelleInput("reset", "","reset");
        erstelleInput("submit", "","submit");

        echo '</fieldset>';

        echo '</form>';

}

function erstelleFormularSeite3($daten) {

    echo '<h2 id="formular_headline">Wunschübersicht</h2>';

    print_r(file_get_contents('reisezieleOK.txt'));

    foreach($daten as $wert) {
        echo $wert.'<br>';

    }

}