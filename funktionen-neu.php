<?php
/**
 * Created by PhpStorm.
 *
 * In dieser Datei befinden sich die Funktionen, die für die Erzeugung der Eingabefelder benötigt werden,
 * außerdem die Funktionen, die die Eingaben überprüfen und die HTML-Seiten erzeugen
 *
 * @author Steffi Reeg <s50213@beuth-hochschule.de>
 * User: stefanie_reeg
 * Date: 2019-02-05
 * Time: 10:19
 */

/* Die Funktion erstellt ein Eingabefeld, bekommt als Wert einen String mit dem gewünschten
* Namen des Eingabefeldes
*/
function erstelleEingabefeld($nameEingabefeld) {

    echo $nameEingabefeld.': '.'<input type="text" name="'.$nameEingabefeld. '"/>'.'<br>';

}

/* funktion erstellt ein Eingabefeld mit rotem Text und einem Hinweistext, der auch rot dargestellt wird
* der Hinweistext informiert, dass Sonderzeichen nicht erlaubt sind
*/
function erstelleEingabefeldrot($nameEingabefeld) {

    echo '<div class="red">'.$nameEingabefeld.': '.'</div>'.'<input type="text" name="'.$nameEingabefeld. '"/>'.'<br>';
    echo '<div class="red"> Bitte keine Sonderzeichen verwenden!'.'</div>'.'<br>';
}


/* funktion erstellt ein Button, bekommt als Werte den Typ des Buttons, den gewünschten Namen des Buttons
* und den Wert, der im Button stehen soll
*/
function erstelleInput($inputType, $inputName, $inputWert) {

    echo '<input type="'.$inputType.'" name="'.$inputName.'" value="'.$inputWert.'" class="button float rund"/>';
}


/* Die Funktion bekommt die Daten aus dem Array $_POST und prüft, ob die Daten unzulässige Sonderzeichen enthalten.
* Sind die Felder gar nicht ausgefüllt, wird das Formular einfach noch einmal dargestellt.
* Sind alle Daten korrekt, wird die zweite Seite der Website dargestellt. Sind Fehler in den Daten, wird erneut
* die erste Seite der Website dargestellt, mit roten Warnhinweisen.
*/
function pruefeReiseziele($eingabe) {

    $reisezieleNichtOK = [];
    $reisezieleOK = [];

    $zaehler = 0;
    $nummer = 1;

    $leeresFeld = 0;
    $falscheEingabe = 0;
    $korrekteEingabe = 0;

    /* Beginnt am Anfang des Strings und sucht bis zum Ende des Strings.
    * Erlaubt sind alle alphanumerischen Zeichen, Leerzeichen, Bindestrich und Punkt.
    * Leider kann man jetzt Leerzeichen, Bindestrich und Punkt mehrmals hintereinander eingeben,
    * Man kann Schriftzeichen aus allen Sprachen verwenden.
    */
    $sonderzeichen = '/^[\w\s\-\.]+$/';


   foreach($eingabe as $schluessel=>$wert) {
       //alle Daten des Arrays werden geprüft, außer dem versteckten Button mit dem Namen 'seite-1'
       if($schluessel !== 'seite-1') {


           if ($wert == '') {

               $reisezieleNichtOK[$zaehler] = '<label for="Urlaubsziel' . $nummer . '">' . 'Urlaubsziel ' . $nummer . ': </label>' . '<input type="text" name="' . 'Urlaubsziel ' . $nummer . '"/>' . '<br>';
               $reisezieleOK[$zaehler] = '<p class="uppercase">Urlaubziel' . $nummer . ': ' . $wert . '</p><br>';
               $leeresFeld++;
               $zaehler++;
               $nummer++;

           } else if (!preg_match($sonderzeichen, $wert)) {

               $reisezieleNichtOK[$zaehler] = '<label for="Urlaubsziel ' . $nummer . '"class="rot">' . 'Urlaubsziel ' . $nummer . '</label>' . '<input type="text" name="' . 'Urlaubsziel ' . $nummer . '" value="' . $wert . '"/>' . '<br>' .
                   '<p class="rot">Sonderzeichen sind nicht erlaubt!!!</p>';
               $falscheEingabe++;
               $zaehler++;
               $nummer++;

           } else {

               $reisezieleNichtOK[$zaehler] = 'Urlaubsziel ' . $nummer . ': ' . $wert . '<br>';
               $reisezieleOK[$zaehler] = '<p class="uppercase">Urlaubziel ' . $nummer . ': ' . $wert . '</p><br>';
               $korrekteEingabe++;
               $zaehler++;
               $nummer++;

           }

       }
   }

   if($falscheEingabe>0) {
       erstelleFormularSeite1($reisezieleNichtOK);
   }

   //alle Bedingungen erfüllt
   else if ($falscheEingabe == 0 && $korrekteEingabe>0) {

       //Daten lokal speichern
       file_put_contents('daten.txt',$reisezieleOK);

       //Array initialisieren, das die Eingabefelder für die zweite Seite enthält
       $kontaktdaten = ['<label for="name">Name:</label>'.'<input type="text" name="name"/>'.'<br>', '<label for="adresse">Adresse:</label>'.'<input type="text" name="adresse"/>'.'<br>', '<label for=telefon">Telefon: </label>'.'<input type="text" name="telefon"/>'.'<br>'];

       //Funktion aufrufen, die die zweite Seite erstellt
       erstelleFormularSeite2($kontaktdaten);
   }

   else {
       erstelleFormularSeite1($reisezieleNichtOK);
   }

}


/* die Funktion bekommt die Daten aus dem Array $_POST und prüft, ob die Felder ausgefüllt sind und ob die eingegebenen Daten
* korrekt sind
*/
function pruefeKontaktdaten($eingabe) {

    $fehlerGefunden = 0;
    $zaehler = 0;
    $kontaktdatenOK = [];
    $kontaktdatenNichtOK = [];

    /* Beginnt am Anfang des Strings und sucht bis zum Ende des Strings.
   * Erlaubt sind alle alphanumerischen Zeichen, Leerzeichen, Bindestrich und Punkt.
   * Leider kann man jetzt Leerzeichen, Bindestrich und Punkt mehrmals hintereinander eingeben,
   * Man kann Schriftzeichen aus allen Sprachen verwenden.
   */
    $pruefeName = '/^[\w\s\-\.]+$/';


    /* Beginnt am Anfang des Strings und sucht bis zum Ende des Strings.
    * Erlaubt sind alle alphanumerischen Zeichen, Leerzeichen, Bindestrich und Punkt.
   * Leider kann man jetzt Leerzeichen, Bindestrich und Punkt mehrmals hintereinander eingeben,
   * Man kann Schriftzeichen aus allen Sprachen verwenden.
     * Am Ende ist noch ein Zusatz möglich, z. B. Müllerstraße 7b. Erlaubt sind Zeichen von A-Z, groß- oder
     * kleingeschrieben.
     */
    $pruefeAdresse = '/^[\w\-\s\.]+[a-zA-Z]?$/';


    /* Erlaubt sind Ziffern, Plus, Punkt, Klammern und Leerzeichen. Auch Schrägstriche und Bindestriche sind erlaubt.
     * So kann man verschiedene Schreibweisen von Telefonnummern eingeben.
     */
    $pruefeTelefon = '/\+?\(?[0-9]+\.?\s?\(?\)?\-?/';



    foreach ($eingabe as $schluessel=>$wert) {

        if($schluessel == 'name') {

                if($wert !== '' && preg_match($pruefeName, $wert)) {
                    $kontaktdatenOK[$zaehler] = '<p class="uppercase">Name: '.$wert.'</p>'.'<br>';
                    $kontaktdatenNichtOK[$zaehler] = '<label for "name">Name:</label><input type="text" name="name" value="'.$wert.'" />'.'<br>';
                    $zaehler++;
                } else {
                    $kontaktdatenNichtOK[$zaehler] = '<label for"name" class="rot">Name:</label><input type="text" name="name" />'.'<br>'
                                                     .'<p class="rot">Bitte geben Sie einen korrekten Namen ein!</p>';
                    $zaehler++;
                    $fehlerGefunden++;
                }
        }

        if($schluessel == 'adresse') {

                if($wert !== '' && preg_match($pruefeAdresse, $wert)) {
                    $kontaktdatenOK[$zaehler] = '<p class="uppercase">Adresse: '.$wert.'</p>'.'<br>';
                    $kontaktdatenNichtOK[$zaehler] = '<label for "adresse">Adresse:</label><input type="text" name="adresse" value="'.$wert.'" />'.'<br>';
                    $zaehler++;
                } else {
                    $kontaktdatenNichtOK[$zaehler] = '<label for "adresse" class="rot">Adresse:</label><input type="text" name="adresse" />'.'<br>'
                                                      .'<p class="rot">Bitte geben Sie eine gültige Adresse ein!</p>';
                    $zaehler++;
                    $fehlerGefunden++;
                }


        }

        if($schluessel == 'telefon') {

            if($wert !== '' && preg_match($pruefeTelefon, $wert)) {
                $kontaktdatenOK[$zaehler] = '<p class="uppercase">Telefon: '.$wert.'</p>'.'<br>';
                $kontaktdatenNichtOK[$zaehler] = '<label for "telefon">Telefon:</label><input type="text" name="telefon" value="'.$wert.'" />'.'<br>';
                $zaehler++;
            } else {
                $kontaktdatenNichtOK[$zaehler] = '<label for "telefon" class="rot">Telefon:</label><input type="text" name="telefon" />'.'<br>'
                    .'<p class="rot">Bitte geben Sie eine gültige Telefonnummer ein!</p>';
                $zaehler++;
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


//stellt die erste Seite dar, bekommt Array mit Formularfeldern
function erstelleFormularSeite1($felder) {


    echo '<h2 id="formular_headline">Meine Urlaubsziele</h2>';

    echo '<form action="index.php" method="Post">';

    echo '<fieldset>';

    foreach($felder as $value) {
        echo $value;
    }

    erstelleInput("hidden", "seite-1", "geklickt");
    erstelleInput("reset", "","reset");
    erstelleInput("submit", "","submit");

    echo '</fieldset>';

    echo '</form>';

}

/* stellt die zweite Seite dar, liest Daten aus der Textdatei 'daten.txt' ein
* bekommt ein Array Formularfeldern
 *
 */
function erstelleFormularSeite2($daten) {

        echo '<h2 id="formular_headline">Kontaktdaten eingeben!</h2>';

        print_r((file_get_contents('daten.txt')));

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


/* stellt die dritte Seite dar, bekommt ein Array mit Daten
 * speichert die Daten in die Datei 'daten.txt', gibt den
 * Inhalt der Datei auf der Seite aus
 */
function erstelleFormularSeite3($kontaktdaten) {

    echo '<h2 id="formular_headline">Wunschübersicht</h2>';

    $gespeicherteDaten = fopen("daten.txt", "a");
    foreach($kontaktdaten as $value=>$item) {
        fputs($gespeicherteDaten, $item);
    }

    fclose($gespeicherteDaten);

    print_r(file_get_contents('daten.txt'));

}
