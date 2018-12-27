<?php
namespace User\Controller;

use Common\Controller\AdminbaseController;

class IndexadminController extends AdminbaseController {
    
    // 后台本站用户列表
    public function index(){
        $where = array();
        $where['u.user_type'] = array('in','2,3');
        /*搜索条件*/
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword = $request['keyword'];
            $where['u.mobile|u.user_name|s.school_name|p.pro_name'] = array('like',"%$keyword%");
        }
        if(!empty($request['user_type'])){
            $user_type = $request['user_type'];
            if(in_array($user_type,array('2','3'))){
                $where['u.user_type'] = array('eq',$user_type);
            }
        }
        if(!empty($request['school_type'])){
            $school_type = $request['school_type'];
            $where['s.type'] = array('eq',$school_type);
        }
        $user_status = $request['user_status'];
        if(in_array($user_status,array('1','2'))){
            if($user_status == 2){
                $where['u.user_status'] = array('eq',0);
            }else {
                $where['u.user_status'] = array('eq', $user_status);
            }
        }
        $num_last = $request['num_last'];
        $num_next = $request['num_next'];
        if($num_last >= 0 && $num_next >= 0 && $num_last != null && $num_next != null){
            $where['u.online_time'] = array('between',[$num_last,$num_next]);
        }elseif ($num_last >= 0 && $num_last != null){
            $where['u.online_time'] = array('egt',$num_last);
        }elseif ($num_next >= 0 && $num_next != null){
            $where['u.online_time'] = array('elt',$num_next);
        }
        $start_time = $request['start_time'];
        $end_time = $request['end_time'];
        if(!empty($start_time) && !empty($end_time)){
            $where['u.create_time'] = array('between',[$start_time,$end_time]);
        }elseif (!empty($start_time)){
            $where['u.create_time'] = array('egt',$start_time);
        }elseif (!empty($end_time)){
            $where['u.create_time'] = array('elt',$end_time);
        }

    	$users_model = M("Users");
    	$count = $users_model->alias('u')
            ->join('h2w_school as s on s.id=u.school_id')
            ->join('left join h2w_school_professional as p on p.id=u.specialty_id')
            ->where($where)->count();
    	$page = $this->page($count, 20);

    	$list = $users_model->alias('u')
            ->join('h2w_school as s on s.id=u.school_id')
            ->join('left join h2w_school_professional as p on p.id=u.specialty_id')
            ->where($where)
            ->field('u.*,s.type,s.school_name,p.pro_name')
            ->order("u.create_time DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
    	$this->assign('list', $list);
    	$this->assign("page", $page->show('Admin'));
    	$this->display(":index");
    }
    
    // 后台本站用户禁用
    public function ban(){
    	$id= I('get.id',0,'intval');
    	if ($id) {
    	    $where['id'] = array('eq',$id);
    	    $where['user_type'] = array('in','2,3');
    		$result = M("Users")->where($where)->setField('user_status',0);
    		if ($result) {
    			$this->success("停用成功！", U("indexadmin/index"));
    		} else {
    			$this->error('操作失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    // 后台本站用户启用
    public function cancelban(){
    	$id= I('get.id',0,'intval');
    	if ($id) {
            $where['id'] = array('eq',$id);
            $where['user_type'] = array('in','2,3');
    		$result = M("Users")->where($where)->setField('user_status',1);
    		if ($result) {
    			$this->success("恢复正常成功！", U("indexadmin/index"));
    		} else {
    			$this->error('操作失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
}
