<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/27
 * Time: 14:02
 */

namespace Mobile\Controller;

use Common\Controller\AppframeController;

class PublicController extends AppframeController
{
    //登录
    public function login(){
        if(IS_POST){
            $mobile = I('post.mobile');
            $password = I('post.password');
            $aurora = I('post.aurora');
            if(empty($mobile)){
                $this->ajaxReturn(['status'=>0,'info'=>'请输入手机号']);
            }
            if(empty($password)){
                $this->ajaxReturn(['status'=>0,'info'=>'请输入密码']);
            }
            $where['mobile'] = array('eq',$mobile);
            $where['user_type'] = array('in','0,2,3');
            $user = M('Users')->where($where)->find();
            if($user){
                if(sp_password($password) == $user['user_pass']){
                    if($user['user_status'] != 1){
                        $this->ajaxReturn(['status'=>0,'info'=>'当前账号已被禁用']);
                    }
                    if($user['user_type'] == 0 || empty($user['school_id'])){
                        $this->ajaxReturn(['status'=>-3,'info'=>'用户信息还未完善','id'=>$user['id']]);
                    }
                    $label_count = M('UsersLabel')->where(array('user_id'=>$user['id']))->count();
                    if($label_count < 1){
                        $this->ajaxReturn(['status'=>-4,'info'=>'用户标签信息为空','id'=>$user['id']]);
                    }
                    $dataInfo['id'] = $user['id'];
                    $dataInfo['uu_id'] = md5(time().$user['id'].rand(10000,99999));
                    if(!empty($aurora)){
                        $dataInfo['aurora'] = $aurora;
                    }
                    M('Users')->save($dataInfo);
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
//            if(!check_send_pass_code($mobile,trim($yzm))){
//                $this->ajaxReturn(['status'=>0,'info'=>'验证码输入有误']);
//            }
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
//            if(!check_send_code($mobile,trim($yzm))){
//                $this->ajaxReturn(['status'=>0,'info'=>'验证码输入有误']);
//            }
            $exist = M('Users')->where(array('mobile'=>$mobile))->find();
            if($exist){
                $this->ajaxReturn(['status'=>0,'info'=>'该账户已存在']);
            }
            $dataInfo = [
                'user_login' => $mobile,
                'user_pass' => sp_password($password),
                'create_time' => date('Y-m-d H:i:s'),
                'user_status' => 1,
                'user_type' => 0,//先默认0
                'mobile' => $mobile
            ];
            $result_id = M('Users')->add($dataInfo);
            if($result_id){
                M('Users')->save(array('id'=>$result_id,'user_name'=>'用户'.$result_id));
                $this->ajaxReturn(['status'=>1,'info'=>'注册成功','id'=>$result_id]);
            }else{
                $this->ajaxReturn(['status'=>-1,'info'=>'网络错误,请稍后再试']);
            }
        }
    }

    //设置用户信息
    public function user_setting(){
        if(IS_POST){
            $user_id = intval(I('post.uid'));
            $type = intval(I('post.type'));
            if(!in_array($type,array(2,3))){
                $type = 2;
            }
            $school_id = intval(I('post.school_id'));
            $school = M('School')->where(array('id'=>$school_id))->find();
            if(!$school){
                $this->ajaxReturn(['status'=>0,'info'=>'选择失败,不存在该学校']);
            }
            if($school['type'] == 1) {
                $specialty_id = intval(I('post.specialty_id'));
                $specialty = M('SchoolProfessional')->where(array('id' => $specialty_id, 'school_id' => $school_id))->find();
                if (!$specialty) {
                    $this->ajaxReturn(['status' => 0, 'info' => '选择失败,不存在该专业']);
                }
                $dataInfo['specialty_id'] = $specialty_id;
            }
            $dataInfo['id'] = $user_id;
            $dataInfo['user_type'] = $type;
            $dataInfo['school_id'] = $school_id;
            if($type == 3){//家长类型用户直接认证通过
                $dataInfo['verify'] = 1;
            }
            if(M('Users')->save($dataInfo) !== false){
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
            $where['school_name'] = array('like',"%$keyword%");
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
            $user_info = $this->user_info($user_id);
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在']);
            }
            M('UsersLabel')->where(array('user_id'=>$user_id))->delete();
            if(empty($label)){
                $this->ajaxReturn(['status'=>0,'info'=>'请至少选择一条您感兴趣的标签']);
            }
            $labelArray = explode(',',$label);
            if(count($labelArray) > 5){
                $this->ajaxReturn(['status'=>0,'info'=>'标签选择上限为5条']);
            }
            $where['id'] = array('in',$labelArray);
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
            $this->ajaxReturn(['status'=>1,'info'=>'设置成功','user'=>$user_info]);
        }
    }

    //处理上传图片
    public function upload_img(){
        if(IS_POST) {
            if($_FILES['file']['name'] !='') {
                $config = array(
                    'rootPath' => './' . C("UPLOADPATH"),
                    'savePath' => './mobile/' . date('Ymd') . '/',
                    'maxSize' => 10485760,//10M
                    'saveName' => array('uniqid', ''),
                    'exts' => array('jpg', 'jpeg', 'png', 'gif'),
                    'autoSub' => false,
                );
                $upload = new \Think\Upload($config);
                $info = $upload->upload($_FILES);
                if ($info) {
                    $resultArray = array();
                    foreach ($info as $k => $v) {
                        $filename = 'mobile/' . date('Ymd') . '/' . $v['savename'];
                        $path_info = './data/upload/'.$filename;
                        $image_size = getimagesize($path_info);//获取图片信息
                        $res = $this->makeImgthumb($path_info,$image_size[0],$image_size[1]);//裁剪图片
                        $res_arr = explode('/',$res);
                        $new_filename = $res_arr[3].'/'.$res_arr[4].'/'.$res_arr[5];
                        $resultArray[] = [
                            'url' => $new_filename,
                            'url_true' => sp_get_image_preview_url($new_filename)
                        ];
                    }
                    $this->ajaxReturn(['status' => 1, 'result' => $resultArray]);
                } else {//上传失败，返回错误
                    $this->ajaxReturn(['status' => 0, 'info' => $upload->getError()]);
                }
            }else{
                $this->ajaxReturn(['status'=>0,'info'=>'请选择图片']);
            }
        }
    }

    /**
     * 裁剪原图
     * @params $img 原图
     * @params $width 宽度
     * @params $height 高度
     */
    function makeImgthumb($img,$width,$height){
        //构造缩略图路径
        $img_arr = explode('/',$img);
        $thumb_path = $img_arr[0].'/'.$img_arr[1].'/'.$img_arr[2].'/'.$img_arr[3].'/'.$img_arr[4].'/thumb_'.$img_arr[5];
        //实例化图片处理类
        $image = new \Think\Image();
        // 在图片右下角添加水印文字 ThinkPHP 并保存为new.jpg
        $image->open($img)->thumb($width,$height,3)->save($thumb_path);
        unlink($img);
        return $thumb_path;
    }

    //根据用户id或手机号获取用户信息
    function user_info($key){
        if(preg_match('/^1[3456789][0-9]{9}$/',$key) < 1){
            $where['id'] = array('eq',$key);
        }else{
            $where['mobile'] = array('eq',$key);
        }
        $where['user_type'] = array('in','2,3');
        $user_info = M('Users')->where($where)->field('id,user_name,avatar,signature,create_time,score,user_type,mobile,school_id,specialty_id,verify,continuous_day,aurora,push,uu_id')->find();
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

    //注册发送验证码
    function send_sms_register(){
        header('content-type:text/html;charset=utf-8');
        $mobile = I('post.mobile');
        if(preg_match('/^1[3456789][0-9]{9}$/',$mobile) < 1){
            $this->ajaxReturn(['status'=>0,'info'=>'请输入正确的手机号！']);
        }
        $employee = M('Users')->where(['mobile'=>$mobile])->find();
        if($employee){
            $this->ajaxReturn(['status'=>0,'info'=>'该手机号已存在']);
        }
        $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
        $code = rand(100000,999999);  // 随机验证码
        $smsConf = array(
            'key'   => '0051ec9c4dfa573886c7415d74d2dc48', //您申请的APPKEY
            'mobile'    => $mobile, //接受短信的用户手机号码
            'tpl_id'    => '110935', //您申请的短信模板ID，根据实际情况修改
            'tpl_value' =>urlencode('#code#='.$code) //您设置的模板变量，根据实际情况修改
        );
        $content = $this->http_data_send($sendUrl,$smsConf); //请求发送短信
        if($content){
            S('sms_user_register'.$mobile,$code,600);
            $this->ajaxReturn(['status'=>1,'info'=>'发送成功']);
        }else{
            $this->ajaxReturn(['status'=>0,'info'=>"发送失败,请稍后再试"]);
        }
    }

    //忘记密码发送验证码
    function send_sms_forget(){
        header('content-type:text/html;charset=utf-8');
        $mobile = I('post.mobile');
        if(preg_match('/^1[3456789][0-9]{9}$/',$mobile) < 1){
            $this->ajaxReturn(['status'=>0,'info'=>'请输入正确的手机号！']);
        }
        $employee = M('Users')->where(['mobile'=>$mobile,'user_type'=>array('in','2,3')])->find();
        if(!$employee){
            $this->ajaxReturn(['status'=>0,'info'=>'该手机号不存在']);
        }
        $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
        $code = rand(100000,999999);  // 随机验证码
        $smsConf = array(
            'key'   => '0051ec9c4dfa573886c7415d74d2dc48', //您申请的APPKEY
            'mobile'    => $mobile, //接受短信的用户手机号码
            'tpl_id'    => '110935', //您申请的短信模板ID，根据实际情况修改
            'tpl_value' =>urlencode('#code#='.$code) //您设置的模板变量，根据实际情况修改
        );
        $content = $this->http_data_send($sendUrl,$smsConf); //请求发送短信
        if($content){
            S('sms_find_password'.$mobile,$code,600);
            $this->ajaxReturn(['status'=>1,'info'=>'发送成功']);
        }else{
            $this->ajaxReturn(['status'=>0,'info'=>"发送失败,请稍后再试"]);
        }
    }

    function http_data_send($url,$data=[]){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        if(!empty($data)){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output=curl_exec($ch);

        curl_close($ch);
        file_put_contents('send_code.txt',var_export($output,true));
        if($output) {
            $result = json_decode($output, true);
            $error_code = $result['error_code'];
            if ($error_code == 0) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}