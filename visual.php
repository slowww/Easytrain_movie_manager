<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dario
 * Date: 11/05/2019
 * Time: 21:24
 *
 *
 *3c

 */

$rifconn = include_once('connection.php');

header('Content-Type: application/json');

$data1 = $_GET['data1'];
$data2 = $_GET['data2'];

if($_SERVER['REQUEST_METHOD']=="GET")
{
    getVisual($data1,$data2,$rifconn);

}


function getVisual($d1,$d2,$conn)
{

    $stmt = $conn->prepare("select titolo, count(id_film_fk) as freq from film inner join visual on id_film = id_film_fk where data_visual between ? and ? group by id_film_fk order by freq DESC limit 1;");
    $stmt->bind_param("ss",$d1,$d2);
    $stmt->execute();
    $result = $stmt->get_result();

    $msmError = (object)array('Errore nei dati inseriti.');
    $msmAlert = (object)array('No results.');
    if ((!$result) || (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$d1) ||  (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$d2))))
    {
        echo json_encode($msmError);
    }
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