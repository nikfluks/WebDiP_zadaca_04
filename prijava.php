<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include("baza.class.php");
$bp = new Baza();
if (isset($_POST["posaljiPrijavu"])) {
    $kor_ime = $_POST["korImePrijava"];
    $lozinka = $_POST["lozPrijava"];
    
    //prvo provjeriti dal je korisnik aktiviran!!
    
    if (isset($kor_ime)) {
        $sql = "SELECT * FROM korisnik WHERE `korisnicko_ime`='$kor_ime'";
        $bp->spojiDB();
        $rs = $bp->selectDB($sql);
        $odgovor = mysqli_fetch_array($rs);
        if (empty($kor_ime)) {
            echo "Niste unjeli korisničko ime!";
        } else if ($odgovor["korisnicko_ime"] == $kor_ime) {
            // echo "korisnik postoji u bazi";
            if ($lozinka == $odgovor["lozinka"] && $odgovor["broj_unosa"] < 3 && $odgovor["prijava_2koraka"] == 0 && $odgovor["aktiviran"] == 1) {
                echo "Prijavili ste se!";
                $sql = "UPDATE `korisnik` SET `broj_unosa`='0'  WHERE `korisnicko_ime`='$kor_ime'";
                $rs = $bp->ostaliUpitiDB($sql);
            } else if ($lozinka == $odgovor["lozinka"] && $odgovor["broj_unosa"] < 3 && $odgovor["prijava_2koraka"] == 1 && empty($_POST["token"]) && $odgovor["aktiviran"] == 1) {
                echo "Imas prijavu u 2 koraka! <br>";
                $sql = "UPDATE `korisnik` SET `broj_unosa`='0'  WHERE `korisnicko_ime`='$kor_ime'";
                $rs = $bp->ostaliUpitiDB($sql);
                $salt = sha1(time());
                $token = sha1($salt);

                echo "Slanje maila! <br>";
                $mail_to = $odgovor["email"];
                $mail_from = "From: nikfluks@zadaca04.hr";
                $mail_subject = "Token";
                $mail_body = "Vaš token: " . $token . "<br>";
                $sql = "UPDATE `korisnik` SET `token`='$token'  WHERE `korisnicko_ime`='$kor_ime'";
                $rs = $bp->ostaliUpitiDB($sql);

                echo $mail_body;
                if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
                    echo("Poslana poruka za: '$mail_to'!");
                } else {
                    echo("Problem kod poruke za: '$mail_to'!");
                }
            } else if ($lozinka == $odgovor["lozinka"] && $odgovor["broj_unosa"] < 3 && $odgovor["prijava_2koraka"] == 1 && $odgovor["token"] == $_POST["token"] && $odgovor["aktiviran"] == 1) {
                echo "Prijavili ste se!<br>";
                $sql = "UPDATE `korisnik` SET `broj_unosa`='0'  WHERE `korisnicko_ime`='$kor_ime'";
                $rs = $bp->ostaliUpitiDB($sql);
            } else if ($odgovor["broj_unosa"] < 3) {
                $broj = $odgovor["broj_unosa"] + 1;
                echo "Pogrešno ste se prijavili: " . $broj . ". put<br>";
                $sql = "UPDATE `korisnik` SET `broj_unosa`='$broj'  WHERE `korisnicko_ime`='$kor_ime'";
                $rs = $bp->ostaliUpitiDB($sql);
            } else {
                if ($odgovor["aktiviran"] == 0) {
                    echo "Korisnik nije aktiviran!<br>";
                }
                if ($odgovor["broj_unosa"] == 3) {
                    echo "Korisnik je zaključan u bazi, molimo vas da ga otključate!";
                }
            }
        } else {
            echo "Korisnik ne postoji u bazi!";
        }
    }
}
?>

<html>
    <head>
        <title>Prijava</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Prijava">
        <meta name="kljucne_rijeci" content="FOI,Web DiP">
        <meta name="datum_izrade" content="07.03.2017.">  
        <meta name="autor" content="Nikola Fluks">
        <link rel="stylesheet" media="screen" type="text/css" href="css/nikfluks.css">
    </head>

    <body>
        <header>
            <h1>Prijava</h1>
            <figure id="logoSlika">
                <img src="slike/logo.png" usemap="#mapa1" alt="FOI" width="400" height="200">
                <map name="mapa1">
                    <area href="index.html" alt="logo" shape="rect" coords="0,0,200,200" target="index" />
                    <area href="#korImeL" alt="logo" shape="rect" coords="200,0,400,200" />
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
        <div>
            <?php
            echo "Korisničko ime: nikfluks, lozinka: NNnn11";
            ?>
        </div>

        <section class="prijava">
            <h2>Prijava</h2>
            <form method="POST" name="prijava" id="prijava"
                  action="prijava.php" novalidate="">
                <label id="korImeL" for="korImePrijava">Korisničko ime:</label>
                <input type="text" id="korImePrijava" name="korImePrijava" size="20" placeholder="Korisničko ime" required="required" autofocus="autofocus"><br>
                <label id="lozinkaL" for="lozPrijava">Lozinka:</label>
                <input type="password" id="lozPrijava" name="lozPrijava" size="20" placeholder="Lozinka" required="required"><br>
                <label id="token" for="token1">Token:</label>
                <input type="text" id="token1" name="token"  placeholder="token" required="required" ><br>
                <label id="zapamtiMeL" for="zapamtiMeDa">Zapamti me:</label>
                <input type="radio" id="zapamtiMeDa" name="zapamtiMe" value="DA" />DA
                <label id="zapamtiMeLBrisi" for="zapamtiMeNe">Zapamti me:</label>
                <input type="radio" id="zapamtiMeNe" name="zapamtiMe" value="NE" checked="checked"/>NE<br>

                <input type="submit" id="posaljiPrijavu" name="posaljiPrijavu" value="Prijava">
                <a id="neReg" href="registracija.html"> Registracija</a>
            </form>
        </section>

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
