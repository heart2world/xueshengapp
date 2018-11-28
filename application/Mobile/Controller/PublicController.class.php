<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/27
 * Time: 14:02
 */

namespace Mobile\Controller;

class PublicController extends BaseController
{
    //登录
    public function login(){
        if(IS_POST){
            $mobile = I('post.mobile');
            $password = I('post.password');
            if(empty($mobile)){
                $this->ajaxReturn(['status'=>0,'info'=>'请输入手机号']);
            }
            if(empty($password)){
                $this->ajaxReturn(['status'=>0,'info'=>'请输入密码']);
            }
            $where['mobile'] = array('eq',$mobile);
            $where['user_type'] = array('in','2,3');
            $user= M('Users')->where($where)->find();
            if($user){
                if(sp_password($password) == $user['user_pass']){
                    $user_info = $this->user_info($mobile);
                    $this->ajaxReturn(['status'=>1,'info'=>'登录成功','user'=>$user_info]);
                }else{
                    $this->ajaxReturn(['status'=>0,'info'=>'密码错误,请检查']);
                }
            }else{
                $this->ajaxReturn(['status'=>0,'info'=>'当前账号不存在']);
            }
        }
    }

    //找回密码
    public function find_pass(){
        if(IS_POST){
            $mobile = I('post.mobile');
            $yzm = I('post.code');
            $password = I('post.password');
            if(preg_match('/^1[3456789][0-9]{9}$/',$mobile) < 1){
                $this->ajaxReturn(['status'=>0,'info'=>'请输入正确的手机号']);
            }
            if(empty($yzm)){
                $this->ajaxReturn(['status'=>0,'info'=>'请输入获取的短信验证码']);
            }
            if(empty($password)){
                $this->ajaxReturn(['status'=>0,'info'=>'请输入新密码']);
            }
            if(preg_match('/^[a-zA-Z0-9]{6,18}$/',$password) < 1){
                $this->ajaxReturn(['status'=>0,'info'=>'请输入正确格式的密码']);
            }
            if(!check_send_pass_code($mobile,trim($yzm))){
                $this->ajaxReturn(['status'=>0,'info'=>'验证码输入有误']);
            }
            $user = M('Users')->where(array('mobile'=>$mobile,'user_type'=>array('in','2,3')))->find();
            if(!$user){
                $this->ajaxReturn(['status'=>0,'info'=>'不存在该账户信息']);
            }
            if(M('Users')->save(array('id'=>$user['id'],'user_pass'=>sp_password($password))) !== false){
                $this->ajaxReturn(['status'=>1,'info'=>'新密码设置成功']);
            }else{
                $this->ajaxReturn(['status'=>-1,'info'=>'网络错误,请稍后再试']);
            }
        }
    }

    //用户注册
    public function register(){
        if(IS_POST){
            $mobile = I('post.mobile');
            $yzm = I('post.code');
            $password = I('post.password');
            if(preg_match('/^1[3456789][0-9]{9}$/',$mobile) < 1){
                $this->ajaxReturn(['status'=>0,'info'=>'请输入正确的手机号']);
            }
            if(empty($yzm)){
                $this->ajaxReturn(['status'=>0,'info'=>'请输入获取的短信验证码']);
            }
            if(empty($password)){
                $this->ajaxReturn(['status'=>0,'info'=>'请设置登录密码']);
            }
            if(preg_match('/^[a-zA-Z0-9]{6,18}$/',$password) < 1){
                $this->ajaxReturn(['status'=>0,'info'=>'请输入正确格式的密码']);
            }
            if(!check_send_code($mobile,trim($yzm))){
                $this->ajaxReturn(['status'=>0,'info'=>'验证码输入有误']);
            }
            $dataInfo = [
                'user_login' => $mobile,
                'user_pass' => sp_password($password),
                'create_time' => date('Y-m-d H:i:s'),
                'user_status' => 1,
                'user_type' => 2,//先默认为学生
                'mobile' => $mobile
            ];
            $result_id = M('Users')->add($dataInfo);
            if($result_id){
                M('Users')->save(array('id'=>$result_id,'user_name'=>'用户'.$result_id));
                $user_info = $this->user_info($result_id);
                $this->ajaxReturn(['status'=>1,'info'=>'注册成功','user'=>$user_info]);
            }else{
                $this->ajaxReturn(['status'=>-1,'info'=>'网络错误,请稍后再试']);
            }
        }
    }

    //设置用户角色
    public function user_role(){
        if(IS_POST){
            $user_id = intval(I('post.uid'));
            $type = intval(I('post.type'));
            if(!in_array($type,array(2,3))){
                $type = 2;
            }
            if(M('Users')->save(array('id'=>$user_id,'user_type'=>$type)) !== false){
                $this->ajaxReturn(['status'=>1,'info'=>'设置成功']);
            }else{
                $this->ajaxReturn(['status'=>-1,'info'=>'网络错误,请稍后再试']);
            }
        }
    }

    //设置用户所选学校
    public function user_school(){
        if(IS_POST){
            $school_id = intval(I('post.school_id'));
            $user_id = intval(I('post.uid'));
            $school = M('School')->where(array('id'=>$school_id))->find();
            if(!$school){
                $this->ajaxReturn(['status'=>0,'info'=>'选择失败,不存在该学校']);
            }
            if(M('Users')->save(array('id'=>$user_id,'school_id'=>$school['id'])) !== false){
                $this->ajaxReturn(['status'=>1,'info'=>'设置成功']);
            }else{
                $this->ajaxReturn(['status'=>-1,'info'=>'网络错误,请稍后再试']);
            }
        }
    }

    //设置用户所选专业
    public function user_specialty(){
        if(IS_POST){
            $school_id = intval(I('post.school_id'));
            $specialty_id = intval(I('post.specialty_id'));
            $user_id = intval(I('post.uid'));
            $specialty = M('SchoolProfessional')->where(array('id'=>$specialty_id,'school_id'=>$school_id))->find();
            if(!$specialty){
                $this->ajaxReturn(['status'=>0,'info'=>'选择失败,不存在该专业']);
            }
            if(M('Users')->save(array('id'=>$user_id,'specialty_id'=>$specialty['id'])) !== false){
                $this->ajaxReturn(['status'=>1,'info'=>'设置成功']);
            }else{
                $this->ajaxReturn(['status'=>-1,'info'=>'网络错误,请稍后再试']);
            }
        }
    }

    //获取学校列表
    public function school_list(){
        $type = intval(I('request.type'));
        if(!in_array($type,array(1,2))){//默认大学
            $type = 1;
        }
        $where['type'] = array('eq',$type);
        $where['status'] = array('eq',0);
        $keyword = I('request.keyword');
        if($keyword){
            $where['keyword'] = array('like',"%$keyword%");
        }
        $school_group = M('School')->where($where)->group('first')->select();
        $group_array = array();
        foreach ($school_group as $k=>$v){
            $group_array[]['name'] = $v['first'];
        }
        if(count($group_array) > 0){
            $school = M('School')->where($where)->field('id,school_name,first')->select();
            foreach ($group_array as $k=>$v){
                foreach ($school as $x=>$y){
                    if($y['first'] == $v['name']){
                        $group_array[$k]['list'][] = $y;
                    }
                }
            }
        }
        $this->ajaxReturn(['status'=>1,'school'=>$group_array]);
    }

    //获取专业列表
    public function specialty_list(){
        $school_id = intval(I('request.school_id'));
        $where['school_id'] = array('eq',$school_id);
        $where['status'] = array('eq',0);
        $keyword = I('request.keyword');
        if($keyword){
            $where['pro_name'] = array('like',"%$keyword%");
        }
        $specialty = M('SchoolProfessional')->where($where)->field('id,pro_name')->select();
        $this->ajaxReturn(['status'=>1,'specialty'=>$specialty]);
    }

    //获取标签列表
    public function label_list(){
        $label = M('Label')->where(array('status'=>1))->order('listorder asc')->field('id,name')->select();
        $this->ajaxReturn(['status'=>1,'label'=>$label]);
    }

    //设置用户标签
    public function user_label(){
        if(IS_POST){
            $user_id = intval(I('post.uid'));
            $label = I('post.label');
            if(empty($label)){
                $this->ajaxReturn(['status'=>1,'info'=>'设置成功']);
            }
            $user_info = $this->user_info($user_id);
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在']);
            }
            $where['id'] = array('in',$label);
            $labelInfo = M('Label')->where($where)->select();
            $relationArray = array();
            foreach ($labelInfo as $k=>$v){
                $relationArray[] = [
                    'user_id' => $user_id,
                    'label_id' => $v['id']
                ];
            }
            if(count($relationArray) > 0){
                M('UsersLabel')->addAll($relationArray);
            }
            $this->ajaxReturn(['status'=>1,'info'=>'设置成功']);
        }
    }
}