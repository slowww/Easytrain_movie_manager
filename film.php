<?php

$rifconn = include_once('connection.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD']=="GET")
{
        getFilm($_GET,$rifconn);

}


function getFilm($g, $conn)
{
    /*2
         * Ricerca TUTTI oppure in base a: TITOLO, ANNO, DURATA(lunghi >60, corti<60), GENERE, ATTORI/REGISTI COINVOLTI */

    if (isset($g['titolo'])) {
        $titolo = $g['titolo'];
        $stmt = $conn->prepare("select titolo,anno_film,durata from film where titolo like ?;");
        $stmt->bind_param("s", $titolo);
        $stmt->execute();
        $result = $stmt->get_result();

        $msmError = (object)array('Error');
        $msmAlert = (object)array('No results');
        if (!$result) echo json_encode($msmError);
        $return = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $return[] = $row;
        }
        if ($return == null) {
            echo json_encode($msmAlert);
        } else {
            $object = (object)($return);
            echo json_encode($object);

        }
    } else if (isset($g['anno'])) {
        $anno = (int)$g['anno'];
        $stmt = $conn->prepare("select titolo,anno_film,durata  from film where anno_film=?;");
        $stmt->bind_param("i", $anno);
        $stmt->execute();
        $result = $stmt->get_result();

        $msmError = (object)array('Error');
        $msmAlert = (object)array('No results');
        if (!$result) echo json_encode($msmError);
        $return = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $return[] = $row;
        }
        if ($return == null) {
            echo json_encode($msmAlert);
        } else {
            $object = (object)($return);
            echo json_encode($object);
        }
    } elseif (isset($g['durata'])) {
        switch ($g['durata']) {
            case "lungo":
                $stmt = $conn->prepare("select titolo,anno_film,durata  from film where durata>=60;");
                $stmt->execute();
                $result = $stmt->get_result();

                $msmError = (object)array('Error');
                $msmAlert = (object)array('No results');
                if (!$result) echo json_encode($msmError);
                $return = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    $return[] = $row;
                }
                if ($return == null) {
                    echo json_encode($msmAlert);
                } else {
                    $object = (object)($return);
                    echo json_encode($object);
                }
                break;
            case "corto":
                $stmt = $conn->prepare("select titolo,anno_film,durata  from film where durata<60;");
                $stmt->execute();
                $result = $stmt->get_result();

                $msmError = (object)array('Error');
                $msmAlert = (object)array('No results');
                if (!$result) echo json_encode($msmError);
                $return = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    $return[] = $row;
                }
                if ($return == null) {
                    echo json_encode($msmAlert);
                } else {
                    $object = (object)($return);
                    echo json_encode($object);
                }
                break;
        }

    } elseif (isset($g['genere'])) {
        $genere = $g['genere'];
        $stmt = $conn->prepare("select titolo,anno_film,durata  from film inner join appartenenze on id_film = id_film_fk inner join generi on id_gen_fk = id_gen where nome_gen like ?");
        $stmt->bind_param("s", $genere);
        $stmt->execute();
        $result = $stmt->get_result();

        $msmError = (object)array('Error');
        $msmAlert = (object)array('msg'=>'No results');
        if (!$result) echo json_encode($msmError);
        $return = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $return[] = $row;
        }
        if ($return == null) {
            echo json_encode($msmAlert);
        } else {
            $object = (object)($return);
            echo json_encode($object);
        }
    } elseif (isset($g['sogg'])) {
        $sogg = $g['sogg'];
        $stmt = $conn->prepare("select titolo,anno_film,durata  from film inner join coinv on id_film = id_film_fk inner join soggetti on id_sogg_fk = id_sogg where nome_sogg like ?;");
        $stmt->bind_param("ss", "%".$sogg."%");
        $stmt->execute();
        $result = $stmt->get_result();

        $msmError = (object)array('Error');
        $msmAlert = (object)array('No results');
        if (!$result) echo json_encode($msmError);
        $return = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $return[] = $row;
        }
        if ($return == null) {
            echo json_encode($msmAlert);
        } else {
            $object = (object)($return);
            echo json_encode($object);
        }
    } else {
        /*3a (GENERAL GET)
     * select * from film innerjoin appartenenze on id_film = id_film_fk inner join generi on id_gen_fk = id_gen
    order by nome_gen, anno_film;*/

        $stmt = $conn->prepare("select id_film, titolo, anno_film, durata, nome_gen from film inner join appartenenze on id_film = id_film_fk inner join generi on id_gen_fk = id_gen order by nome_gen, anno_film;");
        $stmt->execute();
        $result = $stmt->get_result();

        $msmError = (object)array('Error');
        $msmAlert = (object)array('No results');
        if (!$result) echo json_encode($msmError);
        $return = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $return[] = $row;
        }
        if ($return == null) {
            echo json_encode($msmAlert);
        } else {
            $object = (object)($return);
            echo json_encode($object);
        }
    }
}//end getFilm















