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

	/**
	 * 构造函数
	 */
	protected function __construct($option = [])
	{
		foreach ($option as $key => $value) {
			$this->$key = $value;
		}

		try{
			$this->pdo = new PDO('mysql:host='.$this->host.';dbname='.$this->dbName,$this->user,$this->pwd);

		} catch ( PDOException $e){
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
	 * @param array $option 连接参数数据
	 * @return object  实例对象
	 */
	public function selectRow($select = '*',$where = [])
	{

	}
	/**
	 * 查询sql
	 */
	public function query($sql)
	{
		$stmt = $this->pdo->prepare($sql);
		$res = $stmt->execute();
		$red = $stmt->fetchAll();
		return $red;
	}
	public function insert($sql)
	{
		$stmt = $this->pdo->prepare($sql);
		$res = $stmt->execute();
		//$res = $this->pdo->exec($sql);
		return $res;
	}
}

