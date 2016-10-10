<?php

/**
 * 首页控制器
 * @copyright  yeoman-chen
 * @author yeoman
 * @since 2016.09.30
 */

namespace Yeo\Controller;

use Yeo\Core\YeoController;
use Yeo\Core\YeoLog;
use Yeo\Core\YeoDb;

class IndexController extends YeoController
{
	/**
	 * 默认控制器
	 */
	public function indexAction()
	{
		/*$userList = [1 => 'test1',2 => 'test2'];
		$this->display('index',['dada' => 'dadad123','userList' => $userList]);die;*/
		
		$dbConfig = ['host' => '127.0.0.1','dbName' => 'yeoman','user' => 'root','pwd' => ''];
		$db = YeoDb::getInstance($dbConfig);
		$where['and'] = ['deptno' => ':deptno'];
		$where['order'] = ['deptno' => 'ASC'];
		$where['limit'] = [0,10];

		$db->selectRow('dept','*','deptno = :deptno',[":deptno" => 16777215]);die;
		//YeoLog::log('test','info','application',true);
		$yeolog = new YeoLog();
		for ($i = 0;$i< 10;$i++) {
			$yeolog->log('test','info','application',false);
		}
		$yeolog->log('test','info','application',false);
		//$this->yeoDb;
		$data = ['code' => 1000,'message' => 'welcome to yeophp!' ,'content' => '谢谢！'];
		$this->apiResultStandard($data['code'],$data['message'],$data['content']);
	}
	public function logAction()
	{
		YeoLog::log('test','info','application',true);
		echo 'finall';
	}
	public function dbtestAction()
	{
		$dbConfig = ['host' => '127.0.0.1','dbName' => 'demopy','user' => 'root','pwd' => ''];
		$db = YeoDb::getInstance($dbConfig);
		//select row
		/*$sql = "select * from pyuser where id = :id ";
		$db->selectRow($sql,[':id' => 1]);*/
		//select all
		/*$sql = "select * from pyuser where groupId = :id limit 10";
		$db->selectAll($sql,[':id' => 1]);*/
		//insert
		/*$data = ['name' => 'test2','telphone' => '13612345678'];
		$res = $db->insert('pyuser',$data);
		var_dump($res);*/
		//update
		/*$data = ['name' => 'test3','telphone' => '13688888'];
		$where = ['id' => 2];
		$res = $db->update('pyuser',$data,$where);
		var_dump($res);*/
		//delete
		$where = ['id' => 4];
		$res = $db->delete('pyuser',$where);
		var_dump($res);
	}
}