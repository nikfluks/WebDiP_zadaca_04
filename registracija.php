<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <title>Registracija</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Registracija">
        <meta name="kljucne_rijeci" content="FOI,Web DiP">
        <meta name="datum_izrade" content="07.03.2017.">  
        <meta name="autor" content="Nikola Fluks">
        <link rel="stylesheet" media="screen" type="text/css" href="css/nikfluks.css">
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>

    <body>
        <?php
        if (isset($_POST["submit"])) {
            /* foreach ($_POST as $key => $value) {
              echo "Kljuc: " . $key . ", vrijednost: " . $value . "<br>";
              } */
            $predlozakSpecZnak = "/[(){}\'!#\"\\/]/";
            $predlozakLozinka = "/^(?=(.*[A-Z]){2,})(?=(.*[a-z]){2,})(?=(.*[0-9]){1,})[^\s]{5,15}$/";
            $predlozakEmail = "/^\w+@\w+\.\w+$/";

            //3. zadatak a i
            if (empty($_POST["ime"])) {
                $greskaIme = "Ime nije uneseno!";
            } else {
                if (preg_match_all($predlozakSpecZnak, $_POST["ime"])) {
                    $greskaImeSpec = "Ime sadrži specijalni znak!";
                }
            }
            if (empty($_POST["prezime"])) {
                $greskaPrezime = "Prezime nije uneseno!";
            } else {
                if (preg_match_all($predlozakSpecZnak, $_POST["prezime"])) {
                    $greskaPrezimeSpec = "Prezime sadrži specijalni znak!";
                }
            }
            if (empty($_POST["korIme"])) {
                $greskaKorIme = "Korisničko ime nije uneseno!";
            } else {
                if (preg_match_all($predlozakSpecZnak, $_POST["korIme"])) {
                    $greskaKorImeSpec = "Korisničko ime sadrži specijalni znak!";
                }
            }
            if (empty($_POST["email"])) {
                $greskaEmail = "Email nije unesen!";
            } else {
                if (preg_match_all($predlozakSpecZnak, $_POST["email"])) {
                    $greskaEmailSpec = "Email sadrži specijalni znak!";
                } else {
                    //3. zadatak a iv
                    if (!preg_match_all($predlozakEmail, $_POST["email"])) {
                        $greskaEmailFormat = "Email nije formata nesto@nesto.nesto!";
                    }
                }
            }
            if (empty($_POST["lozinka"])) {
                $greskaLozinka = "Lozinka nije unesena!";
            } else {
                if (preg_match_all($predlozakSpecZnak, $_POST["lozinka"])) {
                    $greskaLozinkaSpec = "Lozinka sadrži specijalni znak!";
                } else {
                    //3. zadatak a ii
                    if (!preg_match_all($predlozakLozinka, $_POST["lozinka"])) {
                        $greskaLozinkaKriva = "Lozinka mora sadržavati barem 2 velika i 2 mala slova, 1 broj i duljina 5-15 znakova!";
                    }
                }
            }
            if (empty($_POST["lozinka2"])) {
                $greskaPonLozinka = "Ponovljena lozinka nije unesena!";
            } else {
                if (preg_match_all($predlozakSpecZnak, $_POST["lozinka2"])) {
                    $greskaPonLozinkaSpec = "Ponovljena lozinka sadrži specijalni znak!";
                }
            }
            //3. zadatak a iii
            if (!isset($greskaLozinka) && !isset($greskaLozinkaSpec) && !isset($greskaLozinkaKriva)) {
                if ($_POST["lozinka"] !== $_POST["lozinka2"]) {
                    $razliciteLozinke = "Lozinka i ponovljena lozinka se ne podudaraju!";
                }
            }

            //3. zadatak a v
            include("baza.class.php");
            $veza = new Baza ();
            $veza->spojiDB();
            $rezultat = $veza->selectDB("SELECT * FROM korisnik WHERE korisnicko_ime='" . $_POST["korIme"] . "' or email='" . $_POST["email"] . "'");
            $red = $rezultat->fetch_array();

            if ($veza->pogreskaDB()) {
                echo "Problem kod upita na bazu!<br>";
            }

            if ($red["korisnicko_ime"] || $red["email"]) {
                $postojeciImeEmail = "Korisničko ime/email već postoji u bazi!";
            }
            //3. zadatak b i
            if (!isset($greskaIme) && !isset($greskaImeSpec) && !isset($greskaPrezime) && !isset($greskaPrezimeSpec) && !isset($greskaKorIme) && !isset($greskaKorImeSpec) && !isset($greskaEmail) && !isset($greskaEmailSpec) && !isset($greskaEmailFormat) && !isset($greskaLozinka) && !isset($greskaLozinkaSpec) && !isset($greskaLozinkaKriva) && !isset($greskaPonLozinka) && !isset($greskaPonLozinkaSpec) && !isset($razliciteLozinke) && !isset($postojeciImeEmail)) {
                $salt = sha1(time());
                $kriptirana_lozinka = sha1($salt . "--" . $_POST["lozinka"]);

                $saltAkt = sha1(time());
                $aktKod = sha1($saltAkt . $_POST["korIme"]);

                $upisiKorisnika = $veza->ostaliUpitiDB("INSERT INTO korisnik (ime,prezime,email,korisnicko_ime,lozinka,tip_korisnika_id,prijava_2koraka,aktivacijski_link,kriptirana_lozinka) "
                        . "VALUES ('" . $_POST["ime"] . "','" . $_POST["prezime"] . "','" . $_POST["email"] . "','" . $_POST["korIme"] . "','" . $_POST["lozinka"] . "',1," . $_POST["PrijavaU2"] . ",'$aktKod', '$kriptirana_lozinka' )");

                echo "Slanje maila!";
                $mail_to = $_POST["email"];
                $mail_from = "From: nikfluks@zadaca04.hr";
                $mail_subject = "Aktivacijski link";
                $mail_body = "Za aktivaciju pritisnite na link: http://barka.foi.hr/WebDiP/2016/zadaca_04/nikfluks/aktivacija.php?aktkod=" . $aktKod;

                echo $mail_body;
                if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
                    echo("Poslana poruka za: '$mail_to'!");
                } else {
                    echo("Problem kod poruke za: '$mail_to'!");
                }
            }
            $veza->zatvoriDB();
        }
        ?>


        <header>
            <h1 id="status">Registracija</h1>
            <figure id="logoSlika">
                <img src="slike/logo.png" usemap="#mapa1" alt="FOI" width="400" height="200">
                <map name="mapa1">
                    <area href="index.html" alt="logo" shape="rect" coords="0,0,200,200" target="index"/>
                    <area href="#registracija_nav" alt="logo" shape="rect" coords="200,0,400,200" />
                </map>
                <figcaption id="logoCap">Interaktivna slika</figcaption>
            </figure>
        </header>

        <nav id="registracija_nav">
            <ul>
                <li><a href="registracija.php"> Registracija</a></li>
                <li><a href="prijava.php"> Prijava</a></li>
                <li><a href="novi_proizvod.php"> Novi proizvod</a></li>
                <li><a href="dnevnik.php"> Dnevnik</a></li>
                <li><a href="otkljucavanje_korisnika.php"> Otključavanje korisnika</a></li>
                <li><a href="aktivacija.php"> Aktivacija</a></li>
            </ul> 
        </nav>

        <section class="registracija">
            <h2>Registracija</h2>
            <div id="greske_reg">
                <?php
                if (isset($greskaIme)) {
                    echo $greskaIme . "<br>";
                } else {
                    if (isset($greskaImeSpec)) {
                        echo $greskaImeSpec . "<br>";
                    }
                }
                if (isset($greskaPrezime)) {
                    echo $greskaPrezime . "<br>";
                } else {
                    if (isset($greskaPrezimeSpec)) {
                        echo $greskaPrezimeSpec . "<br>";
                    }
                }
                if (isset($greskaKorIme)) {
                    echo $greskaKorIme . "<br>";
                } else {
                    if (isset($greskaKorImeSpec)) {
                        echo $greskaKorImeSpec . "<br>";
                    }
                }
                if (isset($greskaEmail)) {
                    echo $greskaEmail . "<br>";
                } else {
                    if (isset($greskaEmailSpec)) {
                        echo $greskaEmailSpec . "<br>";
                    } else {
                        if (isset($greskaEmailFormat)) {
                            echo $greskaEmailFormat . "<br>";
                        }
                    }
                }
                if (isset($greskaLozinka)) {
                    echo $greskaLozinka . "<br>";
                } else {
                    if (isset($greskaLozinkaSpec)) {
                        echo $greskaLozinkaSpec . "<br>";
                    } else {
                        if (isset($greskaLozinkaKriva)) {
                            echo $greskaLozinkaKriva . "<br>";
                        }
                    }
                }
                if (isset($greskaPonLozinka)) {
                    echo $greskaPonLozinka . "<br>";
                } else {
                    if (isset($greskaPonLozinkaSpec)) {
                        echo $greskaPonLozinkaSpec . "<br>";
                    }
                }

                if (isset($razliciteLozinke)) {
                    echo $razliciteLozinke . "<br>";
                }
                if (isset($postojeciImeEmail)) {
                    echo $postojeciImeEmail . "<br>";
                }
                ?>
            </div>
            <form method="POST" name="registracija"
                  action="<?php echo $_SERVER["PHP_SELF"]; ?>" novalidate>
                <label for="ime">Ime:</label>
                <input type="text" id="ime" name="ime" size="30" placeholder="Ime"><br>
                <label for="prezime">Prezime:</label>
                <input type="text" id="prezime" name="prezime" size="30" placeholder="Prezime"><br>
                <label for="korIme">Korisničko ime:</label>
                <input type="text" id="korIme" name="korIme" size="30" placeholder="Korisničko ime" required="required"><br>
                <label for="email">E-mail adresa:</label>
                <input type="email" id="email" name="email" size="30" placeholder="ime.prezime@posluzitelj.xxx" required="required"><br>
                <label for="lozinka">Lozinka:</label>
                <input type="password" id="lozinka" name="lozinka" size="30" placeholder="Lozinka" required="required"><br>
                <label for="lozinka2">Ponovi lozinku:</label>
                <input type="password" id="lozinka2" name="lozinka2" size="30" placeholder="Ponovi lozinku" required="required"><br>

                <label id="zapamtiMeL" for="zapamtiMeDa">Prijava u 2 koraka?</label>
                <input type="radio" id="zapamtiMeDa" name="PrijavaU2" value="1" />DA
                <label id="zapamtiMeLBrisi" for="zapamtiMeNe">Prijava u 2 koraka?</label>
                <input type="radio" id="zapamtiMeNe" name="PrijavaU2" value="0" checked="checked"/>NE<br>

                <input type="submit" id="posaljiReg" name="submit" value="Registracija"> 
                <div class="g-recaptcha" data-sitekey="6LcYex8UAAAAAHdDN08_mkh7BC756tKxXzaetLuv"></div>
            </form>
        </section>

        <footer>
            <p>Vrijeme potrebno za rješavanje aktivnog dokumenta: 4h </p>
            <a href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2016%2Fzadaca_01%2Fnikfluks%2Fregistracija.html" target="html5">
                <figure id="html5">
                    <img src="slike/HTML5.png" alt="HTML5" width="100" height="100">
                    <figcaption>HTML5 validator</figcaption>
                </figure>
            </a>
            <a href="http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2016%2Fzadaca_01%2Fnikfluks%2Fnikfluks.css&AMP;profile=css3&AMP;usermedium=all&AMP;warning=1&AMP;vextwarning=&AMP;lang=en" target="css3">
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


