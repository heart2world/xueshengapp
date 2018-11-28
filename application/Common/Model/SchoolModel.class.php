<?php
namespace Common\Model;
use Common\Model\CommonModel;
class SchoolModel extends CommonModel{
	//自动验证
	protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('school_name', 'require', '学校名称不能为空！',self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('professional_type','require','学校类型不能为空！',0,'regex',self::MODEL_BOTH), //默认情况下用正则进行验证
        array('type',array(1,2),'学校类型错误！',2,'in'), // 当值不为空的时候判断是否在一个范围内
        array('address', 'require', '所在地区不能为空！',self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}

    protected $_auto = array (
        array('create_time','time',1,'function'),
    );

}