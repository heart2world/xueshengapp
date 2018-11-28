<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\GiftModel;
use Think\Upload;

class GiftController extends AdminbaseController{

    /**
     * @var GiftModel
     */
	protected $gift;

	public function _initialize() {
		parent::_initialize();
        $this->gift = D("Common/Gift");
	}

	// 商品列表
	public function index(){
		$where = [];
		/**搜索条件**/
		$keyword = I('request.keyword');

        $status = I('request.status');
        if ($status!=""){
            $where['status'] = $status;
        }

        if($keyword){
			$where['gift_name'] = array('like',"%$keyword%");
		}

		$count= $this->gift->where($where)->count();
		$page = $this->page($count, 10);
        $list =$this->gift
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
            $m = $this->gift;
            if($m->create()!==false){
                if($m->add()!==false){
                    $this->success('保存成功!',U('Gift/index'));
                }else{
                    $this->error("保存失败!");
                }
            } else {
                $this->error($m->getError());
            }
        }
	}


	//  编辑
	public function edit(){
	    $id = I('get.id',0,'intval');
	    $data =  $this->gift ->find($id);

		$this->assign("data",$data);
		$this->display();
	}

	// 编辑提交
	public function edit_post(){
		if (IS_POST) {
		    $m = $this->gift;
		    $this->initImage();

		    if($m->create()!==false){
                if($m->save()!==false){
                    $this->success('保存成功!',U('Gift/index'));
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
        $upload->subName  =      "/". date("Ymd");


        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            $path.=$info['file']['savepath'].$info['file']['savename'];
            $this->success('上传成功！',null,['img_path'=>$path]);
        }
    }


	// 停用
    public function down(){
        $id = I('get.id',0,'intval');

    	if (!empty($id)) {
    		$result =$this->gift->where(array("id"=>$id))->setField('status','1');
    		if ($result!==false) {
    			$this->success("下架成功！", U("gift/index"));
    		} else {
    			$this->error('下架失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

    // 启用
    public function up(){
    	$id = I('get.id',0,'intval');
    	if (!empty($id)) {
    		$result = $this->gift->where(array("id"=>$id ))->setField('status','0');
    		if ($result!==false) {
    			$this->success("上架成功！", U("gift/index"));
    		} else {
    			$this->error('上架失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

}