<?php
/**
 * Created by PhpStorm.
 * User: Boyd
 * Date: 6/30/2018
 * Time: 10:47 PM
 */

//define('__ROOT__', $_SERVER['DOCUMENT_ROOT']);
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
require_once(__ROOT__ . '/app/config/config.php');

class Database extends mysqli
{
    private static $instance = null;

    private $host = DB_HOST;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $name = DB_NAME;

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct()
    {
        parent::__construct($this->host, $this->username, $this->password, $this->name);
        if (mysqli_connect_error()) {
            exit('Connect Error (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
        }
        parent::set_charset('utf-8');
    }

    public function runQuery($query)
    {
        if ($query == "" || !$this->query($query)) {
            return false;
        }

        return true;
    }

    public function getResults($query)
    {
        if (!$this->runQuery($query)) {
            return null;
        }

        $results = array();
        $temp = $this->query($query);
        if ($temp->num_rows > 0) {
            while ($row = $temp->fetch_assoc()) {
                array_push($results, $row);
            }
            return $results;
        } else {
            return null;
        }
    }
}