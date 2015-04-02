<?php
    // Convert a formatted string to date object
    function stringToDate($date) {
    	return DateTime::createFromFormat('Y-m-j', $date);
    }

    // Convert date object to formatted string
    function dateToString($date) {
    	return date_format($date,"j-M-Y");
    }
?>