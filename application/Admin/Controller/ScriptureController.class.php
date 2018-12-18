<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\ScriptureModel;
use Think\Upload;

class ScriptureController extends AdminbaseController{

    /**
     * @var ScriptureModel
     */
	protected $scripture;

	public function _initialize() {
		parent::_initialize();
        $this->scripture = D("Common/Scripture");
	}

	// 笔记列表
	public function index(){
		$where = [];
		/**搜索条件**/
		$keyword = I('request.keyword');

        $status = I('request.status');
        if ($status!=""){
            $where['status'] = $status;
        }

        if($keyword){
			$where['title'] = array('like',"%$keyword%");
		}

        $time = I('request.time');
        if ($time){
            $where['create_time'] = ['EGT', strtotime($time)];
        }

        $number_type = I('num_type',0,'intval');//量
        if($number_type && in_array($number_type,array(1,2))){
            $num_last = I('request.num_last');
            $num_next = I('request.num_next');
            if ($number_type == 1) {
                if ($num_last >= 0 && $num_next >= 0 && $num_last != null && $num_next != null) {
                    $where['views'] = array('between', [$num_last, $num_next]);
                } elseif ($num_last >= 0 && $num_last != null) {
                    $where['views'] = array('egt', $num_last);
                } elseif ($num_next >= 0 && $num_next != null) {
                    $where['views'] = array('elt', $num_next);
                }
            }else {
                if ($num_last >= 0 && $num_next >= 0 && $num_last != null && $num_next != null) {
                    $where['collect'] = array('between', [$num_last, $num_next]);
                } elseif ($num_last >= 0 && $num_last != null) {
                    $where['collect'] = array('egt', $num_last);
                } elseif ($num_next >= 0 && $num_next != null) {
                    $where['collect'] = array('elt', $num_next);
                }
            }
        }

		$count= $this->scripture->where($where)->count();
		$page = $this->page($count, 20);
        $list =$this->scripture
            ->where($where)
            ->order("create_time DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();

		$this->assign("page", $page->show('Admin'));
		$this->assign("list",$list);
		$this->display();
	}

	//添加
	public function add(){
		$this->display();
	}

	// 添加提交
	public function add_post(){
        if (IS_POST) {
            $m = $this->scripture;
            if($m->create()!==false){
                if($m->add()!==false){
                    $this->success('保存成功!',U("scripture/index"));
                }else{
                    $this->error("保存失败!");
                }
            } else {
                $this->error($m->getError());
            }
        }
	}


    //修改链接
    public function link(){
	    $this->assign("link",clinks());
        $this->display();
    }

    // 修改链接提交
    public function link_post(){
        if (IS_POST) {
            clinks(true);
            $this->success("保存成功");
        }
    }



	//  编辑
	public function edit(){
	    $id = I('get.id',0,'intval');
	    $data =  $this->scripture ->find($id);
		$this->assign("data",$data);
		$this->display();
	}

	// 编辑提交
	public function edit_post(){
		if (IS_POST) {
		    $m = $this->scripture;
		    if($m->create()!==false){
                if($m->save()!==false){

                    $this->success('保存成功!',U("scripture/index"));
                }else{
                    $this->error("保存失败!");
                }
            } else {
                $this->error($m->getError());
            }
		}

	}

    // 初始化图片
	function initImage(){
        $img = I("img/a");
        $sort = I("sort/a");
        if ($img){
            $intro =[];
            foreach ($img as $k=>$item){
                $intro[$k]=[
                    'img'=>$item,
                    'sort'=>$sort[$k],
                ];
            }
            $_POST['product_intro']=json_encode($intro);
        }
    }


    // 上传文件
    public function upload(){
	    $path = '/data/upload';
        $upload = new Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     ".$path"; // 设置附件上传根目录
        $upload->savePath  =     '/admin'; // 设置附件上传（子）目录
        $upload->subName   =      "/". date("Ymd");


        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            $path.=$info['file']['savepath'].$info['file']['savename'];
            $this->success('上传成功！',null,['img_path'=>$path]);
        }
    }


    // 删除
    public function delete(){
        $id = I('get.id',0,'intval');

        if (!empty($id)) {
            $result =$this->scripture->where(array("id"=>$id))->delete();
            if ($result!==false) {
                $this->success("删除成功！" );
            } else {
                $this->error('删除失败！');
            }
        } else {
            $this->error('数据传入失败！');
        }
    }

	// 停用
    public function down(){
        $id = I('get.id',0,'intval');

    	if (!empty($id)) {
    		$result =$this->scripture->where(array("id"=>$id))->setField('status','1');
    		if ($result!==false) {
    			$this->success("禁用成功！", U("scripture/index"));
    		} else {
    			$this->error('禁用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

    // 启用
    public function up(){
    	$id = I('get.id',0,'intval');
    	if (!empty($id)) {
    		$result = $this->scripture->where(array("id"=>$id ))->setField('status','0');
    		if ($result!==false) {
    			$this->success("启用成功！", U("scripture/index"));
    		} else {
    			$this->error('启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

}