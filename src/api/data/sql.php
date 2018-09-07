<?php
/**
 * Created by PhpStorm.
 * User: jues
 * Date: 2018/6/11
 * Time: 14:43
 * homd: http://blog.jues.org.cn
 */

namespace jues;


class sql
{
    protected $m_conn = null;
    protected $m_host, $m_port, $m_user, $m_password, $m_db, $m_tab_pre;


    /**
     * sql constructor.
     * @param $host
     * @param $port
     * @param $user
     * @param $password
     * @param $db
     * @param $tab_pre
     */
    function __construct($host, $port, $user, $password, $db, $tab_pre)
    {
        $this->m_host = $host;
        $this->m_port = $port;
        $this->m_user = $user;
        $this->m_password = $password;
        $this->m_db = $db;
        $this->m_tab_pre = $tab_pre;
    }


    /**
     * @return null|\PDO
     */
    function connect()
    {
        return sql::db_connect($this->m_host, $this->m_port, $this->m_user, $this->m_password, $this->m_db);
    }


    /**
     * @param $sql
     * @param $params
     * @return array|bool
     */
    function db_query_array($sql, $params)
    {
        $cont = $this->connect();
        $stmt = $this->db_execute($cont, $sql, $params);
        if (null == $stmt) {
            return FALSE;
        }
        //
        $res = array();
        /*
        foreach ($stmt as $row) {
            array_push($res, $row);
        }*/
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            array_push($res, $row);
        }
        //
        return $res;
    }


    /**
     * @param $sql
     * @param $params
     * @return null|object
     */
    function db_query_object($sql, $params)
    {
        $cont = $this->connect();
        $stmt = $this->db_execute($cont, $sql, $params);
        if (null == $stmt) {
            return null;
        }
        //
        $res = $stmt->fetchObject();
        //
        return $res;
    }


    /**
     * @param $sql
     * @param $params
     * @param $last
     * @return null|string
     */
    function db_insert($sql, $params, $last)
    {
        $cont = $this->connect();
        $stmt = $this->db_execute($cont, $sql, $params);
        if (null == $stmt) {
            return null;
        }
        //
        return $cont->lastInsertId($last);
    }


    /**
     * @param $sql
     * @param $params
     * @return null
     */
    function db_insert_row($sql, $params)
    {
        $cont = $this->connect();
        $stmt = $this->db_execute($cont, $sql, $params);
        if (null == $stmt) {
            return null;
        }
        //
        return $stmt->rowCount();
    }


    /**
     * @param $sql
     * @param $params
     * @return null
     */
    function db_update($sql, $params)
    {
        $cont = $this->connect();
        $stmt = $this->db_execute($cont, $sql, $params);
        if (null == $stmt) {
            return null;
        }
        //
        return $stmt->rowCount();
    }


    /**
     * @param $cont
     * @param $sql
     * @param $params
     * @return null
     */
    function db_execute($cont, $sql, $params)
    {
        if (null == $cont) {
            return null;
        }
        //
        $stmt = $cont->prepare($sql);
        if (FALSE == $stmt->execute($params)) {

            $error = $stmt->errorInfo();
            return null;
        }
        //
        return $stmt;
    }


    /**
     * @param $host
     * @param $port
     * @param $user
     * @param $password
     * @param $db
     * @return null|\PDO
     */
    static function db_connect($host, $port, $user, $password, $db)
    {
        try {
            $dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $db . ';charset=utf8;';
            $cont = new \PDO($dsn, $user, $password);
            //
            if (0 != $cont->errorCode()) {
                return null;
            }
        } catch (\PDOException $e) {
            die ("Error!: " . $e->getMessage() . "<br/>");
        }

        //
        //$cont->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $cont->query('SET NAMES utf8');
        //
        return $cont;
    }
}