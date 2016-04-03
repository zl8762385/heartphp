<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 后台管理系统
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:3677989>
 * @lastmodify			2013.04.09
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class contentController extends helper_baseadminController {
	private $system_filed = array('catid', 'modelid');//系统默认字段

	public function __construct() {
		parent::__construct();

		$this->db = D('admin_category_model');
		$this->model_db = D('admin_model_model');
		$this->content_db = D('content_model');//内容模型
		$this->model_filed_db = D('model_filed_model');//模型字段
		$this->pagesize = 10;
		$this->category = $this->get_category();//当前栏目信息
		$this->category_all_tree();
	}

	public function category_all_tree() {
		$this->view->assign('categorys_tree', $this->db->category_all_json());
	}
	/**
	 * 查看模型是否存在
	 * @return
	 */
	public function model_exists() {
		$model_info = $this->get_model_info($this->category['modelid']);
		if($model_info['status'] == '-1') $this->show_message("[{$model_info['name']}]已经执行逻辑删除.");
	}

	/**
	 * 获取栏目
	 * @return
	 */
	public function categorys() {
		$this->view->display();
	}
	/**
	 * 栏目列表
	 * @return 
	 */
	public function index() {
		$page = gpc('p');
		$tree = new tree();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

		$data = $this->db->select('*', 'is_display=1', '', 'orders desc');
		$array = array();
		
		foreach($data as $r) {
			$r['create_date'] = date('Y-m-d H:i:s', $r['createtime']);

			if(empty($r['attach_id'])) {
				$r['name']  = $r['name'];
				$r['str_manage'] = '<a href="'.get_url('admin', 'content', 'content_list', 'catid='.$r['id'].'').'" title=""><span style="font-weight:800;color:red">'.icons('list', '查看文章列表').'</span></a>';
			} else {
				$r['name'] = '<span style="color:red;">'.$r['name'].'</span>';
				$r['str_manage'] = '';
			}
			
			$r['parentid_node'] = ($r['parentid'])? ' class="child-of-node-'.$r['parentid'].'"' : '';
			$array[] = $r;
		}

		$str  = "<tr id='node-\$id' \$parentid_node>
		<td align='left'>\$id</td>
		<td align=''>\$spacer\$name</td>
		<td align=''>\$str_manage</td>
		</tr>";

		$tree->init($array);
		$categorys = $tree->get_tree(0, $str);
		$this->view->assign('categorys', $categorys);
		$this->view->display();
	}

	/**
	 * 查看内容列表
	 * @return
	 */
	public function content_list() {
		$this->model_exists();//查看模型是否存在
		$page = gpc('p');
		$catid = intval(gpc('catid'));
		if(empty($catid)) $this->show_message('参数错误.');
		$filed_list = $this->get_filed_info(array('m_id' => $this->category['modelid'], 'content_list' => 1));
		if(empty($filed_list)) $this->show_message('请模型管理中设置默认显示字段.');

		//设置模型
		$this->content_db->set_model($this->category['modelid']);
		list($count, $lists) = $this->content_db->select_all('*', "catid='$catid'", '', 'id desc', $page, $this->pagesize);

	 	$this->view->assign("pages", $this->page($count, $this->pagesize));
	 	$this->view->assign("lists", $lists);
		$this->view->assign('filed_list', $filed_list);
		$this->view->assign('category', $this->category);
		$this->view->display('content_list');
	}
	
	/**
	 * 获取字段信息
	 * 
	 */
	public function get_filed_info($filed_arr) {
		if(empty($filed_arr)) return false;
		$filed_data = $this->model_filed_db->select($filed_arr);
		return $filed_data;
	}
	/**
	 * 检查字段，并且删除模型字段中没有的字段，进行重组返回
	 * @param $m_id 模型ID
	 * @param $content_fileds 内容字段 
	 */
	public function check_filed($m_id, $fileds){
		if(empty($m_id) || !is_array($fileds)) $this->show_message('请求错误.');

		$content_fileds = array_keys($fileds);
		$model_info = $this->get_model_info($m_id);
		if($model_info) {
			$filed_data = $this->get_filed_info(array('m_id' => $m_id));

			$filed_data_arr = $new_filed_data = array();

			foreach($filed_data as $v){//组合一维数组
				$filed_data_arr[] = $v['name'];
				$new_filed_data[$v['name']] = $v;//用字段名做键值
			}
			if(!empty($filed_data_arr)) $filed_data_arr = array_merge($filed_data_arr, $this->system_filed);

			$diff_filed = array_diff($content_fileds, $filed_data_arr);//字段差，用户提交的字段和数据库中字段对比，计算出来没用的字段

			//OK了，开始过滤没用的字段, 进行重组
			$newdata = array();

			foreach($fileds as $k => $v) {
				if(!in_array($k, $diff_filed)) {//抛弃没用的字段 对数据进行重组
					//字段进行字符串 取值范围判断
					if(isset($new_filed_data[$k]) && $new_filed_data[$k]['number_range']){
						$number_range = json_decode($new_filed_data[$k]['number_range'],1);
						if(!empty($number_range['min']) && strlen($v) < $number_range['min']) {
							$this->show_message("[{$new_filed_data[$k]['title']}]最小 {$number_range['min']}个字符");
						}

						if(!empty($number_range['max']) && strlen($v) > $number_range['max']) {
							$this->show_message("[{$new_filed_data[$k]['title']}]最大 {$number_range['max']}个字符");
						}
					}

					//验证正则
					if(isset($new_filed_data[$k]) && $new_filed_data[$k]['pattern']) {
						if(!preg_match(''.$new_filed_data[$k]['pattern'].'', $v)) {
							$this->show_message("[{$new_filed_data[$k]['title']}]匹配不正确.");
						}
					}

					//如果是数组 转换成字符串
					if(isset($v['pic_title']) && is_array($v['pic_title'])) {//如果有上传图片 and file，带有标题的，单独处理
						$pic_value = array();
						foreach($v['pic_title'] as $pic_k => $pic_v) {
							$pic_value[] = array('title' => $pic_v, 'filename' => $pic_k);
						}

						$v = json_encode($pic_value);
					} else {
						if(is_array($v)) $v = ",".implode(',', $v).",";
					}
					

					//对日期进行转换
					if(isset($new_filed_data[$k]['type']) && $new_filed_data[$k]['type'] == 'datetime') {
						$v = strtotime($v);//转换
					}
					
					//OK 通过喽.
					$newdata[$k] = addslashes($v);
				}
			}
			
			return $newdata;
		} else {
			$this->show_message('模型不存在,或没有设置字段.');
		}
	}

	/**
	 * 增加内容
	 * @return
	 */
	public function add() {
		if(gpc('dosubmit', 'P')) {//提交信息

			$content_data = gpc('data', 'P');
			//检查字段，并且删除模型字段中没有的字段，进行重组返回
			$return_data = $this->check_filed($content_data['modelid'], $content_data);
			$this->content_db->set_model($return_data['modelid']);
			//注销系统中 不需要插入的modelid
			unset($return_data['modelid']);

			//定义基本信息
			$operate = array(//保存 操作做基本信息
							'userid' => $this->userinfo['userid'],
							'username' => $this->userinfo['username'],
							'truename' => $this->userinfo['truename']
						);

			$return_data['createtime'] = time();
			$return_data['operate'] = json_encode($operate);

			//插入数据
			if($this->content_db->insert($return_data)) {
				$ref_url = (gpc('dosubmit', 'P') == '提交并继续发表') ?
						    get_url('admin', 'content', 'add', 'catid='.$content_data['catid']) : 
						    get_url('admin', 'content', 'content_list', 'catid='.$content_data['catid']);
				$this->show_message('操作成功.', '', $ref_url);
			}
		}

		$data = $this->category;
		empty($data) && $this->show_message('操作错误!');

		//获取模型信息 是否执行了 逻辑删除
		$model_info = $this->get_model_info($data['modelid']);
		if($model_info['status'] != '-1') {//执行了 物理删除 则不显示字段
			$filed = H('helper_content_filed');//助手工具包 内容字段
			$filed->set_data($data['modelid']);
			$this->view->assign('form_data', $filed->get_data());
		}

		$this->view->assign('category', $data);
		$this->view->display('add');
	}

	/**
	 * 修改内容
	 * @return
	 */
	public function edit() {
		if(gpc('dosubmit', 'P')) {//提交信息
			$id = gpc('id', 'P');
			$content_data = gpc('data', 'P');
			//检查字段，并且删除模型字段中没有的字段，进行重组返回
			$return_data = $this->check_filed($content_data['modelid'], $content_data);
			$this->content_db->set_model($return_data['modelid']);
			//注销系统中 不需要插入的modelid
			unset($return_data['modelid']);

			//定义基本信息
			$operate = array(//保存 操作做基本信息
							'userid' => $this->userinfo['userid'],
							'username' => $this->userinfo['username'],
							'truename' => $this->userinfo['truename']
						);

			$return_data['updatetime'] = time();
			$return_data['operate'] = json_encode($operate);

			//修改数据
			if($this->content_db->update($return_data, array('id' => $id))) {
				$this->show_message('操作成功.', '', get_url('admin', 'content', 'content_list', 'catid='.$content_data['catid']));
			}
		}

		$id = gpc('id');
		$data = $this->category;
		empty($data) && $this->show_message('操作错误!');

		//切换表 读取数据
		$this->content_db->set_model($data['modelid']);
		$infos = $this->content_db->get_one($id);

		//获取模型信息 是否执行了 逻辑删除
		$model_info = $this->get_model_info($data['modelid']);
		if($model_info['status'] != '-1') {//执行了 物理删除 则不显示字段
			$filed = H('helper_content_filed');//助手工具包 内容字段
			$filed->set_data($data['modelid'], $infos);
			$this->view->assign('form_data', $filed->get_data());
		}

		$this->view->assign('id', $id);
		$this->view->assign('category', $data);
		$this->view->display('edit');
	}

	/**
	 * 删除
	 * @return
	 */
	public function delete() {
		$id = intval(gpc('id'));
		if(empty($this->category)) $this->show_message('操作错误.');

		//切换表
		$this->content_db->set_model($this->category['modelid']);
		if($this->content_db->delete('id='.$id)) {
			$this->show_message('数据删除成功！', '', HTTP_REFERER);
		}
	}

	/**
	 * 获取栏目信息
	 */
	public function get_category() {
		$catid = gpc('catid', 'R');
		if(empty($catid)) return false;

		$data = $this->db->get_one($catid);
		return $data;
	}

	/**
	 * 获取模型信息
	 * @param $mid 模型ID
	 * @return array
	 */
	public function get_model_info($mid) {
		$model_info = $this->model_db->get_one($mid);
		return $model_info;
	}



	public function __destruct() {
		$this->db = $this->category = NULL;

	}
}