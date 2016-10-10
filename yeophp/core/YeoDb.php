<?php
namespace Yeo\Core;

use PDO;
use PDOException;

/**
 * Todo database framework
 * Version 1.0.0
 * Copyright 2016.09.29, Yeoman-chen
 *
 */
class YeoDb
{
    protected static $instance;
    protected $pdo;
    protected $host;
    protected $port;
    protected $user;
    protected $pwd;
    protected $prefix;
    protected $dbName;
    protected $query;
    protected $sql;

    /**
     * 构造函数
     * @param array $option 连接参数数组
     */
    protected function __construct($option = [])
    {
        foreach ($option as $key => $value) {
            $this->$key = $value;
        }

        try {
            $this->pdo = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbName, $this->user, $this->pwd);
            //$this->pdo->setAttribute(PDO::ATTR_CASE,)
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
    /**
     * 实例化数据库连接
     * @param array $option 连接参数数据
     * @return object  实例对象
     */
    public static function getInstance($option)
    {

        if (empty(self::$instance)) {
            self::$instance = new self($option);
        }
        return self::$instance;
    }
    /**
     * 查询一条数据
     * @param string $sql 查询语句
     * @param array $params 查询的条件
     * @return mixed  返回结果
     */
    public function selectAll($sql, $params = [])
    {
    	$this->queryParamBinding($sql,$params);
        $info = $this->query->fetchAll(PDO::FETCH_ASSOC);print_r($info);
        return $info;
    }
    /**
     * 查询一条数据
     * @param string $sql 查询语句
     * @param array $params 查询的条件
     * @return mixed  返回结果
     */
    public function selectRow($sql, $params = [])
    {
        $this->queryParamBinding($sql,$params);
        $info = $this->query->fetch(PDO::FETCH_ASSOC);
        return $info;
    }
    /**
     * 拼接sql
     */
    public function selectContext($table, $join, $columns = null, $where = null)
    {
        $condition = $tablejoin = $group = $order = $limit = $having = '';
        if (is_array($where)) {
            foreach ($where as $key => $value) {
                if (strtolower($key) == 'and') {
                    foreach ($value as $key1 => $val1) {
                        $condition .= $condition ? " AND $key = $value" : " $key = $value";
                    }
                }
            }
        } else {
            $condition = $where;
        }
        $this->sql = 'select ' . $columns . ' from ' . $table . $tablejoin . ' where ' . $condition . $group . $order . $limit . $having;
    }
    /**
     * sql语句参数绑定
     * @param string $sql sql语句
     * @param array $params 参数绑定数组
     */
    public function queryParamBinding($sql, $params)
    {
        $this->query = $this->pdo->prepare($sql);
        if (is_array($params)) {

            foreach ($params as $key => $value) {
                $this->query->bindParam($key, $value, $this->getPdoType(gettype($value)));
            }
        }
        $this->query->execute();
    }

    /**
     * 插入数据
     * @param string $tableName 数据表
     * @param array $params 参数绑定数组
     * @return int 返回插入的id
     */
    public function insert($tableName, $data)
    {
    	$columns = '';
    	$values = ':';
    	$params = [];

    	if(is_array($data)){
    		$keys = array_keys($data);
    		$columns = implode(',', $keys);
    		$values .= implode(',:', $keys);
    		foreach ($data as $key => $value) {
    			$params[':'.$key] = $value;
    		}
    	}else{
    		return false;
    	}
    	$this->sql = "insert into $tableName ($columns) values ($values)";
    	
    	$query = $this->pdo->prepare($this->sql);
        $info = $query->execute($params);
        if($info){
        	return $this->pdo->lastInsertId();
        }else{
        	return 0;
        }
    }
    /**
     * 更新数据
     * @param string $tableName 数据表
     * @param array $params 参数绑定数组
     * @return boolean 返回操作结果
     */
    public function update($tableName, $udata,$where)
    {
    	$setColumns = $condition = ' ';

    	$columnsBind = $whereBind = [];

    	if(is_array($udata)){
    		$i = 0 ;
    		foreach ($udata as $key => $value) {
    			$columnsBind[':'.$key] = $value;
    			$setColumns .= $i == 0 ? " $key = :$key": " , $key = :$key";
    			$i++;
    		}
    	}else{
    		return false;
    	}
    	if(is_array($where)){
    		$i = 0 ;
    		foreach ($where as $key => $value) {
    			$whereBind[':'.$key] = $value;
    			$condition .= $i == 0 ? " $key = :$key": " AND $key = :$key";
    			$i++;
    		}
    	}else{
    		return false;
    	}
    	$params = array_merge($columnsBind,$whereBind);
    	$this->sql = "update $tableName set $setColumns where $condition";

    	$query = $this->pdo->prepare($this->sql);

        return $query->execute($params);
    }
    /**
     * 删除数据
     * @param string $tableName 数据表
     * @param array $params 参数绑定数组
     * @return boolean 返回操作结果
     */
    public function delete($tableName, $where)
    {
    	$condition = '';
    	$params = [];

    	if(is_array($where)){
    		$i = 0 ;
    		foreach ($where as $key => $value) {
    			$params[':'.$key] = $value;
    			$condition .= $i == 0 ? " $key = :$key": " AND $key = :$key";
    			$i++;
    		}
    	}else{
    		return false;
    	}
    	$this->sql = "delete from $tableName where $condition";
    	$query = $this->pdo->prepare($this->sql);
        return $query->execute($params);
    }
    /**
     * 获取参数绑定的类型
     */
    public function getPdoType($type)
    {
        $pdoType = ['boolean' => PDO::PARAM_BOOL,
            'integer'             => PDO::PARAM_INT,
            'string'              => PDO::PARAM_STR,
            'resource'            => PDO::PARAM_LOB,
            'NULL'                => PDO::PARAM_NULL];
        return isset($pdoType[$type]) ? $pdoType[$type] : PDO::PARAM_STR;
    }
    /**
     * 获取最后插入的id
     */
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
