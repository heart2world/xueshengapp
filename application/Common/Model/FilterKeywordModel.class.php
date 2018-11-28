<?php
namespace Common\Model;
class FilterKeywordModel extends CommonModel{
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('keyword', 'require', '关键词不能为空！'),
			array('keyword', '', '此关键词已经添加过了！',0,'unique',CommonModel:: MODEL_BOTH),
			 array('effect_area', [1,2,3], '请至少选择一项!','2',"in"),
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}

    protected $_auto = array (
        array('create_time','time',1,'function'),
    );

}