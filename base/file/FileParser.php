<?php

/**
 * FileParser - базовый класс парсера файлов
 *
 * @author Айбулат Галимянов <aibulatgalimianov@gmail.com>
 * @version 1.0
 */

namespace base\file;

abstract class FileParser
{
    protected $_reader; // Обработчик файла

    protected $_arrParsAttr = []; // Массив с атрибутами для парсинга
    protected $_isStrictAttr = true; // Флаг, обозначающий обязательное присутствие атрибутов

    /**
     * Метод возвращает данные файла
     *
     * @return mixed
     */
    abstract public function getData();

    /**
     * Метод парсит необходимые атрибуты и возвращает их
     *
     * @param array $arrAttr
     * @return array
     */
    abstract protected function _parse(array $arrAttr = array()) : array;
}