<?php
namespace Common\Model;

class LabelModel extends CommonModel{
    
	//自动验证
	protected $_validate = array(
		//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		array('listorder', 'require', '标签序号不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('listorder', '/^\d+$/', '标签序号只能为非负整数！', 0, 'regex', CommonModel:: MODEL_BOTH ),
		array('name', 'require', '标签名称不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('name', '', '标签名称已存在！', 1, 'unique', CommonModel:: MODEL_BOTH ),
	);
	
	protected $_auto = array (
        array('create_time','time',1,'function'),
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
	
}