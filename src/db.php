<?php
/**
 * Created by PhpStorm.
 * User: jues
 * Date: 2018/9/1
 * Time: 19:37
 */

require_once(dirname(__FILE__) . '/config.php');
require_once(dirname(__FILE__) . '/api/data/test_data.php');


/**
 * @return \jues\test_data
 */
function db_init()
{
    return new jues\test_data(DB_HOST, DB_PORT, DB_USER, DB_PASSWORD, DB_NAME, DB_TABLE_PRE);
}