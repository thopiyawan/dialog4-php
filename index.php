<?php


function processMessage($update) {
    if($update["result"]["action"] == "sayHello"){
        sendMessage(array(
             "speech"=> "",
                      "messages"=> [array(
                                       "title"=> 'Oblivion',
                                       "subtitle"=> 'Oblivion is a SciFi film.',
                                       "buttons"=> [ array(
                                                      "text"=> "view film",
                                                      "postback"=>"https://www.moovrika.com/m/3520"
                                                  ),
                                           array(
                                                      "text"=> "Ask something else",
                                                      "postback"=>"I want to ask something else"
                                                  )
                                            ]
                                          ,
                                        "type"=> 1
                                   )]
        ));
    }
}

function sendMessage($parameters) {
	header('Content-Type: application/json');
    echo json_encode($parameters, JSON_UNESCAPED_SLASHES);
}

$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);
if (isset($update["result"]["action"])) {
    processMessage($update);
}