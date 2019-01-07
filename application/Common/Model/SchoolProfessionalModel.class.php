<?php
namespace Common\Model;
use Common\Model\CommonModel;
class SchoolProfessionalModel extends CommonModel{
	//自动验证
	protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('pro_name', 'require', '专业名称不能为空！',self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('pro_name', '', '专业名称已经存在！',self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}


}