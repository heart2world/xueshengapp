<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\AuthenticationModel;

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

		$page = $this->page($count, 10);
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
    		$result =  $this->auth->where(array("id"=>$id ,'status'=>'0'))->setField('status',$status);
    		if ($result!==false) {
    			$this->success("操作成功！",U('Authentication/index'));
    		} else {
    			$this->error('操作失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
}