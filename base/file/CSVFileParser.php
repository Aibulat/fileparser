<?php

/**
 * CSVFileParser - класс для работы с файлами CSV
 *
 * @author Айбулат Галимянов <aibulatgalimianov@gmail.com>
 * @version 1.0
 */

namespace base\file;

use SplFileObject, Exception;

class CSVFileParser extends FileParser
{
    public $structureName = 'CSV';

    // Массив с атрибутами для парсинга, формат: ['конечное имя атрибута' => 'исходное имя атрибута', ...]
    // Исходное имя атрибута может включать иерархический разделитель (".") для парсинга не только данных верхнего уровня
    protected $_arrParsAttr = [
        'hotel_id' => 'hotel_id',
        'name' => 'hotel_name',
        'address' => 'addressline1',
        'country_name' => 'country',
        'city_name' => 'city',
        'city_id' => 'city_id',
        'country_code' => 'countryisocode',
        'longitude' => 'longitude',
        'latitude' => 'latitude',
        'star_rating' => 'star_rating',
        'photo1' => 'photo1',
        'photo2' => 'photo2',
        'photo3' => 'photo3',
        'photo4' => 'photo4',
        'photo5' => 'photo5',
        'description' => 'overview'
    ];

    public function __construct($filePath)
    {
        /** @var SplFileObject - класс для чтения файла */
        $this->_reader = new SplFileObject($filePath);;

        if ($this->_reader === false) {
            throw (new Exception('Failed to open file \'' . $filePath . '\''));
        }

        $this->_reader->setFlags(SplFileObject::READ_CSV);
    }

    /**
     * Метод возвращает отпарсенное значение узла
     *
     * @return \Generator
     */
    public function getData()
    {
        try {
            $arrCSVHeader = $this->_reader->fgetcsv();
            $countHeaderColumns = count($arrCSVHeader);

            while (!$this->_reader->eof()) {
                $arrCurrentCSV = $this->_reader->fgetcsv();
                if (count($arrCurrentCSV) === $countHeaderColumns) {
                    $arrCombined = array_combine($arrCSVHeader, $arrCurrentCSV);

                    if (is_array($arrCombined)) {
                        yield $this->_parse($arrCombined);
                    }
                }
            }
        } finally {
            $this->_reader = null;
        }
    }

    /**
     * Метод конвертирует CSV в массив
     *
     * @param $CSVString
     * @return array
     */
    public static function toArray($CSVString)
    {
        return $CSVString;
    }
}