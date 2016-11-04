<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.

Получаем список банков и БИК с сайта cbr.ru

http://www.cbr.ru/scripts/XML_bic2.asp
http://www.cbr.ru/CreditInfoWebServ/CreditOrgInfo.asmx
http://www.cbr.ru/scripts/Root.asp?PrtId=WSCO
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $client = new SoapClient("http://www.cbr.ru/CreditInfoWebServ/CreditOrgInfo.asmx?wsdl", array('soap_version' => SOAP_1_2, 'trace' => true));
        $result = $client->EnumBIC_XML();
        if ($result->EnumBIC_XMLResult->any) {
            $xml = new SimpleXMLElement($result->EnumBIC_XMLResult->any);
            foreach ($xml as $bank_item) {
                $b = json_decode(json_encode($bank_item));
                echo " Бик банка " . $b->BIC . " Название " . $b->NM . " Дата регистрации " . $b->RC . " Внутр. код " . $b->intCode . "<br>";
                $r = $client->GetOffices(array('IntCode' => $b->intCode));
                if ($r->GetOfficesResult->any) {
                    $x = new SimpleXMLElement($r->GetOfficesResult->any);
                    foreach ($x as $bank_item_f) {
                        echo " Филиалы " . "<br>";
                        foreach ($bank_item_f as $bf) {
                            echo " рег. номер филиала " . $bf->cregnum . " название филиала " . $bf->cname . " Место нахождения (фактический адрес) " . $bf->straddrmn . "<br>";
                        }
                        //var_dump($bank_item);
                    }
                }
            }
        }
        ?>
    </body>
</html>
