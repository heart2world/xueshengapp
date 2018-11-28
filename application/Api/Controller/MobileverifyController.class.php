<?php
/**
 * 手机验证码发送
 */
namespace Api\Controller;

use Think\Controller;

class MobileverifyController extends Controller{

    // 手机验证码发送
    public function send(){
        if(IS_POST){
            $mobile=I('post.mobile/s');
            $result= hook_one("send_mobile_verify_code",array('mobile'=>$mobile));
            /*
             *-1:发送次数过多，不能再发送
             *-2:短信服务商短信接口发送失败 
             */
            if($result['error']===0){
                $this->success('验证码已发送到您手机，请查收！');
            }else{
                $this->error($result['error_msg']);
            }
        }
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

