<?php
session_start();
$_SESSION = array();
if (session_destroy()) {
    $result = array(
        'status' => 'success' 
    );
} else {
    $result = array(
        'status' => 'failed' 
    );
}

echo json_encode($result);
?>