<?php

/**
 * FileParser - класс для работы с БД
 *
 * @author Айбулат Галимянов <aibulatgalimianov@gmail.com>
 * @version 1.0
 */

namespace base\file;

use XMLReader, Exception;

class XMLFileParser extends FileParser
{
    // Массив с атрибутами для парсинга, формат: ['конечное имя атрибута' => 'исходное имя атрибута', ...]
    // Исходное имя атрибута может включать иерархический разделитель (".") для парсинга не только данных верхнего уровня
    protected $_arrParsAttr = [
        'hotel_id' => 'id',
        'name' => 'name',
        'address' => 'address',
        'country_name' => 'country.en',
        'city_name' => 'city.en',
        'city_id' => 'cityid',
        'country_code' => 'countrytwocharcode',
        'longitude' => 'longitude',
        'latitude' => 'latitude',
        'star_rating' => 'stars',
        'photo' => 'photos.photo',
        'description' => 'descriptions.en'
    ];

    public $childName = 'hotel'; // Узел, сущность с необходимыми атрибутами

    public function __construct($filePath)
    {
        /** @var XMLReader - класс для чтения XML файла */
        $this->_reader = new XMLReader();

        if ($this->_reader->open($filePath) === false) {
            throw (new Exception('Failed to open file \'' . $filePath . '\''));
        }
    }

    /**
     * Метод возвращает отпарсенное значение узла
     *
     * @return \Generator
     */
    public function getData()
    {
        try {
            while ($this->_reader->read()) {
                if ($this->_reader->nodeType == XMLReader::ELEMENT && $this->_reader->name === $this->childName) {
                    yield $this->_parse(static::XMLToArray($this->_reader->readOuterXml()));
                }
            }
        } finally {
            $this->_reader->close();
        }
    }

    /**
     * Метод конвертирует XML в массив
     *
     * @param $XMLString
     * @return array
     */
    public static function XMLToArray($XMLString)
    {
        $objXML = simplexml_load_string($XMLString, null, LIBXML_NOCDATA);

        if ($objXML !== false) {
            $result = json_decode(json_encode($objXML), true);
        }

        return isset($result) && is_array($result) ? $result : [];
    }

    /**
     * Метод парсит требуемые атрибуты
     *
     * @param array $arrAttr - массив с данными узла - атрибутами
     * @return array
     * @throws Exception
     */
    protected function _parse(array $arrAttr = array()): array
    {
        if (empty($this->_arrParsAttr)) {
            return $arrAttr;
        }

        $arrResult = [];

        if ($this->_isStrictAttr) {
            $arrNotExistColumns = [];
        }

        if (!empty($arrAttr)) {
            foreach ($this->_arrParsAttr as $correctAttr => $currentAttr) {
                $arrPartAttr = explode('.', $currentAttr);
                $parentAttr = array_shift($arrPartAttr);

                if (isset($arrAttr[$parentAttr]) || array_key_exists($parentAttr, $arrAttr)) {
                    $hasValue = true;
                    $temp = $arrAttr[$parentAttr];

                    if (!empty($arrPartAttr)) {
                        foreach ($arrPartAttr as $currentPartAttr) {
                            if (isset($temp[$currentPartAttr]) || (is_array($temp) && array_key_exists($currentPartAttr, $temp))) {
                                $temp = $temp[$currentPartAttr];
                            } else {
                                $hasValue = false;
                                break;
                            }
                        }
                        unset($currentPartAttr);
                    }
                }

                if (!empty($hasValue)) {
                    $arrResult[$correctAttr] = $temp;
                } else if ($this->_isStrictAttr) {
                    $arrNotExistColumns[] = $currentAttr;
                }
                unset($hasValue, $temp);
            }
            unset($correctAttr, $currentAttr);

            if (!empty($arrNotExistColumns)) {
                throw (new Exception('XML structure has broken, check columns: ' . implode(', ', $arrNotExistColumns) . '.'));
            }
        }

        return $arrResult;
    }
}