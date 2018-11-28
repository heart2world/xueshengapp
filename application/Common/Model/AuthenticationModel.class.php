<?php
namespace Common\Model;
use Common\Model\CommonModel;
class AuthenticationModel extends CommonModel{
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('school_id', 'require', '学校为必选不能为空！'),
			array('user_id', 'require', '状态异常请重新登录！'),
			array('student_img', 'require', '请上传学生证照片！'),
            array('status',array(1,2),'状态错误！',2,'in'), // 当值不为空的时候判断是否在一个范围内
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}

    protected $_auto = array (
        array('create_time','time',1,'function'),
    );

}