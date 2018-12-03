<?php
namespace Mobile\Controller;

use Common\Controller\AppframeController;

class BaseController extends AppframeController
{
    function _initialize(){
        $user_id = intval(I('request.uid'));
        $uu_token = I('request.token');
        if(empty($uu_token) || empty($user_id)){
            $this->ajaxReturn(['status'=>0,'info'=>'您还未登录,请先去登录']);
        }
        $where['id'] = array('eq',$user_id);
        $where['user_type'] = array('in','2,3');
        $user_info = M('Users')->where(array('id'=>$user_id))->field('id,uu_id')->find();
        if(!$user_info){
            $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在']);
        }
        if(!empty($user_info['uu_id']) && $uu_token != $user_info['uu_id']){
            $this->ajaxReturn(['status'=>-2,'info'=>'用户信息验证失败,请重新登录']);
        }
    }

    //根据用户id或手机号获取用户信息
    public function user_info($key){
        if(preg_match('/^1[3456789][0-9]{9}$/',$key) < 1){
            $where['id'] = array('eq',$key);
        }else{
            $where['mobile'] = array('eq',$key);
        }
        $where['user_type'] = array('in','2,3');
        $user_info = M('Users')->where($where)->field('id,user_name,avatar,signature,create_time,score,user_type,mobile,school_id,specialty_id,verify,online_time,continuous_day,aurora,push,uu_id')->find();
        if(!$user_info){
            return false;
        }
        if(!empty($user_info['avatar'])){
            $user_info['avatar'] = sp_get_image_preview_url($user_info['avatar']);
        }
        $user_info['school_name'] = '';
        $user_info['school_type'] = '';
        if($user_info['school_id'] > 0){//获取学校信息
            $school = M('School')->where(array('id'=>$user_info['school_id']))->find();
            if($school){
                $user_info['school_name'] = $school['school_name'];
                $user_info['school_type'] = $school['type'];
            }
        }
        $user_info['specialty'] = '';
        if($user_info['specialty_id'] > 0){//获取专业信息
            $specialty = M('SchoolProfessional')->where(array('id'=>$user_info['specialty_id']))->find();
            if($specialty){
                $user_info['specialty'] = $specialty['pro_name'];
            }
        }
        //检查是否已经认证
        if($user_info['user_type'] == 2) {
            $certification = M('Authentication')->where(array('user_id' => $user_info['id']))->find();
            if ($certification) {
                if($certification['status'] == 1){
                    $user_info['certification'] = 2;//2已认证
                }elseif ($certification['status'] == 0){
                    $user_info['certification'] = 1;//1认证中
                }else{
                    $user_info['certification'] = -1;//-1认证失败
                }
            } else {
                $user_info['certification'] = 0;//还未提交认证
            }
        }else{
            $user_info['certification'] = 2;
        }
        //判断该用户今日是否签到
        $today_time = strtotime(date('Y-m-d'));
        $sign = M('UsersSign')->where(array('user_id'=>$user_info['id'],'sign_time'=>$today_time))->find();
        if($sign){
            $user_info['sign'] = 1;
        }else{
            $user_info['sign'] = 0;
        }
        return $user_info;
    }

    //处理讨论信息
    public function discuss_action($discuss,$user_id,$keyword = ''){
        foreach ($discuss as $k=>$v){
            if(!empty($v['avatar'])){
                $discuss[$k]['avatar'] = sp_get_image_preview_url($v['avatar']);
            }
            $collect = M('UsersDiscuss')->where(array('user_id'=>$user_id,'discuss_id'=>$v['id'],'type'=>1))->find();
            if($collect){
                $discuss[$k]['collect'] = 1;
            }else{
                $discuss[$k]['collect'] = 0;
            }
            if(!empty($v['image'])) {
                $images = explode(',', $v['image']);
                foreach ($images as $x => $y) {
                    $images[$x] = sp_get_image_preview_url($y);
                }
            }else{
                $images = array();
            }
            $discuss[$k]['image'] = $images;
            $time_distance = time()-$v['create_time'];
            if($time_distance >= 3600*24*5){
                $discuss[$k]['time_ago'] = date('Y-m-d',$v['create_time']);
            }elseif ($time_distance >= 3600*24){
                $discuss[$k]['time_ago'] = floor($time_distance/3600*24).'天前';
            }elseif ($time_distance >= 3600){
                $discuss[$k]['time_ago'] = floor($time_distance/3600).'小时前';
            }else{
                $discuss[$k]['time_ago'] = floor($time_distance/60).'分钟前';
            }
            if($keyword == '') {
                if (empty($v['name'])) {
                    $content = strip_tags(htmlspecialchars_decode($v['content']));
                    $discuss[$k]['name'] = mb_substr($content, 0, 30, "utf-8");
                }
            }else{
                $content = strip_tags(htmlspecialchars_decode($v['content']));//内容
                $position = mb_stripos($content,$keyword);//字符在字符串中的位置
                if($position !== false){
                    $start = $position-10;
                    if($start < 0){
                        $start = 0;
                    }
                    $length = 10;
                    if($position < 10){
                        $length = $position;
                    }
                    $the_info = mb_substr($content,$start,$length,"utf-8").mb_substr($content,$position,mb_strlen($keyword)+20,"utf-8");
                }else{
                    $the_info = mb_substr($content, 0, 30, "utf-8");
                }
                if(empty($v['name'])){//如果标题为空
                    $discuss[$k]['name'] = $the_info;//则内容变为标题
                    $discuss[$k]['content'] = '';//内容再变为空
                }else{//如果有标题
                    if($position){//如果内容符合
                        if($position > 10){//如果符合位置大于10
                            $discuss[$k]['content'] = '...'.$the_info.'...';
                        }else{//如果符合位置小于10
                            $discuss[$k]['content'] = $the_info.'...';
                        }
                    }else{//如果内容不符合
                        $discuss[$k]['content'] = '';//则内容变为空
                    }
                }
            }
        }
        return $discuss;
    }

    //保存消息
    public function save_message($user_id,$from_uid,$type,$desc = null,$time = ''){
        $from_user = M('Users')->where(array('id'=>$from_uid))->field('id,user_name,avatar')->find();
        if($type == 1){
            $title = $from_user['user_name'].'点赞了您的评论';
        }elseif ($type == 2 || $type == 3){
            $title = $from_user['user_name'].'回复了您：';
        }else{
            $title = '您于 '.date('Y-m-d H:i:s',$time).' 发起的学校认证已被通过。';
        }
        $dataInfo = [
            'user_id' => $user_id,
            'from_uid' => $from_uid,
            'from_avatar' => $from_user['avatar'],
            'title' => $title,
            'desc' => $desc,
            'create_time' => time()
        ];
        M('UsersMessage')->add($dataInfo);
    }

    //判断发布讨论和评论回复是否获得积分
    public function whether_score($user_id,$type){
        //获取缓存数据
        $set_score = S('score_setting');
        if (empty($set_score) || $set_score == false) {
            $set_score = score_setting();
        }
        $gain_score = 0;//获得积分
        $publish_discussion = $set_score['publish_discussion'];//发布讨论获得积分
        $publish_discussion_every_day_max = $set_score['publish_discussion_every_day_max'];//每人每天限获得多少次
        $publish_reply = $set_score['publish_reply'];//发表回复获得积分
        $publish_reply_every_day_max = $set_score['publish_reply_every_day_max'];//每人每天限获得多少次

        $today_begin = strtotime(date('Y-m-d'));
        $today_end = strtotime(date('Y-m-d 23:59:59'));
        $where['user_id'] = array('eq',$user_id);
        $where['create_time'] = array('between',[$today_begin,$today_end]);
        if($type == 1){//发布讨论
            //获取该用户当天已发讨论数量
            $count = M('Discuss')->where($where)->count();
            if($count <= $publish_discussion_every_day_max){
                $gain_score = $publish_discussion;
            }
        }else{//评论回复
            //获取当天评论数量
            $count_comment = M('Comment')->where($where)->count();
            //获取当前回复数量
            $count_reply = M('Reply')->where($where)->count();
            if($count_comment+$count_reply <= $publish_reply_every_day_max){
                $gain_score = $publish_reply;
            }
        }
        return $gain_score;
    }

    //保存积分记录
    public function save_score($user_id,$score,$type){
        $month = strtotime(date('Y-m-01'));
        ($type == 5) ? $status = 0 : $status = 1;
        $dataInfo = [
            'user_id' => $user_id,
            'status' => $status,
            'score' => $score,
            'type' => $type,
            'month' => $month,
            'create_time' => time()
        ];
        M('UsersScore')->add($dataInfo);
    }
}