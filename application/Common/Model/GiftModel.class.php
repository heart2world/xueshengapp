<?php
namespace Common\Model;
class GiftModel extends CommonModel{
	//自动验证
	protected $_validate = array(
	    //gift_name,type,price,,cover_img,product_intro,status,create_time
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('gift_name', 'require', '商品名称不能为空！'),
			//array('price', 'require', '！'),
			array('surplus', 'require', '请填写剩余库存！'),
            array('status',array(0,1),'上架状态错误！',2,'in'), // 当值不为空的时候判断是否在一个范围内
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}

    protected $_auto = array (
        array('create_time','time',1,'function'),
    );

}