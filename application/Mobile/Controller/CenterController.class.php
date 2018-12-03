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
            $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在']);
        }
        $this->ajaxReturn(['status'=>1,'user'=>$user_info]);
    }

    //个人中心
    public function index(){
        $user_id = intval(I('request.uid'));
        $user_info = $this->user_info($user_id);//获取用户信息
        if($user_info == false){
            $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在']);
        }
        //获取未读消息数量
        $read = M('UsersMessage')->where(array('user_id'=>$user_id,'read'=>0))->count();
        $this->ajaxReturn(['status'=>1,'user'=>$user_info,'un_read'=>$read]);
    }

    //修改个人资料
    public function user_action(){
        if(IS_POST){
            $user_id = intval(I('request.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在']);
            }
            $dataInfo = array();
            $avatar = I('post.avatar');//头像
            if(!empty($avatar)){
                $dataInfo['avatar'] = sp_asset_relative_url($avatar);
            }
            $username = trim(I('post.username'));//姓名
            if(!empty($username)){
                //获取关键词并过滤
                $effect = 1;
                $where['effect_area'] = array('like',"%$effect%");
                $filter_keyword = M('FilterKeyword')->where($where)->select();
                foreach ($filter_keyword as $k=>$v){
                    if(strpos($v['keyword'],$username) !== false){
                        $this->ajaxReturn(['status'=>0,'info'=>"更新失败,昵称存在非法字符"]);
                    }
                }
                $dataInfo['user_name'] = $username;
            }
            $signature = trim(I('post.signature'));
            if(!empty($signature)){
                $dataInfo['signature'] = $signature;
            }
            if(count($dataInfo) > 0){
                $dataInfo['id'] = $user_id;
                $result = M('Users')->save($dataInfo);
                if($result){
                    $this->ajaxReturn(['status'=>1,'info'=>'更新成功']);
                }else{
                    $this->ajaxReturn(['status'=>0,'info'=>'网络错误,请稍后再试']);
                }
            }else{
                $this->ajaxReturn(['status'=>0,'info'=>'更新失败']);
            }
        }
    }

    //用户认证
    public function certification(){
        if(IS_POST){
            $user_id = intval(I('request.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在']);
            }
            if($user_info['user_type'] != 2){
                $this->ajaxReturn(['status'=>0,'info'=>'仅学生用户需要验证']);
            }
            if($user_info['certification'] == 1){
                $this->ajaxReturn(['status'=>0,'info'=>'您的申请正在认证中,请勿重复申请']);
            }
            if($user_info['certification'] == 2){
                $this->ajaxReturn(['status'=>0,'info'=>'您已经认证通过了,不用再申请了']);
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
                    'school_id' => $school_id,
                    'student_img' => sp_asset_relative_url($image),
                    'create_time' => time(),
                    'status' => 0
                ];
                $result = M('Authentication')->where(array('user_id'=>$user_id))->save($dataInfo);
            }
            if($result){
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

        $collect = M('UsersDiscuss')->where(array('user_id'=>$user_id,'type'=>1))->select();
        if(count($collect) > 0){
            $relation_array = array();
            foreach ($collect as $k=>$v){
                $relation_array[] = $v['discuss_id'];
            }
            $where['d.status'] = array('eq',1);
            $where['d.id'] = array('in',$relation_array);
            $total_count = M('Discuss')->alias('d')->where($where)->count();
            $discuss = M('Discuss')->alias('d')
                ->join('h2w_users as u on u.id=d.user_id')
                ->join('h2w_school as s on s.id=d.school_id')
                ->where($where)
                ->field('d.*,u.user_name,u.avatar,u.user_type,s.school_name')
                ->order('d.create_time desc')
                ->limit($start,$pageSize)
                ->select();
            //处理讨论信息
            $discuss = $this->discuss_action($discuss,$user_id);
        }else{
            $total_count = 0;
            $discuss = array();
        }
        $total_page = ceil($total_count/$pageSize);
        $this->ajaxReturn(['status'=>1,'discuss'=>$discuss,'total_page'=>$total_page]);
    }

    //我的积分页
    public function mine_score(){
        $user_id = intval(I('request.uid'));
        $user_info = $this->user_info($user_id);//获取用户信息
        if($user_info == false){
            $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在']);
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
            $scoreList = M('UsersScore')->where(array('user_id'=>$user_id))->select();
            foreach ($groupArray as $k=>$v){
                $income = 0;//收入
                $expend = 0;//支出
                foreach ($scoreList as $m=>$n){
                    if($n['month'] == $v['month_time']){
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
        }
        $this->ajaxReturn(['status'=>1,'list'=>$orderList,'total_page'=>$total_page]);
    }

    //设置消息推送
    public function site_push(){
        if(IS_POST){
            $user_id = intval(I('request.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在']);
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
            'mobile' => "023-66776677",
            'introduction' => "随着信息技术的不断创新，信息化校园建设的不断深入，带动了移动应用的快速发展。为了更好的服务于全校师生，上海财经大学现推出iSufe应用，现已良好的支持iPhone和Android终端，目前已推出服务包括新闻、通知公告、空闲教室查询以及wifi申请功能，后续将推出校园地图、公共服务指南、就业信息查询等各类实用功能。在移动互联网高速发展的今天，iSufe将会在未来不断的完善和充实，为全校师生的工作和生活提供更为贴切的服务。"
        ];
        $this->ajaxReturn(['status'=>1,'info'=>$info]);
//        $keyword = '快速';
//        $content = $info['introduction'];
//        $position = mb_stripos($content,$keyword);//字符在字符串中的位置
//        if($position !== false){
//            $start = $position-10;
//            if($start < 0){
//                $start = 0;
//            }
//            $length = 10;
//            if($position < 10){
//                $length = $position;
//            }
//            $the_info = mb_substr($content,$start,$length,"utf-8").mb_substr($content,$position,mb_strlen($keyword)+20,"utf-8");
//        }else{
//            $the_info = mb_substr($content, 0, 30, "utf-8");
//        }
//        $this->ajaxReturn(['status'=>1,'info'=>$info,'res'=>$the_info,'pos'=>$position]);
    }
}