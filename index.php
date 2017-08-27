<?php

$isTest = false;

if ($isTest) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});


try {
    $arrResult = null;

    $objXMLFileParse = new \base\file\XMLFileParser(__DIR__ . '/test_files/gate3.xml');

    foreach ($objXMLFileParse->getData() as $arrHotel) {
        $arrHotel['service'] = 'xml';
        $arrHotel['photo'] = json_encode($arrHotel['photo']);

        \base\models\HotelInfo::saveData(['service' => 'xml'] + $arrHotel);

        $arrResult[] = $arrHotel;
    }
    unset($arrHotel);

    echo '<pre>';
    var_dump($arrResult);
    die;
}
catch (Exception $exception) {
    echo $exception->getMessage();
}