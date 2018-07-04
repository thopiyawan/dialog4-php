<?php
/*
 * PROCESSANDO A MENSAGEM 
 * QUE CHEGA DO BOT
 */
function processMessage($text) {
    if($text == "แพ้อาหาร"){
   
        sendMessage(array(
            "source" => $update["result"]["source"],
            "speech" => "..........TEXT HERE...........",
            "displayText" => ".........TEXT HERE...........",
            "contextOut" => array()
        ));
    }
}


/*
 * FUNÇÃO PARA ENVIAR A MENSAGEM
 */
function sendMessage($parameters) {
    echo json_encode($parameters);
}

/*
 * PEGANDO A REQUISIÇÃO
 */
$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);

 if(!is_null($update)){
            // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
            $replyToken  = $events['events'][0]['replyToken'];
            $user = $events['events'][0]['source']['userId'];
            $text = $events['events'][0]['message']['text'];
            $type_message = $events['events'][0]['message']['type'];
            processMessage($text);

 }
             

// if (isset($update["result"]["action"])) {

//       processMessage($update);

// }

?>