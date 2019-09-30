<?php
require_once('class.php');

/*

Битрикс  - столбец K 
SIP  - столбец J 
Скрипт  берет номер тел в СЛЭЙВЕ (sip - J)  и проверяет его в Мастере (bitrix - K).
Если сопадения есть то вернем для этого номера - exist in master 


TODO
- Убрать дубли
- Выести доп поля
*/


try {

    $bitrix = new CSV("bitrix.csv"); 
    $arrMaster = $bitrix->getCSV();
 

    $sip = new CSV("sip.csv"); //Открываем наш csv
    $arrSip = $sip->getCSV();
    
    // Получаем телефоны SIP
    $arrSipTelephone = array();
    foreach ($arrSip as $value) { 
         $arrSipTelephone[] = $value[9] ;
        // echo "\r\n";
    }


    // function coincidenceByPhoneNumber($tel){
    //     //$resultArr = array();
    //     $result = false;
    //     $bitrix = new CSV("bitrix.csv"); 
    //     $arrMaster = $bitrix->getCSV();

    //     // В цикле прогоним текущий номер по всей выгрузке битрикса
    //     // Если сопадения есть то вернем для этого нромера - true
    //     foreach ($arrMaster as $value){
    //         if ($tel == $value[7])
    //             $result = true; 
    //     }

    //     if($result)
    //          $result =  array( $tel , "true" );    
    //     else
    //          $result =  array( $tel , "false" );    

    //     // Получили СТСТУС для текущего номера тел. из СЛЭЙВА     
    //     return $result;    
    // }

    // Берем все строку, 
        // смотрим телефон - если есть вхождение в битриске - ставим в конце масива - true
            // Если нет 
    function coincidenceByArray( $arr ){
       
        $result = false;
        $bitrix = new CSV("bitrix.csv"); 
        $arrMaster = $bitrix->getCSV();

        // В цикле прогоним текущий номер по всей выгрузке битрикса
        // Если сопадения есть то вернем для этого нромера - true
        foreach ($arrMaster as $value){
            if ($arr[9] == $value[10])
                $result = true; 
        }


        if($result)
            $arr[] = 'exist in master';
        else
            $arr[] = 'not found in master';


        // Получили СТСТУС для текущего номера тел. из СЛЭЙВА     
        return $arr;    
    }


    // function conv($n) {
    //     //var_dump($n);
    //     $array = array();
    //     foreach ($n as $value)
    //       $array[] = $value;
    //       //$array[] = iconv("utf8", "cp1251",$value);
          
    //       var_dump($array);
    //       exit;
    //       return $array;
    // }
    


     // Находим совпадения
     $resArr = array_map('coincidenceByArray',    $arrSip );
     // Переводим в ебучую виндовскую кодировку
     //$resArr = array_map("conv", $resArr);


    // array_walk_recursive($resArr, function(&$value,$key){
    //    $value=iconv("UTF-8","CP1251",$value);
    // });

       // var_dump($resArr);
       // exit; 

    if(!file_exists("result.csv")) 
        touch("result.csv");
    
     $res = new CSV("result.csv");
     $status = $res->setCSVFromArrAndRewrite($resArr);
    
    if($status)
        echo "Все ок, смотри файл result.csv  \r\n";
    else
        echo 'Обратитсь к тому кто это делал';




}
catch (Exception $e) { //Если csv файл не существует, выводим сообщение
    echo "Ошибка: " . $e->getMessage();
}