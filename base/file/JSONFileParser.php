<?php

/**
 * JSONFileParser - класс для работы с файлами JSON
 *
 * @author Айбулат Галимянов <aibulatgalimianov@gmail.com>
 * @version 1.0
 */

namespace base\file;

use SplFileObject, Exception;

class JSONFileParser extends FileParser
{
    public $structureName = 'JSON';

    // Массив с атрибутами для парсинга, формат: ['конечное имя атрибута' => 'исходное имя атрибута', ...]
    // Исходное имя атрибута может включать иерархический разделитель (".") для парсинга не только данных верхнего уровня
    protected $_arrParsAttr = [
        'hotel_id' => 'id',
        'name' => 'en.name',
        'address' => 'en.address',
        'country_name' => 'en.country',
        'city_name' => 'en.city',
        'city_id' => 'region_id',
        'country_code' => 'country_code',
        'longitude' => 'longitude',
        'latitude' => 'latitude',
        'star_rating' => 'star_rating',
        'photo' => 'images',
        'description' => 'en.description_short'
    ];

    public function __construct($filePath)
    {
        /** @var SplFileObject - класс для чтения файла */
        $this->_reader = new SplFileObject($filePath);;

        if ($this->_reader === false) {
            throw (new Exception('Failed to open file \'' . $filePath . '\''));
        }
    }

    public function getData()
    {
        try {
            while (!$this->_reader->eof()) {
                yield $this->_parse(static::toArray($this->_reader->fgets()));
            }
        } finally {
            $this->_reader = null;
        }
    }

    public static function toArray($JSONString)
    {
        $result = json_decode($JSONString, true);

        return json_last_error() === JSON_ERROR_NONE ? $result : [];
    }
}