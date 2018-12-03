<?php
namespace Common\Model;
class GiftModel extends CommonModel{
	//自动验证
	protected $_validate = array(
	    //gift_name,type,price,,cover_img,product_intro,status,create_time
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('gift_name', 'require', '商品名称不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH),
        array('cover_img', 'require', '商品封面不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('surplus', 'require', '请填写商品库存！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('status',array(0,1),'上架状态错误！',2,'in'), // 当值不为空的时候判断是否在一个范围内
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}

    protected $_auto = array (
        array('create_time','time',1,'function'),
        array ('cover_img', 'sp_asset_relative_url', self::MODEL_BOTH, 'function'),
    );

}