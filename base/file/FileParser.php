<?php

/**
 * FileParser - базовый класс парсера файлов
 *
 * @author Айбулат Галимянов <aibulatgalimianov@gmail.com>
 * @version 1.0
 */

namespace base\file;

use Exception;

abstract class FileParser
{
    public $structureName; // Название структуры файла

    protected $_reader; // Обработчик файла

    // Массив с атрибутами для парсинга, формат: ['конечное имя атрибута' => 'исходное имя атрибута', ...]
    // Исходное имя атрибута может включать иерархический разделитель (".") для парсинга не только данных верхнего уровня
    protected $_arrParsAttr = [];

    protected $_isStrictAttr = true; // Флаг, обозначающий обязательное присутствие атрибутов

    /**
     * Метод возвращает данные файла
     *
     * @return mixed
     */
    abstract public function getData();

    /**
     * Метод конвертирует данные в массив
     *
     * @param $element
     * @return mixed
     */
    abstract public static function toArray($element);

    /**
     * Метод парсит необходимые атрибуты и возвращает их
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
                throw (new Exception($this->structureName . ' structure has broken, check columns: ' . implode(', ', $arrNotExistColumns) . '.'));
            }
        }

        return $arrResult;
    }
}