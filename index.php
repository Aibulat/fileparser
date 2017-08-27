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
    /** XML */

    $arrXMLResult = null;

    $objXMLFileParse = new \base\file\XMLFileParser(__DIR__ . '/test_files/gate3.xml');

    foreach ($objXMLFileParse->getData() as $arrHotel) {
        \base\models\HotelInfo::saveData($arrHotel, 'xml');

        $arrXMLResult[] = $arrHotel;
    }
    unset($arrHotel);

    /** JSON */

    $arrJSONResult = null;

    $objJSONFileParse = new \base\file\JSONFileParser(__DIR__ . '/test_files/gate1.json');

    foreach ($objJSONFileParse->getData() as $arrHotel) {
        \base\models\HotelInfo::saveData($arrHotel, 'json');

        $arrJSONResult[] = $arrHotel;
    }
    unset($arrHotel);

    /** CSV */

    $arrCSVResult = null;

    $objCSVFileParse = new \base\file\CSVFileParser(__DIR__ . '/test_files/gate2.csv');

    foreach ($objCSVFileParse->getData() as $arrHotel) {
        $arrHotel['photo'] = array_intersect_key($arrHotel, array_flip(['photo1', 'photo2', 'photo3', 'photo4', 'photo5']));

        \base\models\HotelInfo::saveData($arrHotel, 'csv');

        $arrCSVResult[] = $arrHotel;
    }
    unset($arrHotel);

    /** Result */

    echo '<pre>';
    var_dump($arrXMLResult);
    var_dump($arrJSONResult);
    var_dump($arrCSVResult);
    die;
}
catch (Exception $exception) {
    echo $exception->getMessage();
}