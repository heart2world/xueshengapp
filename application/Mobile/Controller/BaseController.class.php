<?php
namespace Mobile\Controller;

use Common\Controller\AppframeController;

class BaseController extends AppframeController
{
    //根据用户id或手机号获取用户信息
    public function user_info($key){
        if(preg_match('/^1[3456789][0-9]{9}$/',$key) < 1){
            $where['id'] = array('eq',$key);
        }else{
            $where['mobile'] = array('eq',$key);
        }
        $where['user_type'] = array('in','2,3');
        $user_info = M('Users')->where($where)->field('id,user_name,avatar,create_time,score,user_type,mobile,school_id,specialty_id,verify,continuous_day')->find();
        if(!$user_info){
            return false;
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
        if($user_info['specialty_id'] > 0){
            $specialty = M('SchoolProfessional')->where(array('id'=>$user_info['specialty_id']))->find();
            if($specialty){
                $user_info['specialty'] = $specialty['pro_name'];
            }
        }
        return $user_info;
    }

    //处理讨论信息
    public function discuss_action($discuss,$user_id){
        foreach ($discuss as $k=>$v){
            $discuss[$k]['avatar'] = sp_get_image_preview_url($v['avatar']);
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
            $time_distance = $v['create_time']-time();
            if($time_distance >= 3600*24){
                $discuss[$k]['time_ago'] = floor($time_distance/3600*24).'天前';
            }elseif ($time_distance >= 3600){
                $discuss[$k]['time_ago'] = floor($time_distance/3600).'小时前';
            }else{
                $discuss[$k]['time_ago'] = floor($time_distance/60).'分钟前';
            }
        }
        return $discuss;
    }
}