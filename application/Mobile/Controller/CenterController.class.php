<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/29
 * Time: 11:22
 */

namespace Mobile\Controller;


class CenterController extends BaseController
{
    //获取个人信息
    public function mine(){
        $user_id = intval(I('request.uid'));
        $user_info = $this->user_info($user_id);//获取用户信息
        if($user_info == false){
            $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
        }
        $this->ajaxReturn(['status'=>1,'user'=>$user_info]);
    }

    //修改个人资料
    public function user_action(){
        if(IS_POST){
            $user_id = intval(I('request.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
            }
            $dataInfo = array();
            $avatar = I('post.avatar');//头像
            if(!empty($avatar)){
                $dataInfo['avatar'] = sp_asset_relative_url($avatar);
            }
            $username = trim(I('post.username'));//姓名
            if($username != ''){
                //获取关键词并过滤
                $effect = 1;
                $where['effect_area'] = array('like',"%$effect%");
                $filter_keyword = M('FilterKeyword')->where($where)->select();
                foreach ($filter_keyword as $k=>$v){
                    if(strpos($username,$v['keyword']) !== false){
                        $this->ajaxReturn(['status'=>0,'info'=>"您输入的内容包含敏感信息,请检查"]);
                    }
                }
                $dataInfo['user_name'] = $username;
            }else{
                $this->ajaxReturn(['status'=>0,'info'=>'姓名不能为空']);
            }
            $signature = trim(I('post.signature'));//个人简介
            $dataInfo['signature'] = $signature;
            if(count($dataInfo) > 0){
                $dataInfo['id'] = $user_id;
                $result = M('Users')->save($dataInfo);
                if($result !== false){
                    $this->ajaxReturn(['status'=>1,'info'=>'更新成功']);
                }else{
                    $this->ajaxReturn(['status'=>0,'info'=>'网络错误,请稍后再试']);
                }
            }else{
                $this->ajaxReturn(['status'=>0,'info'=>'更新失败']);
            }
        }
    }

    //获取认证信息
    public function cert_info(){
        $user_id = intval(I('request.uid'));
        $where['user_id'] = array('eq',$user_id);
        $where['status'] = array('in','0,1');
        $info = M('Authentication')->where($where)->order('create_time desc')->limit(1)->find();
        if($info){
            $info['school_name'] = '';
            $school = M('School')->where(array('id'=>$info['school_id']))->find();
            if($school){
                $info['school_name'] = $school['school_name'];
            }
            $info['student_true'] = '';
            if(!empty($info['student_img'])){
                $info['student_true'] = sp_get_image_preview_url($info['student_img']);
            }
        }else{
            $info = 0;
        }
        $this->ajaxReturn(['status'=>1,'info'=>$info]);
    }

    //用户认证
    public function certification(){
        if(IS_POST){
            $user_id = intval(I('request.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
            }
            if($user_info['user_type'] != 2){
                $this->ajaxReturn(['status'=>0,'info'=>'仅学生用户需要验证']);
            }
            if($user_info['certification'] == 1){
                $this->ajaxReturn(['status'=>0,'info'=>'您的申请正在认证中,请勿重复申请']);
            }
            $school_id = intval(I('post.sid'));
            $image = I('post.image');
            if(empty($school_id) || empty($image)){
                $this->ajaxReturn(['status'=>0,'info'=>'请选择学校和上传学生或毕业证']);
            }
            if($user_info['certification'] == 0) {
                $dataInfo = [
                    'user_id' => $user_id,
                    'school_id' => $school_id,
                    'student_img' => sp_asset_relative_url($image),
                    'create_time' => time()
                ];
                $result = M('Authentication')->add($dataInfo);
            }else{
                $dataInfo = [
                    'user_id' => $user_id,
                    'school_id' => $school_id,
                    'student_img' => sp_asset_relative_url($image),
                    'create_time' => time()
                ];
                M('Users')->save(array('id'=>$user_id,'verify'=>0));
                $result = M('Authentication')->add($dataInfo);
            }
            if($result != false){
                $this->ajaxReturn(['status'=>1,'info'=>'申请成功']);
            }else{
                $this->ajaxReturn(['status'=>0,'info'=>'网络错误,请稍后再试']);
            }
        }
    }

    //我的收藏页
    public function mine_collect(){
        $user_id = intval(I('request.uid'));
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 20;
        $start = ($now_page - 1) * $pageSize;

        $resultArray = array();//总数组

        $where['user_id'] = array('eq',$user_id);
        $total_count = M('UsersCollect')->where($where)->count();
        $total_page = ceil($total_count/$pageSize);
        $collect = M('UsersCollect')->where($where)->limit($start,$pageSize)->select();

        if(count($collect) > 0){
            $discuss_array = array();//讨论数组
            $scripture_array = array();//笔记数组
            foreach ($collect as $k=>$v){
                ($v['type'] == 1) ? $discuss_array[] = $v['collect_id'] : $scripture_array[] = $v['collect_id'];
            }
            $discuss = array();
            if(count($discuss_array) > 0){//讨论
                $where_discuss['d.status'] = array('eq',1);
                $where_discuss['d.id'] = array('in',$discuss_array);
                $discuss = M('Discuss')->alias('d')
                    ->join('h2w_users as u on u.id=d.user_id')
                    ->where($where_discuss)
                    ->field('d.*,u.user_name,u.avatar,u.user_type,u.verify_id usc_id,u.verify')
                    ->order('d.create_time desc')
                    ->select();
                //处理讨论信息
                $discuss = $this->discuss_action($discuss,$user_id);
                foreach ($discuss as $k=>$v){
                    $discuss[$k]['collect_type'] = 1;
                    foreach ($collect as $m=>$n){
                        if($n['collect_id'] == $v['id'] && $n['type'] == 1){
                            $discuss[$k]['timestamp'] = $n['create_time'];
                        }
                    }
                }
            }
            $scripture = array();
            if(count($scripture_array) > 0){//笔记
                $where_scripture['status'] = array('eq',0);
                $where_scripture['id'] = array('in',$scripture_array);
                $scripture = M('Scripture')->where($where_scripture)->order('create_time desc')->select();
                foreach ($scripture as $k=>$v){
                    $scripture[$k]['collect_type'] = 2;
                    if(!empty($v['cover_img'])){
                        $scripture[$k]['cover_img'] = sp_get_image_preview_url($v['cover_img']);
                    }
                    if(!empty($v['content'])){
                        $scripture[$k]['content'] = strip_tags($v['content']);
                    }
                    $collect_info = M('UsersCollect')->where(array('user_id'=>$user_id,'collect_id'=>$v['id'],'type'=>2))->find();
                    if($collect_info){
                        $scripture[$k]['keep'] = 1;
                    }else{
                        $scripture[$k]['keep'] = 0;
                    }
                    foreach ($collect as $m=>$n){
                        if($n['collect_id'] == $v['id'] && $n['type'] == 2){
                            $scripture[$k]['timestamp'] = $n['create_time'];
                        }
                    }
                }
            }
            $resultArray = array_merge_recursive($discuss,$scripture);//合并数组
            $timestamp_array = array();//取出时间
            foreach ($resultArray as $k=>$v){
                $timestamp_array[] = $v['timestamp'];
            }
            array_multisort($timestamp_array,SORT_DESC,$resultArray);//时间倒叙排列
        }
        $this->ajaxReturn(['status'=>1,'list'=>$resultArray,'total_page'=>$total_page]);
    }

    //我的积分页
    public function mine_score(){
        $user_id = intval(I('request.uid'));
        $user_info = $this->user_info($user_id);//获取用户信息
        if($user_info == false){
            $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
        }
        //获取积分明细按月分组
        $group = M('UsersScore')->where(array('user_id'=>$user_id))->order('month desc')->group('month')->select();
        $groupArray = array();
        if(count($group) > 0){
            foreach ($group as $k=>$v){
                $groupArray[] = [
                    'month' => date('Y-m',$v['month']),
                    'month_time' => $v['month'],
                    'income' => 0,
                    'expend' => 0,
                    'list' => array()
                ];
            }
            //获取所有积分明细
            $scoreList = M('UsersScore')->where(array('user_id'=>$user_id))->order('create_time desc')->select();
            foreach ($groupArray as $k=>$v){
                $income = 0;//收入
                $expend = 0;//支出
                foreach ($scoreList as $m=>$n){
                    if($n['month'] == $v['month_time']){
                        $n['create_time'] = date('Y-m-d H:i:s',$n['create_time']);
                        $groupArray[$k]['list'][] = $n;
                        ($n['status'] == 1) ? $income = $income+$n['score'] : $expend = $expend+$n['score'];
                    }
                }
                $groupArray[$k]['income'] = $income;
                $groupArray[$k]['expend'] = $expend;
            }
        }
        $this->ajaxReturn(['status'=>1,'score'=>$user_info['score'],'list'=>$groupArray]);
    }

    //我的兑换商品页
    public function mine_order(){
        $user_id = intval(I('request.uid'));
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 20;
        $start = ($now_page - 1) * $pageSize;

        $where['user_id'] = array('eq',$user_id);
        $total_count = M('OrderList')->where($where)->count();
        $total_page = ceil($total_count/$pageSize);
        $orderList = M('OrderList')->where($where)->order('create_time desc')->limit($start,$pageSize)->select();
        foreach ($orderList as $k=>$v){
            if(!empty($v['gift_img'])){
                $orderList[$k]['gift_img'] = sp_get_image_preview_url($v['gift_img']);
            }
            $orderList[$k]['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
        }
        $this->ajaxReturn(['status'=>1,'list'=>$orderList,'total_page'=>$total_page]);
    }

    //设置消息推送
    public function site_push(){
        if(IS_POST){
            $user_id = intval(I('request.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
            }
            if($user_info['push'] == 1){
                $result = M('Users')->save(array('id'=>$user_id,'push'=>0));
                if($result){
                    $this->ajaxReturn(['status'=>1,'info'=>'关闭消息推送成功']);
                }
            }else{
                $result = M('Users')->save(array('id'=>$user_id,'push'=>1));
                if($result){
                    $this->ajaxReturn(['status'=>1,'info'=>'开启消息推送成功']);
                }
            }
            $this->ajaxReturn(['status'=>-1,'info'=>'网络错误,请稍后再试']);
        }
    }

    //关于我们
    public function about(){
        $info = [
            'mobile' => "18487358672",
            'introduction' => "思源APP为一款高中在校生和大学生以及毕业工作学长等交流的平台，旨在以学长们的学习经验和社会经验来从学习和生活方面帮助高中学弟学妹，度过高中这一段美好的时期，有着更好的升学体验。"
        ];
        $this->ajaxReturn(['status'=>1,'info'=>$info]);
    }

    //退出登录
    public function logout(){
        if(IS_POST){
            $user_id = intval(I('request.uid'));
            $dataInfo = array('id'=>$user_id,'aurora'=>null);
            $result = M('Users')->where(array('id'=>$user_id))->save($dataInfo);
            if($result !== false){
                $this->ajaxReturn(['status'=>1,'info'=>'退出成功']);
            }else{
                $this->ajaxReturn(['status'=>0,'info'=>'网络错误']);
            }
        }
    }
}