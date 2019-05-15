<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dario
 * Date: 11/05/2019
 * Time: 21:24
 *
 *
 *
 * 3b
select id_ut, nome_ut, cogn_ut
from utenti
where id_ut not in (select distinct id_ut
from utenti inner join visual on id_ut = id_ut_fk);
 *
 */

$rifconn = include_once('connection.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD']=="GET")
{
    getUtenti($rifconn);

}


function getUtenti($conn)
{
        $stmt = $conn->prepare("select id_ut, nome_ut, cogn_ut from utenti where id_ut not in (select distinct id_ut from utenti inner join visual on id_ut = id_ut_fk);");

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