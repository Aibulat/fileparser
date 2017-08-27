<?php

/**
 * HotelInfo - класс для работы с моделями HotelInfo
 *
 * @author Айбулат Галимянов <aibulatgalimianov@gmail.com>
 * @version 1.0
 */

namespace base\models;


use base\DataBase;

class HotelInfo
{
    // Атрибуты модели для сохранения
    private static $_attributes = [
        'service',
        'hotel_id',
        'country_code',
        'country_name',
        'city_id',
        'city_name',
        'longitude',
        'latitude',
        'name',
        'address',
        'description',
        'photo',
        'star_rating'
    ];

    /**
     * Метод сохраняет данные модели и возвращает результат сохранения
     *
     * @param array $arrHotelInfo
     * @return bool
     * @throws \Exception
     */
    public static function saveData(array $arrHotelInfo)
    {
        // Если переданы необходимые атрибуты модели
        if (count(array_diff(static::$_attributes, array_keys($arrHotelInfo))) === 0) {
            $dbh = (new DataBase())->dbh;

            $stmt = $dbh->prepare(
                '
                    INSERT INTO hotel_info (' . implode(', ', static::$_attributes) . ') 
                    VALUES (:' . implode(', :', static::$_attributes) . ')
                '
            );

            $savingResult = $stmt->execute(array_intersect_key($arrHotelInfo, array_flip(static::$_attributes)));
        }
        else {
            throw (new \Exception('Undefined hotel attributes'));
        }

        return $savingResult;
    }
}