<?php

/**
 * 首页控制器
 * @copyright  yeoman-chen
 * @author yeoman
 * @since 2016.09.30
 */

namespace Yeo\Controller;

use Yeo\Core\YeoController;


class IndexController extends YeoController
{
	/**
	 * 默认控制器
	 */
	public function indexAction()
	{
		$this->yeoDb;
		$data = ['code' => 1000,'message' => 'welcome to yeophp!' ,'content' => '谢谢！'];
		$this->apiResultStandard($data['code'],$data['message'],$data['content']);
	}
}