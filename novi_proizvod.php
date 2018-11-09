<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
//2. zadatak a
$cookieIme = "nikfluks_WebDiP";
if (!isset($_COOKIE[$cookieIme])) {
    //echo "Kuki " . $cookieIme . " NE postoji!<br>";
    $cookieVrijediDo = time() + (10 * 60);
    setcookie($cookieIme, $cookieVrijediDo, $cookieVrijediDo, "/");
} else {
    //echo "Kuki " . $cookieIme . " postoji!<br>";
}
?>

<html>
    <head>
        <title>Novi proizvod</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="naslov" content="Novi proizvod">
        <meta name="kljucne_rijeci" content="FOI,Web DiP">
        <meta name="datum_izrade" content="07.03.2017.">  
        <meta name="autor" content="Nikola Fluks">
        <link rel="stylesheet" media="screen" type="text/css" href="css/nikfluks.css">
    </head>

    <body>
        <?php
        if (isset($_POST["submit"])) {
            /*foreach ($_POST as $key => $value) {
                echo "Kljuc: " . $key . ", vrijednost: " . $value . "<br>";
            }*/
            //2. zadadatak b v
            if (isset($_COOKIE[$cookieIme]) && ($_COOKIE[$cookieIme] - time()) <= 5 * 60) {
                $vrijemeIstekaCookia = $_COOKIE[$cookieIme];
                $trenutno = time();
                $razlika = ($vrijemeIstekaCookia - $trenutno);
                $razlikaMin = (int) ($razlika / 60);
                $razlikaSec = round($razlika % 60);
                $vrijemeJeProslo = "Prošlo je 5 min od posjeta stranici, čekajte do isteka kolačića pa osvježite stranicu! " . $razlikaMin . " min " . $razlikaSec . " sec";
            } else {
                $predlozakSpecZnak = "/[(){}\'!#\"\\/]/";
                $predlozakNaziv = "/^[A-Z]\w{4,}/";
                $predlozakDatum = "/^\d\d\.\d\d\.\d{4,4}$/";
                //2. zadatak b i
                if (empty($_POST["nazivProizvoda"])) {
                    $greskaNaziv = "Naziv proizvoda nije unesen!";
                } else {
                    if (preg_match_all($predlozakSpecZnak, $_POST["nazivProizvoda"])) {
                        $greskaNazivSpec = "Naziv proizvoda sadrži specijalni znak!";
                    } else {
                        //2. zadatak b ii
                        if (!preg_match_all($predlozakNaziv, $_POST["nazivProizvoda"])) {
                            $greskaNazivDuljina = "Naziv proizvoda mora početi velikim slovom i imati najmanje 5 znakova!";
                        }
                    }
                }
                if (empty($_POST["opisProizvoda"])) {
                    $greskaOpis = "Opis proizvoda nije unesen!";
                } else {
                    if (preg_match_all($predlozakSpecZnak, $_POST["opisProizvoda"])) {
                        $greskaOpisSpec = "Opis proizvoda sadrži specijalni znak!";
                    }
                }
                if (empty($_POST["datumProizvodnje"])) {
                    $greskaDatum = "Datum proizvodnje nije unesen!";
                } else {
                    if (preg_match_all($predlozakSpecZnak, $_POST["datumProizvodnje"])) {
                        $greskaDatumSpec = "Datum proizvodnje sadrži specijalni znak!";
                    } else {
                        //2. zadatak b iii
                        if (!preg_match_all($predlozakDatum, $_POST["datumProizvodnje"])) {
                            $greskaDatumFormat = "Datum nije u formatu dd.mm.gggg!";
                        } else {
                            $datumPro = $_POST["datumProizvodnje"];
                            $datumProPolje = explode(".", $datumPro);
                            if ($datumProPolje[0] > 31) {
                                $datumDani = "Mjesec ima najviše 31 dan!";
                            } elseif ($datumProPolje[1] > 12) {
                                $datumMjeseci = "Godina ima najviše 12 mjeseci!";
                            } elseif (new DateTime("$datumProPolje[2]-$datumProPolje[1]-$datumProPolje[0]") > new DateTime) {
                                $datumVeci = "Uneseni datum je veći od trenutnog!";
                            }
                        }
                    }
                }
                if (empty($_POST["vrijemeProizvodnje"])) {
                    $greskaVrijeme = "Vrijeme proizvodnje nije uneseno!";
                } else {
                    if (preg_match_all($predlozakSpecZnak, $_POST["vrijemeProizvodnje"])) {
                        $greskaVrijemeSpec = "Vrijeme proizvodnje sadrži specijalni znak!";
                    }
                }
                if (empty($_POST["kolicinaProizvoda"])) {
                    $greskaKolicina = "Količina proizvoda nije unesena!";
                } else {
                    if (preg_match_all($predlozakSpecZnak, $_POST["kolicinaProizvoda"])) {
                        $greskaKolicinaSpec = "Količina proizvoda sadrži specijalni znak!";
                    }
                }
                //2. zadatak b iv
                if (!isset($_POST["kategorijaProizvoda11"]) && !isset($_POST["kategorijaProizvoda22"]) && !isset($_POST["kategorijaProizvoda33"])) {
                    $neoznaceneKategorije = "Niti jedna kategorija nije označena!";
                }
            }
        }
        ?>

        <?php
        //2. zadatak c i
        include("baza.class.php");
        $veza = new Baza ();
        $veza->spojiDB();
        if (isset($_POST["submit"]) && !isset($vrijemeJeProslo) && !isset($greskaNaziv) && !isset($greskaNazivSpec) && !isset($greskaNazivDuljina) && !isset($greskaOpis) && !isset($greskaOpisSpec) && !isset($greskaDatum) && !isset($greskaDatumSpec) && !isset($greskaDatumFormat) && !isset($datumDani) && !isset($datumMjeseci) && !isset($datumVeci) && !isset($greskaVrijeme) && !isset($greskaVrijemeSpec) && !isset($greskaKolicina) && !isset($greskaKolicinaSpec) && !isset($neoznaceneKategorije)) {
            $kategorja = "";
            if (isset($_POST["kategorijaProizvoda11"])) {
                $kategorja .= $_POST["kategorijaProizvoda11"] . ";";
            }
            if (isset($_POST["kategorijaProizvoda22"])) {
                $kategorja .= $_POST["kategorijaProizvoda22"] . ";";
            }
            if (isset($_POST["kategorijaProizvoda33"])) {
                $kategorja .= $_POST["kategorijaProizvoda33"] . ";";
            }

            $uneseniDatumString = $_POST["datumProizvodnje"];
            $uneseniDatumDate = strtotime($uneseniDatumString);
            $uneseniDatumDateFormatirani = date("Y-m-d", $uneseniDatumDate);

            $rezultatUpd = $veza->ostaliUpitiDB("INSERT INTO proizvod (naziv,opis,datum,vrijeme,kolicina,tezina,kategorija) "
                    . "VALUES ('" . $_POST["nazivProizvoda"] . "', '" . $_POST["opisProizvoda"] . "', '$uneseniDatumDateFormatirani', '" . $_POST["vrijemeProizvodnje"] . "', " . $_POST["kolicinaProizvoda"] . ", " . $_POST["tezinaProizvodaLvl"] . ", '$kategorja')");
            echo $rezultatUpd . "<br>";

            /*$rezultat = $veza->selectDB("SELECT * FROM proizvod");

            if ($veza->pogreskaDB()) {
                echo "Problem kod upita na bazu!<br>";
            }

            if ($rezultat != null) {
                print "<table border=1><tr><th>proizvod_id</th><th>naziv</th><th>opis</th><th>datum</th><th>vrijeme</th><th>kolicina</th><th>tezina</th><th>kategorija</th></tr>\n";

                while (list($proizvod_id, $naziv, $opis, $datum, $vrijeme, $kolicina, $tezina, $kategorija) = $rezultat->fetch_array()) {
                    print "<tr><td>$proizvod_id</td><td>$naziv</td><td>$opis</td><td>$datum</td><td>$vrijeme</td><td>$kolicina</td><td>$tezina</td><td>$kategorija</td></tr>\n";
                }
                print "</table>\n";
            }*/
            //2. zadatak c iii
            $datumUnosa = date("Y-m-d");
            $vrijemeUnosa = date("H:i:s");
            $rezultatUpd2 = $veza->ostaliUpitiDB("INSERT INTO dnevnik (naziv_proizvoda,datum,vrijeme,korisnik) "
                    . "VALUES ('Ispravno unesen proizvod: " . $_POST["nazivProizvoda"] . "','$datumUnosa', '$vrijemeUnosa','Neregistrirani korisnik')");

            $veza->zatvoriDB();

            //2. zadatak c ii
            echo "Vrijednosti u cookie-u:" . "<br>" . "Naziv: " . $cookieIme . "<br>" . "Vrijednost: " . $_COOKIE[$cookieIme] . "<br>";
            if (isset($_COOKIE[$cookieIme])) {
                unset($_COOKIE[$cookieIme]);
                setcookie($cookieIme, null, -3600, "/");
            }
        }
        ?>

        <header>
            <h1>Novi proizvod</h1>
            <figure id="logoSlika">
                <img src="slike/logo.png" usemap="#mapa1" alt="FOI" width="400" height="200">
                <map name="mapa1">
                    <area href="index.html" alt="logo" shape="rect" coords="0,0,200,200" target="index" />
                    <area href="#nazivProizvoda" alt="logo" shape="rect" coords="200,0,400,200" />
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

        <section class="dodavanje_novog_proizvoda">
            <h2>Dodavanje novog proizvoda</h2>
            <div id="greske_novi_proizv">
                <?php
                if (isset($vrijemeJeProslo)) {
                    echo $vrijemeJeProslo . "<br>";
                } else {
                    if (isset($greskaNaziv)) {
                        echo $greskaNaziv . "<br>";
                    } else {
                        if (isset($greskaNazivSpec)) {
                            echo $greskaNazivSpec . "<br>";
                        } else {
                            if (isset($greskaNazivDuljina)) {
                                echo $greskaNazivDuljina . "<br>";
                            }
                        }
                    }
                    if (isset($greskaOpis)) {
                        echo $greskaOpis . "<br>";
                    } else {
                        if (isset($greskaOpisSpec)) {
                            echo $greskaOpisSpec . "<br>";
                        }
                    }
                    if (isset($greskaDatum)) {
                        echo $greskaDatum . "<br>";
                    } else {
                        if (isset($greskaDatumSpec)) {
                            echo $greskaDatumSpec . "<br>";
                        } else {
                            if (isset($greskaDatumFormat)) {
                                echo $greskaDatumFormat . "<br>";
                            } else {
                                if (isset($datumDani)) {
                                    echo $datumDani . "<br>";
                                } elseif (isset($datumMjeseci)) {
                                    echo $datumMjeseci . "<br>";
                                } elseif (isset($datumVeci)) {
                                    echo $datumVeci . "<br>";
                                }
                            }
                        }
                    }
                    if (isset($greskaVrijeme)) {
                        echo $greskaVrijeme . "<br>";
                    } else {
                        if (isset($greskaVrijemeSpec)) {
                            echo $greskaVrijemeSpec . "<br>";
                        }
                    }
                    if (isset($greskaKolicina)) {
                        echo $greskaKolicina . "<br>";
                    } else {
                        if (isset($greskaKolicinaSpec)) {
                            echo $greskaKolicinaSpec . "<br>";
                        }
                    }
                    if (isset($neoznaceneKategorije)) {
                        echo $neoznaceneKategorije . "<br>";
                    }
                }
                ?>
            </div>
            <form method="POST" name="dodovanje_novog_proizvoda" id="novi_proizvod_form" novalidate
                  action="<?php echo $_SERVER["PHP_SELF"]; ?>" 
                  oninput="level.value = tezinaProizvodaLvl.valueAsNumber">
                <label class="dodavanje_proizvodaL" for="nazivProizvoda">Naziv proizvoda:<?php
                    if (isset($greskaNaziv) || isset($greskaNazivSpec) || isset($greskaNazivDuljina)) {
                        echo '<span style="color:red;font-size:20px;">!</span>';
                    }
                    ?>
                </label>
                <input class="dodavanje_proizvodaI" type="text" id="nazivProizvoda" name="nazivProizvoda" size="15" maxlength="15" placeholder="Naziv proizvoda" autofocus="autofocus" 
                <?php
                if (isset($vrijemeJeProslo)) {
                    echo 'disabled="disabled"';
                }
                ?>><br>
                <label class="dodavanje_proizvodaL" for="opisProizvoda">Opis proizvoda:<?php
                    if (isset($greskaOpis) || isset($greskaOpisSpec)) {
                        echo '<span style="color:red;font-size:20px;">!</span>';
                    }
                    ?>
                </label>
                <textarea id="opisProizvoda" name="opisProizvoda" rows="50" cols="100" placeholder="Ovdje unesite opis proizvoda"
                <?php
                if (isset($vrijemeJeProslo)) {
                    echo 'disabled="disabled"';
                }
                ?>></textarea><br>
                <label class="dodavanje_proizvodaL" for="datumProizvodnje">Datum proizvodnje:<?php
                    if (isset($greskaDatum) || isset($greskaDatumSpec) || isset($greskaDatumFormat) || isset($datumDani) || isset($datumMjeseci) || isset($datumVeci)) {
                        echo '<span style="color:red;font-size:20px;">!</span>';
                    }
                    ?>
                </label>
                <input class="dodavanje_proizvodaI" type="text" id="datumProizvodnje" name="datumProizvodnje" placeholder="dd.mm.gggg"
                <?php
                if (isset($vrijemeJeProslo)) {
                    echo 'disabled="disabled"';
                }
                ?>><br>
                <label class="dodavanje_proizvodaL" for="vrijemeProizvodnje">Vrijeme proizvodnje:<?php
                    if (isset($greskaVrijeme) || isset($greskaVrijemeSpec)) {
                        echo '<span style="color:red;font-size:20px;">!</span>';
                    }
                    ?>
                </label>
                <input class="dodavanje_proizvodaI" type="text" id="vrijemeProizvodnje" name="vrijemeProizvodnje"
                <?php
                if (isset($vrijemeJeProslo)) {
                    echo 'disabled="disabled"';
                }
                ?>><br>
                <label class="dodavanje_proizvodaL" for="kolicinaProizvoda">Količina proizvoda:<?php
                    if (isset($greskaKolicina) || isset($greskaKolicinaSpec)) {
                        echo '<span style="color:red;font-size:20px;">!</span>';
                    }
                    ?>
                </label>
                <input class="dodavanje_proizvodaI" type="number" id="kolicinaProizvoda" name="kolicinaProizvoda" min="1"
                <?php
                if (isset($vrijemeJeProslo)) {
                    echo 'disabled="disabled"';
                }
                ?>><br>
                <label class="dodavanje_proizvodaL" for="tezinaProizvoda">Težina proizvoda:</label>
                <input class="dodavanje_proizvodaI" type="range" id="tezinaProizvoda" name="tezinaProizvodaLvl" min="0" max="100" value="0"
                <?php
                if (isset($vrijemeJeProslo)) {
                    echo 'disabled="disabled"';
                }
                ?>>
                <output for="tezinaProizvoda" name=level>0</output>/100<br>

                <label class="dodavanje_proizvodaL" for="kategorijaProizvoda">Kategorija proizvoda:<?php
                    if (isset($neoznaceneKategorije)) {
                        echo '<span style="color:red;font-size:20px;">!</span>';
                    }
                    ?>
                </label>
                <input class="kategorijaProizvoda" type="checkbox" id="kategorijaProizvoda" name="kategorijaProizvoda11" value="Tehnika" checked="checked"
                <?php
                if (isset($vrijemeJeProslo)) {
                    echo 'disabled="disabled"';
                }
                ?>>Tehnika<br>
                <label class="dodavanje_proizvodaLBrisi" for="kategorijaProizvoda2">Kategorija proizvoda:</label>
                <input class="kategorijaProizvoda" type="checkbox" id="kategorijaProizvoda2" name="kategorijaProizvoda22" value="Hrana"
                <?php
                if (isset($vrijemeJeProslo)) {
                    echo 'disabled="disabled"';
                }
                ?>>Hrana<br>
                <label class="dodavanje_proizvodaLBrisi" for="kategorijaProizvoda3">Kategorija proizvoda:</label>
                <input class="kategorijaProizvoda" type="checkbox" id="kategorijaProizvoda3" name="kategorijaProizvoda33" value="Piće"
                <?php
                if (isset($vrijemeJeProslo)) {
                    echo 'disabled="disabled"';
                }
                ?>>Piće<br>

                <input type="submit" id="spremiProizvod" name="submit" value="Spremi"
                <?php
                if (isset($vrijemeJeProslo)) {
                    echo 'disabled="disabled"';
                }
                ?>>
                <input type="reset" id="reset1" value="Inicijaliziraj"                      
                <?php
                if (isset($vrijemeJeProslo)) {
                    echo 'disabled="disabled"';
                }
                ?>>
            </form>
        </section>

        <footer>
            <p>Vrijeme potrebno za rješavanje aktivnog dokumenta: 1h </p>
            <a href="https://validator.w3.org/nu/?doc=http%3A%2F%2Fbarka.foi.hr%2FWebDiP%2F2016%2Fzadaca_03%2Fnikfluks%2Fnovi_proizvod.html" target="html5">
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
