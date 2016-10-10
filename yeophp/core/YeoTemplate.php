<?php

/**
 * 模板类
 * @author Yeoman
 * @since 2016.10.07
 */
namespace Yeo\Core;

class YeoTemplate
{
	private $value = [];
	public function __construct()
	{

	}
	/**
	 * 注入字符变量
	 * @param string $key   变量key
	 * @param string $value 变量value
	 */
	public function assign($key,$value)
	{
		$this->value[$key] = $value;
	}
	/**
	 * 注入数组变量
	 * @param array $array 数组变量
	 */
	public function assignArray($array)
	{
		if(is_array($array)){
			foreach ($array as $key => $value) {
				$this->value[$key] = $value;
			}
		}
	}
	/**
	 * 显示模板文件
	 * @param string $fileName   变量key
	 * @param string $data   变量value
	 */
	public function display($tpl,$data = [])
	{
		$this->assignArray($data);
		$fileName = APP_PATH.'/yeophp/view/'.$tpl.'.phtml';
		$cacheTpl = APP_PATH.'/yeophp/runtime/cache/'.md5($tpl).'.php';
		if(!file_exists($fileName)){
			exit('View file '. $fileName .' is not found');
		}
		//编译模板文件
		$content = file_get_contents($fileName);
		extract($this->value,EXTR_OVERWRITE);
		file_put_contents($cacheTpl, $content);

		include $cacheTpl;
		
	}
}