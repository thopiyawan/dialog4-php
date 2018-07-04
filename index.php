<?php
/*
 * PROCESSANDO A MENSAGEM 
 * QUE CHEGA DO BOT
 */
function processMessage($text) {
    if($text == "แพ้อาหาร"){
   
        sendMessage(array(
            "source" => "22222",
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
            // $replyToken  = $update ['events'][0]['replyToken'];
            // $user = $update['events'][0]['source']['userId'];
            $text = $update['events'][0]['message']['text'];
            // $type_message = $update['events'][0]['message']['type'];
            processMessage($text);

 }
             

// if (isset($update["result"]["action"])) {

//       processMessage($update);

// }

?>