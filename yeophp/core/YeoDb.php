<?php
namespace Yeo\Core;

use Exception;
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

		try{
			$this->pdo = new PDO('mysql:host='.$this->host.';dbname='.$this->dbName,$this->user,$this->pwd);
			//$this->pdo->setAttribute(PDO::ATTR_CASE,)
		} catch ( \PDOException $e){
			print "Error!: ". $e->getMessage() . "<br/>";
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
		
		if(empty(self::$instance)){
			self::$instance = new self($option);
		}
		return self::$instance;
	}
	public function selectAll($select = '*',$where = [])
	{
		
	}
	/**
	 * 查询一条数据
	 * @param string $select 查询的字段
	 * @param array $where 查询的条件
	 * @return object  实例对象
	 */
	public function selectRow($table,$columns = '*',$where = [],$params = [])
	{
		$this->selectContext($table,'',$columns,$where);
		
		$query = $this->pdo->prepare($this->sql);
		
		foreach ($params as $key => $value) {
			//echo $key.'_'.$value.'_'.$this->getPdoType(gettype($value));die;
			$query->bindParam($key,$value,$this->getPdoType(gettype($value)));
			//$query->bindParam($key,$value);
		}
		$query->execute();
		$res = $query->fetch();
		var_dump($res);
	}
	/**
	 * 拼接sql
	 */
	public function selectContext($table, $join, $columns = null, $where = null)
	{
		$condition = $tablejoin = $group = $order = $limit = $having = '';
		if(is_array($where)){
			foreach ($where as $key => $value) {
				if(strtolower($key) == 'and'){
					foreach ($value as $key1 => $val1) {
						$condition .= $condition ? " AND $key = $value" : " $key = $value" ;
					}
				}
			}
		} else {
			$condition = $where;
		}
		$this->sql = 'select ' . $columns . ' from ' . $table . $tablejoin .' where ' .$condition.$group.$order.$limit.$having;
	}
	
	/**
	 * where 解析
	 */
	public function parseWhere($where = [])
	{
		if(empty($where)){
			return false;
		}
		$conWhere = $order = $group = '';
		foreach ($where as $key => $value) {
			switch ($key) {
				case 'AND':
					foreach ($value as $key => $value) {
						$conWhere = $conWhere ? " AND $key = $value " : " $key = $value ";
					}
					break;
				case 'order':
					foreach ($value as $key => $value) {
						$conWhere = $conWhere ? " AND $key = $value " : " $key = $value ";
					}
					break;
				default:
					$conWhere = " $key = $value ";
					break;
			}
		}
	}
	/**
	 * 查询sql
	 */
	public function query($sql)
	{
		$query = $this->pdo->prepare($sql);
		$res = $query->execute();
		$red = $query->fetchAll();
		return $red;
	}
	public function insert($sql)
	{
		$query = $this->pdo->prepare($sql);
		$res = $query->execute();
		//$res = $this->pdo->exec($sql);
		return $res;
	}
	/**
	 * 获取参数绑定的类型
	 */
	public function getPdoType($type)
	{
		$pdoType = ['boolean' => PDO::PARAM_BOOL,
					'integer' => PDO::PARAM_INT,
					'string' => PDO::PARAM_STR,
					'resource' => PDO::PARAM_LOB,
					'NULL' => PDO::PARAM_NULL];
		return isset($pdoType[$type]) ? $pdoType[$type] : PDO::PARAM_STR;
	}
	/**
	 * 获取最后插入的id
	 */
	public function getLastInsertId(){
		return $this->pdo->lastInsertId();
	}
}

