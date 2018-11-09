<?php
//1. zadatak
class Baza {

    const server = "localhost";
    const korisnik = "WebDiP2016x038";
    const lozinka = "admin_8in2";
    const baza = "WebDiP2016x038";

    private $veza = null;
    private $greska = '';

    function spojiDB() {
        $this->veza = new mysqli(self::server, self::korisnik, self::lozinka, self::baza);
        if ($this->veza->connect_errno) {
            echo "Neuspješno spajanje na bazu: " . $this->veza->connect_errno . ", " .
            $this->veza->connect_error;
            $this->greska = $this->veza->connect_error;
        }
        $this->veza->set_charset("utf8");
        if ($this->veza->connect_errno) {
            echo "Neuspješno postavljanje znakova za bazu: " . $this->veza->connect_errno . ", " .
            $this->veza->connect_error;
            $this->greska = $this->veza->connect_error;
        }
        return $this->veza;
    }

    function zatvoriDB() {
        $this->veza->close();
    }

    function selectDB($upit) {
        $provjeriSelect = explode(" ", $upit);
        $rezultat = null;

        if (strtolower($provjeriSelect[0]) === 'select') {
            $rezultat = $this->veza->query($upit);
            if ($this->veza->connect_errno) {
                echo "Greška kod upita: {$upit} - " . $this->veza->connect_errno . ", " .
                $this->veza->connect_error;
                $this->greska = $this->veza->connect_error;
            }
            if (!$rezultat) {
                echo "Greška u SELECT upitu!<br>";
                $rezultat = null;
            }
        } else {
            $rezultat = null;
            echo "Upit mora biti tipa SELECT!<br>";
        }
        return $rezultat;
    }

    function ostaliUpitiDB($upit, $skripta = '') {
        $provjeriOstali = explode(" ", $upit);
        $rezultat = null;
        $uspjeh = "uspjeh";

        if (strtolower($provjeriOstali[0]) !== 'select') {
            $rezultat = $this->veza->query($upit);
            if ($rezultat == null) {
                $uspjeh = "neuspjeh";
            }
            if ($this->veza->connect_errno) {
                echo "Greška kod upita: {$upit} - " . $this->veza->connect_errno . ", " .
                $this->veza->connect_error;
                $this->greska = $this->veza->connect_error;
                $uspjeh = "neuspjeh";
            } else {
                if ($skripta != '') {
                    header("Location: $skripta");
                }
            }
        } else {
            $rezultat = null;
            $uspjeh = "neuspjeh";
            echo "Upit ne smije biti tipa SELECT!<br>";
        }
        return $uspjeh;
    }

    function pogreskaDB() {
        if ($this->greska != '') {
            return true;
        } else {
            return false;
        }
    }
}
?>