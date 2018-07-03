<?php
require 'get_enews.php';

function processMessage($input) {
    $action = $input["result"]["action"];
    switch($action){

        case 'getNews':
            $param = $input["result"]["parameters"]["number"];
            getNews($param);
            break;

        default :
            sendMessage(array(
                "source" => "RMC",
                "speech" => "I am not able to understand. what do you want ?",
                "displayText" => "I am not able to understand. what do you want ?",
                "contextOut" => array()
            ));
    }
}
function sendMessage($parameters) {
    header('Content-Type: application/json');
    $data = str_replace('\/','/',json_encode($parameters));
    echo $data;
}
$input = json_decode(file_get_contents('php://input'), true);
if (isset($input["result"]["action"])) {
    processMessage($input);
}
?>