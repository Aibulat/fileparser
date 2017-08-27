<?php
/**
 * DataBase - класс для работы с БД
 *
 * @author Айбулат Галимянов <aibulatgalimianov@gmail.com>
 * @version 1.0
 */

namespace base;

use PDO, Exception;

class DataBase
{
    const HOST = 'localhost';
    const DB_NAME = 'fileparser';
    const USER = 'root';
    const PASSWORD = '';

    /** @var PDO */
    public $dbh;

    public function __construct()
    {
        $this->dbh = new PDO('mysql:host=' . static::HOST . ';dbname=' . static::DB_NAME, static::USER, static::PASSWORD);
        if (!$this->dbh) {
            throw(new Exception('Wrong database connecting data'));
        }
    }
}