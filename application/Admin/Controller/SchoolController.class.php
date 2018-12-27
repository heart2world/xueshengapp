<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\SchoolModel;
use Common\Model\SchoolProfessionalModel;
use Common\Model\UsersModel;

class SchoolController extends AdminbaseController{

    /**
     * @var SchoolModel
     */
	protected $school;

	public function _initialize() {
		parent::_initialize();
        $this->school = D("Common/School");
	}

	// 学校列表
	public function index(){
		$where = [];
		/**搜索条件**/
		$keyword = I('request.keyword');

        $type = I('request.type',1,'intval');
        if($type != 1){
            $type = 2;
        }
        $where['type'] = $type;


        $status = I('request.status');
        if ($status!=""){
            $where['status'] = $status;
        }

        if($keyword){
			$where['school_name|address|professional_type'] = array('like',"%$keyword%");
		}

		$count= $this->school->where($where)->count();
		$page = $this->page($count, 20);
        $list = $this->school
            ->where($where)
            ->order("create_time DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();
        $pro = new SchoolProfessionalModel();
        $user = new UsersModel();

        foreach ($list as $k=>$item)
        {
            //统计专业数
            $item['pros']= $pro->where(['school_id'=>$item['id']])->count();
            //统计注册数
            $item['regs']= $user->where(['school_id'=>$item['id']])->count() ;

            $list[$k]=$item;
        }

		$this->assign("page", $page->show('Admin'));
		$this->assign("list",$list);
		$this->assign("type",$type);
		$this->display();
	}

	//添加学校
	public function add(){
	    $type = I('request.type',1,'intval');
	    if($type != 1){
	        $type = 2;
        }
        $this->assign("type",$type);
		$this->display();
	}

	// 添加提交
	public function add_post(){
        if (IS_POST) {
            $m = $this->school;
            $type = intval(I('post.type'));
            $school_name = I('post.school_name');
            if(empty($school_name)){
                $this->error("学校名称不能为空!");
            }

            $first = strtoupper(I('post.first'));
            $pin_yin = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            if(!empty($first)){
                if(!in_array($first,$pin_yin)){
                    $this->error("学校首字母不符合规范");
                }
            }else{
                import ( "ORG.Util.Pinyin" );
                $pinyin = new \PinYin();
                $firstInfo = $pinyin->getFirstPY($school_name);
                $first = substr($firstInfo, 0, 1 );
                if(!in_array($first,$pin_yin)){
                    $this->error("学校名称只能是字母或中文开头");
                }
            }
            $_POST['first'] = $first;
            if($m->create()!==false){
                if($m->add()!==false){
                    $this->success('保存成功!',U('School/index',array('type'=>$type)));
                }else{
                    $this->error("保存失败!");
                }
            } else {
                $this->error($m->getError());
            }
        }
	}

	// 添加专业提交
	public function add_pro_post(){
        if (IS_POST) {
            $m = D("Common/SchoolProfessional");
            if ($m->where(['school_id'=>I("school_id"),'pro_name'=>I("pro_name")])->count()){
                $this->error("该专业已存在");
            }
            if($m->create()!==false){
                if($m->add()!==false){
                    $this->success('保存成功!');
                }else{
                    $this->error("保存失败!");
                }
            } else {
                $this->error($m->getError());
            }
        }
	}

    // 编辑专业提交
    public function edit_pro_post(){
        if (IS_POST) {
            $m = D("Common/SchoolProfessional");
            if($m->create()!==false){
                if($m->save()!==false){
                    $this->success('保存成功!');
                }else{
                    $this->error("保存失败!");
                }
            } else {
                $this->error($m->getError());
            }
        }
    }


	// 学校编辑
	public function edit(){
	    $id = I('get.id',0,'intval');
	    $data = $this->school->find($id);
        $user = new UsersModel();
        //统计注册数
        $data['count']= $user->where(['school_id'=>$id])->count() ;
        $pro_list = D("Common/SchoolProfessional")->where(['school_id'=>$id])->select();
        foreach ($pro_list as $k=>$v){
            $count = M('Users')->where(array('specialty_id'=>$v['id']))->count();
            $pro_list[$k]['count'] = $count;
        }
		$this->assign("data",$data);
		$this->assign("pro_list",$pro_list);
		$this->display();
	}

	// 编辑学校提交
	public function edit_post(){
		if (IS_POST) {
		    $m = $this->school;
		    $school = $m->find(I('post.id'));
            $school_name = I('post.school_name');
            if(empty($school_name)){
                $this->error("学校名称不能为空!");
            }
            $first = strtoupper(I('post.first'));
            $pin_yin = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            if(!empty($first)){
                if(!in_array($first,$pin_yin)){
                    $this->error("学校首字母不符合规范");
                }
            }else{
                import ( "ORG.Util.Pinyin" );
                $pinyin = new \PinYin();
                $firstInfo = $pinyin->getFirstPY($school_name);
                $first = substr($firstInfo, 0, 1 );
                if(!in_array($first,$pin_yin)){
                    $this->error("学校名称只能是字母或中文开头");
                }
            }
            $_POST['first'] = $first;
		    if($m->create()!==false){
                if($m->save()!==false){
                    $this->success('保存成功!',U('School/index',array('type'=>$school['type'])));
                }else{
                    $this->error("保存失败!");
                }

            } else {
                $this->error($m->getError());
            }
		}
	}


	// 停用学校
    public function ban(){
        $id = I('get.id',0,'intval');

    	if (!empty($id)) {
    	    $school = $this->school->find($id);
    		$result = $this->school->where(array("id"=>$id))->setField('status','1');
    		if ($result!==false) {
    			$this->success("停用成功！", U("school/index",array('type'=>$school['type'])));
    		} else {
    			$this->error('停用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

    // 启用学校
    public function cancelban(){
    	$id = I('get.id',0,'intval');
    	if (!empty($id)) {
            $school = $this->school->find($id);
    		$result = $this->school->where(array("id"=>$id ))->setField('status','0');
    		if ($result!==false) {
    			$this->success("启用成功！", U("school/index",array('type'=>$school['type'])));
    		} else {
    			$this->error('启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
	// 停用专业
    public function ban_pro(){
        $id = I('get.id',0,'intval');

    	if (!empty($id)) {
    		$result = M('SchoolProfessional')->where(array("id"=>$id))->setField('status','1');
    		if ($result!==false) {
    			$this->success("停用成功！");
    		} else {
    			$this->error('停用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

    // 启用专业
    public function cancelban_pro(){
    	$id = I('get.id',0,'intval');
    	if (!empty($id)) {
    		$result = M('SchoolProfessional')->where(array("id"=>$id ))->setField('status','0');
    		if ($result!==false) {
    			$this->success("启用成功！");
    		} else {
    			$this->error('启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

}