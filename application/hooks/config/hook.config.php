<?php
/**
 * 一个钩子可以挂载多个类函数
 * type: //3种类型, 
 * 		plugin:不在系统执行中运行。任意地方调用.   
 * 		controller_top:在系统框架中执行，框架运行时在controller运行前执行.  
 * 		controller_bottom:在系统框架中执行，框架运行时在controller运行后执行
 * 
 */
//插件模式可以在任何地方调用
$hook['plugin']= array(
					'arge_users' => array(
						array('users', 'users1'),
						array('users', 'users2')
					),
					'admin_test' => array(
						array('admin', 'ad1'),
						array('admin', 'ad2')
					)
				);
//框架运行时在controller运行前执行.
$hook['controller_top']= array(
					'arge_users' => array(
						array('users', 'controller_top'),
						array('admin', 'ad1')
					)
				);
//框架运行时在controller运行后执行
$hook['controller_bottom']= array(
					'arge_users' => array(
						array('users', 'controller_bottom')
					)
				);