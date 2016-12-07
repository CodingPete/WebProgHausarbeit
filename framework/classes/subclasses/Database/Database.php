<?php
defined('APP_ROOT') or exit("kthxbai");

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 10.11.2016
 * Time: 13:56
 */
class Database extends Redis
{

    public function __construct(&$config, &$framework) {
        parent::__construct();
        $this->connect(
            $config->redis_credentials["host"],
            $config->redis_credentials["port"]);

        $this->select($config->redis_db);
        //$this->flushDB();
    }

}