<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
if (isset($_GET["aktkod"])) {
    $kod = $_GET["aktkod"];
    include("baza.class.php");
    $bp = new Baza();
    $bp->spojiDB();
    $sql1 = "SELECT * FROM korisnik WHERE `aktivacijski_link`='$kod'";
    $dohvati = $bp->selectDB($sql1);
    $rjesenje = mysqli_fetch_array($dohvati);
    if (empty($rjesenje)) {
        echo "Nepostojeći aktivacijski kod!<br>";
        echo "<a href='prijava.php'>Prijava</a>";
        $bp->zatvoriDB();
    } else if ($rjesenje["aktiviran"] == 1) {
        echo "Korisnički račun je već aktiviran!<br>";
        echo "<a href='prijava.php'>Prijava</a>";
        $bp->zatvoriDB();
    } else {
        $sql = "UPDATE `korisnik` SET `aktiviran`='1' WHERE `aktivacijski_link`='$kod'";
        $otkljucaj = $bp->ostaliUpitiDB($sql);
        echo "Korisnik je aktiviran!<br>";
        echo "<a href='prijava.php'>Prijava</a>";
        $bp->zatvoriDB();
    }
} else {
    echo "Niste unijeli aktivacijski kod!<br>";
    echo '<a href="prijava.php">Prijava</a>';
}
?>

<html>
    <head>
        <title>Aktivacija</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Aktivacija">
        <meta name="kljucne_rijeci" content="FOI,Web DiP">
        <meta name="datum_izrade" content="07.03.2017.">  
        <meta name="autor" content="Nikola Fluks">
        <link rel="stylesheet" media="screen" type="text/css" href="css/nikfluks.css">    
    </head>

    <body>
        <header>
            <h1>Aktivacija</h1>
            <figure id="logoSlika">
                <img src="slike/logo.png" usemap="#mapa1" alt="FOI" width="400" height="200">
                <map name="mapa1">
                    <area href="index.html" alt="logo" shape="rect" coords="0,0,200,200" target="index" />
                    <area href="#prijava_nav" alt="logo" shape="rect" coords="200,0,400,200" />
                </map>
                <figcaption id="logoCap">Interaktivna slika</figcaption>
            </figure>
        </header>

        <nav id="prijava_nav">
            <ul>
                <li><a href="registracija.php"> Registracija</a></li>
                <li><a href="prijava.php"> Prijava</a></li>
                <li><a href="novi_proizvod.php"> Novi proizvod</a></li>
                <li><a href="dnevnik.php"> Dnevnik</a></li>
                <li><a href="otkljucavanje_korisnika.php"> Otključavanje korisnika</a></li>
                <li><a href="aktivacija.php"> Aktivacija</a></li>
            </ul> 
        </nav>

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


