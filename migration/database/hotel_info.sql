/**

Распределение данных (общих): каждый сервис имеет свой дамп с таблицами городов, стран и т.п., поэтому `id`
связываем с сервисом. Связь города и страны делаем по `city_id`, `country_code`, соответственно.
Остальные поля являются отельными. В принципе, можно вынести в отдельную таблицу такие данные как координаты, рейтинг,
но, как правило, такие связи только увеличивают затраты. Все эти связи можно проигнорировать, так как, зачастую,
они нужны в таком формате (который мы спарсили), и связи таблиц в каждом запросе будут увеличивать время его выполнения,
поэтому можно пренебречь нормализацией. В итоге будем использовать одну таблицу:

 */

CREATE TABLE `hotel_info` (
  `hotel_info_id` INT                         NOT NULL AUTO_INCREMENT
  COMMENT 'ID записи',
  `service`       ENUM ('xml', 'json', 'csv') NOT NULL
  COMMENT 'ID сервиса данных',
  `hotel_id`      INT                         NOT NULL
  COMMENT 'ID отеля',
  `country_code`  VARCHAR(2)                  NOT NULL
  COMMENT 'Код страны',
  `country_name`  VARCHAR(255)                NOT NULL
  COMMENT 'Название страны',
  `city_id`       INT                         NOT NULL
  COMMENT 'ID города',
  `city_name`     VARCHAR(255)                NOT NULL
  COMMENT 'Название города',
  `longitude`     FLOAT                       NOT NULL
  COMMENT 'Долгота отеля',
  `latitude`      FLOAT                       NOT NULL
  COMMENT 'Широта отеля',
  `name`          VARCHAR(255)                NOT NULL
  COMMENT 'Название отеля',
  `address`       VARCHAR(255)                NOT NULL
  COMMENT 'Адрес отеля',
  `description`   TEXT                        NOT NULL
  COMMENT 'Описание отеля',
  `photo`         JSON                        NULL     DEFAULT NULL
  COMMENT 'Набор фото',
  `star_rating`   TINYINT                     NULL     DEFAULT NULL
  COMMENT 'Рейтинг в звездах',
  PRIMARY KEY (`hotel_info_id`), # порядковый индекс
  /*
    idx_hotel_service - индекс, связывающий отель с сервисом, откуда были получены его данные.
    Порядок полей составного индекса выбран, исходя из селективности
   */
  UNIQUE KEY `idx_hotel_service` (`hotel_id`, `service`),
  KEY `idx_city_id` (`city_id`), # создан, исходя из частой выборке отелей по городам
  KEY `idx_rating` (`star_rating`) # создан, исходя из частой сортировки отелей по рейтингу
)
  ENGINE = InnoDB
  COMMENT = 'таблица с данными об отелях';