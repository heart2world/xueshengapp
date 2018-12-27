<?php
namespace Common\Model;
class ScriptureModel extends CommonModel{
	//自动验证
	protected $_validate = array(
	    //gift_name,type,price,,cover_img,product_intro,status,create_time
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('title', 'require', '笔记标题不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('cover_img', 'require', '笔记封面不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('content', 'require', '笔记正文不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
        if(!empty($data['content'])){
            $data['content']=htmlspecialchars_decode($data['content']);
        }
	}

    protected $_auto = array (
        array('create_time','time',1,'function'),
        array ('cover_img', 'sp_asset_relative_url', self::MODEL_BOTH, 'function'),
    );

}