<!DOCTYPE html>
<?php
include("baza.class.php");
$bp = new Baza();

if (isset($_POST["otkljucaj"])) {
    $kor_ime = $_POST['otkljucaj'];
    $bp->spojiDB();
    $sql = "UPDATE `korisnik` SET `broj_unosa`='0' WHERE `korisnicko_ime`='$kor_ime'";
    $otkljucaj = $bp->ostaliUpitiDB($sql);
    echo "Korisnik " . $kor_ime . " je otključan";
    // $otkljucaj->close();
    $bp->zatvoriDB();
}
?>

<html>
    <head>
        <title>Otključaj korisnika</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Otključavanje korisnika">
        <meta name="kljucne_rijeci" content="FOI,Web DiP">
        <meta name="datum_izrade" content="07.03.2017.">  
        <meta name="autor" content="Nikola Fluks">
        <link rel="stylesheet" media="screen" type="text/css" href="css/nikfluks.css">
    </head>

    <body>
        <header>
            <h1>Otključavanje korisnika</h1>
            <figure id="logoSlika">
                <img src="slike/logo.png" usemap="#mapa1" alt="FOI" width="400" height="200">
                <map name="mapa1">
                    <area href="index.html" alt="logo" shape="rect" coords="0,0,200,200" target="index" />
                    <area href="#novi_proizvod_nav" alt="logo" shape="rect" coords="200,0,400,200" />
                </map>
                <figcaption id="logoCap">Interaktivna slika</figcaption>
            </figure>
        </header>

        <nav id="novi_proizvod_nav">
            <ul>
                <li><a href="registracija.php"> Registracija</a></li>
                <li><a href="prijava.php"> Prijava</a></li>
                <li><a href="novi_proizvod.php"> Novi proizvod</a></li>
                <li><a href="dnevnik.php"> Dnevnik</a></li>
                <li><a href="otkljucavanje_korisnika.php"> Otključavanje korisnika</a></li>
                <li><a href="aktivacija.php"> Aktivacija</a></li>
            </ul> 
        </nav>

        <form name="otkljucaj" action="otkljucavanje_korisnika.php" method="POST">
            <?php
            $sql = "SELECT * FROM korisnik WHERE `broj_unosa`=3";
            $bp->spojiDB();
            $rs = $bp->selectDB($sql);

            if ($bp->pogreskaDB()) {
                echo "Problem kod upita na bazu podataka!";
                exit;
            }

            while (list($id, $ime, $prezime, $email, $kor_ime, $lozinka) = $rs->fetch_array()) {
                echo '<label class="otkljucavanjeLabele" id=' . $id . ' for=' . $kor_ime . '>Korisničko ime: ' . $kor_ime . '</label>';
                echo "<input type='radio' id='$kor_ime' name='otkljucaj' value='$kor_ime' /><br>";
            }
            print "</table>\n";

            $rs->close();
            $bp->zatvoriDB();
            ?>
            <input type="submit" id="posaljiPrijavu" name="posaljiPrijavu" value="Otključaj">
        </form>
        <footer>
            <p>Vrijeme potrebno za rješavanje aktivnog dokumenta: 3h </p>
            <a href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2016%2Fzadaca_03%2Fnikfluks%2Fprijava.html" target="html5">
                <figure id="html5">
                    <img src="slike/HTML5.png" alt="HTML5" width="100" height="100">
                    <figcaption>HTML5 validator</figcaption>
                </figure>
            </a>
            <a href="http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2016%2Fzadaca_03%2Fnikfluks%2Fcss%2Fnikfluks.css&amp;profile=css3&amp;usermedium=all&amp;warning=1&amp;vextwarning=&amp;lang=en" target="css3">
                <figure id="css3">
                    <img src="slike/CSS3.png" alt="CSS3" width="100" height="100">
                    <figcaption>CSS3 validator</figcaption>
                </figure> 
            </a>
            <address id="mail"><strong>Kontakt: <a href="mailto:nikfluks@foi.hr">Nikola Fluks</a></strong></address>
            <p><small>&copy; 2017. N. Fluks </small></p>
        </footer>
    </body>
</html>