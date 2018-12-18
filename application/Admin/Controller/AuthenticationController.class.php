<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\AuthenticationModel;
use Mobile\Controller\BaseController;
use Think\JPushZDY;

class AuthenticationController extends AdminbaseController{

    /**
     * @var AuthenticationModel
     */
	protected $auth;

	public function _initialize() {
		parent::_initialize();
        $this->auth = D("Common/Authentication");
	}

	// 学校列表
	public function index(){
		$where = [];
		/**搜索条件**/
		$keyword = I('request.keyword');
        if($keyword){
            $where['s.school_name|u.user_name'] = array('like',"%$keyword%");
        }
        $status = I('request.status');
        if ($status!=""){
            $where['a.status'] = $status;
        }
        $time = I('request.time');
        if ($time){
            $where['a.create_time'] = ['EGT', strtotime($time)];
        }
        $type = I('request.type',0,'intval');
        if($type){
            $where['s.type'] = array('eq',$type);
        }

		$count= $this->auth->alias("a")
            ->join("h2w_users u on  u.id =a.user_id")
            ->join("h2w_school s on  s.id =a.school_id")
            ->where($where)
            ->count();

		$page = $this->page($count, 20);
        $list =$this->auth->alias("a")
            ->join("h2w_users u on  u.id =a.user_id")
            ->join("h2w_school s on  s.id =a.school_id")
            ->where($where)
            ->field("a.*,u.user_name,s.school_name,s.type")
            ->order("a.status asc,a.create_time DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();

		$this->assign("page", $page->show('Admin'));
		$this->assign("list",$list);
		$this->display();
	}

	// 认证详情
	public function edit(){
	    $id = I('get.id',0,'intval');
        $data = $this->auth->alias("a")
            ->join("h2w_users u on  u.id =a.user_id")
            ->join("h2w_school s on  s.id =a.school_id")
            ->where(['a.id'=>$id])
            ->field("a.*,u.user_name,s.school_name,s.type")
            ->find();
		$this->assign("data",$data);
		$this->display();
	}

	// 认证用户操作
    public function edit_post(){
        $id = I('request.id',0,'intval');
        $status = I('request.status',0,'intval');
        if (!in_array($status,[1,2])){
            $this->error("操作失败!请刷新后重试");
        }
    	if (!empty($id)) {
            $auth_info = $this->auth->where(array('id'=>$id,'status'=>0))->find();
            if(!$auth_info){
                $this->error('操作失败！');
            }
    		$result =  $this->auth->where(array("id"=>$id ,'status'=>'0'))->setField('status',$status);
    		if ($result!==false) {
                //获取用户信息
                $userInfo = M('Users')->where(array('id'=>$auth_info['user_id'],'verify'=>0))->find();
                if($userInfo) {
                    if ($status == 1) {//修改用户认证状态为通过
                        //发送消息
                        $this->save_message($userInfo['id'], get_current_admin_id(), 4, $auth_info['create_time'], $status);
                        //获取缓存数据
                        $set_score = S('score_setting');
                        if (empty($set_score) || $set_score == false) {
                            $set_score = score_setting();
                        }
                        $gain_score = 0;
                        $pass_authentication = $set_score['pass_authentication'];
                        if ($pass_authentication > 0) {
                            $gain_score = $pass_authentication;
                            //保存积分记录
                            $this->save_score($userInfo['id'], $gain_score, 4);
                        }
                        M('Users')->save(array('id' => $userInfo['id'], 'verify' => 1, 'score' => $userInfo['score'] + $gain_score));
                        //发送极光推送
                        if($userInfo['push'] == 1 && !empty($userInfo['aurora'])) {
                            $this->send_pub($userInfo['aurora'],'您于 '.date('Y-m-d H:i:s',$auth_info['create_time']).' 发起的学校认证已被通过。');
                        }
                    } else {
                        //发送消息
                        $this->save_message($userInfo['id'], get_current_admin_id(), 4, $auth_info['create_time'], $status);
                        //发送极光推送
                        if($userInfo['push'] == 1 && !empty($userInfo['aurora'])) {
                            $this->send_pub($userInfo['aurora'],'您于 '.date('Y-m-d H:i:s',$auth_info['create_time']).' 发起的学校认证已被拒绝。');
                        }
                    }
                }
    			$this->success("操作成功！",U('Authentication/index'));
    		} else {
    			$this->error('操作失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

    //保存消息
    public function save_message($user_id,$from_uid,$type,$time = '',$status = 1,$desc = null){
        $from_user = M('Users')->where(array('id'=>$from_uid))->field('id,user_name,avatar')->find();
        if($type == 1){
            $title = $from_user['user_name'].'点赞了您的评论';
        }elseif ($type == 2 || $type == 3){
            $title = $from_user['user_name'].'回复了您：';
        }else{
            if($status == 1){
                $title = '您于 '.date('Y-m-d H:i:s',$time).' 发起的学校认证已被通过。';
            }else{
                $title = '您于 '.date('Y-m-d H:i:s',$time).' 发起的学校认证已被拒绝。';
            }
        }
        if(empty($from_user['avatar'])){
            $from_user['avatar'] = 'mobile/avatar.jpg';
        }
        $dataInfo = [
            'user_id' => $user_id,
            'from_uid' => $from_uid,
            'from_avatar' => $from_user['avatar'],
            'title' => $title,
            'desc' => $desc,
            'type' => $type,
            'create_time' => time()
        ];
        M('UsersMessage')->add($dataInfo);
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

    //调用推送方法
    function send_pub($receive,$content){
        $push = new JPushZDY();
        $m_type = 'http';//推送附加字段的类型
        $m_txt = 'JPush';//推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
        $message = "";//存储推送状态
        $result = $push->push($receive,$content,$m_type,$m_txt);
        if($result){
            $res_arr = json_decode($result, true);
            if(isset($res_arr['error'])){//如果返回了error则证明失败
                $error_code = $res_arr['error']['code'];//错误码
                switch ($error_code) {
                    case 200:
                        $message = '发送成功！';
                        break;
                    case 1000:
                        $message = '失败(系统内部错误)';
                        break;
                    case 1001:
                        $message = '失败(只支持 HTTP Post 方法，不支持 Get 方法)';
                        break;
                    case 1002:
                        $message = '失败(缺少了必须的参数)';
                        break;
                    case 1003:
                        $message = '失败(参数值不合法)';
                        break;
                    case 1004:
                        $message = '失败(验证失败)';
                        break;
                    case 1005:
                        $message = '失败(消息体太大)';
                        break;
                    case 1008:
                        $message = '失败(appkey参数非法)';
                        break;
                    case 1020:
                        $message = '失败(只支持 HTTPS 请求)';
                        break;
                    case 1030:
                        $message = '失败(内部服务超时)';
                        break;
                    default:
                        $message = '失败(返回其他状态，目前不清楚额，请联系开发人员！)';
                        break;
                }
                return false;
            }else{
                $message = "发送成功！";
                return true;
            }
        }else{//接口调用失败或无响应
            $message = '接口调用失败或无响应';
            return false;
        }
    }
}