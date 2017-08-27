# fileparser
---
## Общие признаки
|    service   	| xml                	| json              	| csv            	|                                                                                       	|
|:------------:	|--------------------	|-------------------	|----------------	|---------------------------------------------------------------------------------------	|
| hotel_id     	| id                 	| id                	| hotel_id       	|                                                                                       	|
| name         	| name               	| name              	| name           	|                                                                                       	|
| address      	| address            	| address           	| addressline1   	|                                                                                       	|
| country_name 	| country            	| country           	| country        	|                                                                                       	|
| city_name    	| city               	| city              	| city           	|                                                                                       	|
| city_id      	| cityid             	| region_id         	| city_id        	| Уточнение: здесь имеется в виду идентификатор места нахождения отеля, не только город 	|
| country_code 	| countrytwocharcode 	| country_code      	| countryisocode 	|                                                                                       	|
| longitude    	| longitude          	| longitude         	| longitude      	|                                                                                       	|
| latitude     	| latitude           	| latitude          	| latitude       	|                                                                                       	|
| star_rating  	| stars              	| star_rating       	| star_rating    	|                                                                                       	|
| photo        	| photos             	| images            	| photos[1-5]    	|                                                                                       	|
| description  	| descriptions       	| description_short 	| overview       	|                                                                                       	|

---
### Таблица для хранения общих признаков

Распределение данных (общих): каждый сервис имеет свой дамп с таблицами городов, стран и т.п., поэтому `id`
связываем с сервисом. Связь города и страны делаем по `city_id`, `country_code`, соответственно.
Остальные поля являются отельными. В принципе, можно вынести в отдельную таблицу такие данные как координаты, рейтинг,
но, как правило, такие связи только увеличивают затраты. Все эти связи можно проигнорировать, так как, зачастую,
они нужны в таком формате (который мы спарсили), и связи таблиц в каждом запросе будут увеличивать время его выполнения,
поэтому можно пренебречь нормализацией. В итоге будем использовать одну таблицу: [hotel_info.sql](https://github.com/Aibulat/fileparser/blob/master/migration/database/hotel_info.sql)