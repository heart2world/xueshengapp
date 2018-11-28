<?php
namespace Common\Model;
class ScriptureModel extends CommonModel{
	//自动验证
	protected $_validate = array(
	    //gift_name,type,price,,cover_img,product_intro,status,create_time
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('title', 'require', '标题不能为空！'),
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}

    protected $_auto = array (
        array('create_time','time',1,'function'),
    );

}