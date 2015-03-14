<?php
    function connect(){
        $conn = oci_connect('C##', 'Diablokil9499');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        return $conn;
    }
?>
