<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\UsersModel;

class UserController extends AdminbaseController{

    /**
     * @var UsersModel
     */
	protected $users_model;
    protected $role_model;

	public function _initialize() {
		parent::_initialize();
		$this->users_model = D("Common/Users");
		$this->role_model = D("Common/Role");
	}

	// 管理员列表
	public function index(){
		$where = array("user_type"=>1);
		/**搜索条件**/
        $keyword = I('request.keyword');
		if($keyword){
			$where['u.user_name|u.mobile'] = array('like',"%$keyword%");
		}
		$status = I('request.status');
		if($status != ''){
		    $where['u.user_status'] = array('eq',$status);
        }
        $role_id = I('request.role_id',0,'intval');
		if($role_id){
		    if($role_id == 1){
		        $map['u.id'] = array('eq',1);
		        $map['r.role_id'] = array('eq', $role_id);
		        $map['_logic'] = 'or';
                $where['_complex'] = $map;
            }else {
                $where['r.role_id'] = array('eq', $role_id);
            }
        }
        $time = I('request.time');
		$end_time = I('request.end_time');
		if($time && $end_time){
            $where['u.create_time'] = array('between',[$time,$end_time]);
        }elseif ($time){
            $where['u.create_time'] = array('egt',$time);
        }elseif ($end_time){
            $where['u.create_time'] = array('elt',$end_time);
        }
		$count=$this->users_model->alias('u')->join('h2w_role_user as r on r.user_id=u.id')->where($where)->count();
		$page = $this->page($count, 20);
        $users = $this->users_model->alias('u')
            ->join('left join h2w_role_user as r on r.user_id=u.id')
            ->where($where)
            ->field('u.*,r.role_id')
            ->order("u.create_time DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();

        $roles = $this->role_model->select();
        foreach ($users as $k=>$v){
            foreach ($roles as $x=>$y){
                if($v['role_id'] == $y['id']){
                    $users[$k]['role_name'] = $y['name'];
                }
            }
            if($v['id'] == 1){
                $users[$k]['role_name'] = '超级管理员';
            }
        }

		$this->assign("page", $page->show('Admin'));
		$this->assign("roles",$roles);
		$this->assign("users",$users);
		$this->display();
	}

	// 管理员添加
	public function add(){
		$roles=$this->role_model->where(array('status' => 1))->order("id DESC")->select();
		$this->assign("roles",$roles);
		$this->display();
	}

	// 管理员添加提交
	public function add_post(){
		if(IS_POST){
			if(!empty($_POST['role_id']) && is_array($_POST['role_id'])){
				$role_ids=$_POST['role_id'];
				unset($_POST['role_id']);
				$_POST['user_login'] = $_POST['mobile'];
				$_POST['user_pass'] = '123456';
				if ($this->users_model->create()!==false) {
				    $exist = $this->users_model->where(array("user_type"=>1,'mobile'=>$_POST['mobile']))->find();
				    if($exist){
                        $this->error("手机号已经存在！");
                    }
					$result=$this->users_model->add();
					if ($result!==false) {
						$role_user_model=M("RoleUser");
						foreach ($role_ids as $role_id){
							if(sp_get_current_admin_id() != 1 && $role_id == 1){
								$this->error("为了网站的安全，非网站创建者不可创建超级管理员！");
							}
							$role_user_model->add(array("role_id"=>$role_id,"user_id"=>$result));
						}
						$this->success("添加成功！", U("user/index"));
					} else {
						$this->error("添加失败！");
					}
				} else {
					$this->error($this->users_model->getError());
				}
			}else{
				$this->error("请为此用户指定角色！");
			}

		}
	}

	// 管理员编辑
	public function edit(){
	    $id = I('get.id',0,'intval');
		$roles=$this->role_model->where(array('status' => 1))->order("id DESC")->select();
		$this->assign("roles",$roles);
		$role_user_model=M("RoleUser");
		$role_ids=$role_user_model->where(array("user_id"=>$id))->getField("role_id",true);
		$this->assign("role_ids",$role_ids);

		$user=$this->users_model->where(array("id"=>$id))->find();
		$this->assign($user);
		$this->display();
	}

	// 管理员编辑提交
	public function edit_post(){
		if (IS_POST) {
			if(!empty($_POST['role_id']) && is_array($_POST['role_id'])){
				if(empty($_POST['user_pass'])){
					unset($_POST['user_pass']);
				}
				$role_ids = I('post.role_id/a');
				unset($_POST['role_id']);
                $uid = I('post.id',0,'intval');
				if(!empty($_POST['mobile'])){
				    $userInfo = $this->users_model->find($uid);
				    if($userInfo && $_POST['mobile'] != $userInfo['mobile']){
				        $_POST['user_pass'] = '123456';
                    }
                }
                $_POST['user_login'] = $_POST['mobile'];
				if ($this->users_model->create()!==false) {
                    $exist = $this->users_model->where(array("user_type"=>1,'mobile'=>$_POST['mobile'],'id'=>array('neq',$_POST['id'])))->find();
                    if($exist){
                        $this->error("手机号已经存在！");
                    }
					$result=$this->users_model->save();
					if ($result!==false) {
						$role_user_model=M("RoleUser");
						$role_user_model->where(array("user_id"=>$uid))->delete();
						foreach ($role_ids as $role_id){
							if(sp_get_current_admin_id() != 1 && $role_id == 1){
								$this->error("为了网站的安全，非网站创建者不可创建超级管理员！");
							}
							$role_user_model->add(array("role_id"=>$role_id,"user_id"=>$uid));
						}
						$this->success("保存成功！", U("user/index"));
					} else {
						$this->error("保存失败！");
					}
				} else {
					$this->error($this->users_model->getError());
				}
			}else{
				$this->error("请为此用户指定角色！");
			}

		}
	}

	// 管理员删除
	public function delete(){
	    $id = I('get.id',0,'intval');
		if($id==1){
			$this->error("最高管理员不能删除！");
		}

		if ($this->users_model->delete($id)!==false) {
			M("RoleUser")->where(array("user_id"=>$id))->delete();
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}

	// 管理员个人信息修改
	public function userinfo(){
		$id=sp_get_current_admin_id();
		$user=$this->users_model->where(array("id"=>$id))->find();
		$this->assign($user);
		$this->display();
	}

	// 管理员个人信息修改提交
	public function userinfo_post(){
		if (IS_POST) {
			$_POST['id']=sp_get_current_admin_id();
			$create_result=$this->users_model
			->field("id,user_nicename,sex,birthday,user_url,signature")
			->create();
			if ($create_result!==false) {
				if ($this->users_model->save()!==false) {
					$this->success("保存成功！");
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->users_model->getError());
			}
		}
	}

	// 停用管理员
    public function ban(){
        $id = I('get.id',0,'intval');
    	if (!empty($id)) {
    		$result = $this->users_model->where(array("id"=>$id,"user_type"=>1))->setField('user_status','0');
    		if ($result!==false) {
    			$this->success("管理员停用成功！", U("user/index"));
    		} else {
    			$this->error('管理员停用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

    // 启用管理员
    public function cancelban(){
    	$id = I('get.id',0,'intval');
    	if (!empty($id)) {
    		$result = $this->users_model->where(array("id"=>$id,"user_type"=>1))->setField('user_status','1');
    		if ($result!==false) {
    			$this->success("管理员启用成功！", U("user/index"));
    		} else {
    			$this->error('管理员启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

    //重置密码
    public function reset_password(){
        $id = I('get.id',0,'intval');
        if (!empty($id)) {
            $result = $this->users_model->where(array("id"=>$id,"user_type"=>1))->setField('user_pass',sp_password('123456'));
            if ($result!==false) {
                $this->success("重置成功！");
            } else {
                $this->error('重置失败！');
            }
        } else {
            $this->error('数据传入失败！');
        }
    }

}