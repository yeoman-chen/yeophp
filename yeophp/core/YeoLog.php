<?php

/**
 * 日志操作类
 * @author Yeoman
 * @since 2016.10.06
 */

namespace Yeo\Core;

class YeoLog 
{
	const LEVEL_TRACE = 'trace';
	const LEVEL_WARNING = 'warning';
	const LEVEL_ERROR = 'error';
	const LEVEL_INFO = 'info';
	const LEVEL_PROFILE = 'profile';
	const MAX_TOTAL = 2; //内存中最大日志数

	private $logCount = 0; //记录日志数
	private $logArr = []; //日志数组
	private $logPath;  //日志路径
	private $logFile;  //日志文件

	/**
	 * 打印日志
	 * @param string $message 日志消息
	 * @param string $level 日志等级
	 * @param string $category 日志类型
	 * @param string $autoFlush 是否刷新日志（写入文件）
	 */
	public function log($message,$level = 'info',$category = 'application',$autoFlush = false)
	{
		$this->logArr[] = array($message,$level,$category,microtime(true));
		$this->logCount++;
		if($this->logCount >= YeoLog::MAX_TOTAL || $autoFlush == true){
			$this->flushLog();
		}
	}
	/**
	 * 刷新日志
	 */
	public function flushLog()
	{
		if($this->logCount <= 0){
			return false;
		}
		foreach ($this->logArr as $value) {
			$msg = $this->messageFormat($value[0],$value[1],$value[2],$value[3]);
			$this->writeFile($msg,$value[2]);
		}
		$this->logCount = 0;
		$this->logArr = [];

	}
	/**
	 * 写入文件
	 * @param string $msg 消息
	 * @param string $category 类型
	 */
	public function writeFile($msg,$category)
	{
		$this->logPath = APP_PATH.'/yeophp/runtime';
		$this->logFile = $this->logPath.'/'.$category.'.log';
		//文件目录不存在则创建
		if(!file_exists($this->logPath)){
			mkdir($this->logPath,0755);
		}
		$fp = fopen($this->logFile, 'a');
		if(flock($fp, LOCK_EX)){
			fwrite($fp, $msg);
			flock($fp, LOCK_UN);
		}else{
			echo 'cannot lock the file';
		}
		fclose($fp);
	}
	/**
	 * 日志格式化
	 * @param string $message 日志消息
	 * @param string $level 日志等级
	 * @param string $category 日志类型
	 * @param string $time 时间
	 * @return string 格式化后的日志
	 */
	public function messageFormat($message,$level,$category,$time)
	{
		return date('Y-m-d H:i:s',$time)." [$level] [$category] $message \n";
	}
}