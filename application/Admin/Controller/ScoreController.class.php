<?php

namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\FilterKeywordModel;

class ScoreController extends AdminbaseController
{

    /**
     * @var FilterKeywordModel
     */
    protected $order;

    public function _initialize()
    {
        parent::_initialize();
    }

    // 积分设置
    public function index()
    {
        $set = score_setting();
        $this->assign("score", $set);
        $this->display();
    }

    // 设置提交
    public function editPost()
    {
        if (IS_POST) {
            $set_data = score_setting();
            $postData = I('post.');
            foreach ($postData as $k=>$v){
                if($v <= 0){
                    $this->error("积分不能小于0");
                }
            }
            $first_day = $postData['first_day'];
            $max_single_time = $postData['max_single_time'];
            if($max_single_time < $first_day){
                $this->error("连续登录单次最多获得积分不能小于首天登录获得积分");
            }
            score_setting(true);
            //获取剩余时间
            $less_time = strtotime(date('Y-m-d 23:59:59'))-time();
            S('score_setting',$set_data,$less_time);
            $this->success("保存成功");
        }
    }

}