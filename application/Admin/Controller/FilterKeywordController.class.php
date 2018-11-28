<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\FilterKeywordModel;

class FilterKeywordController extends AdminbaseController{

    /**
     * @var FilterKeywordModel
     */
	protected $order;

	public function _initialize() {
		parent::_initialize();
        $this->order = D("Common/FilterKeyword");
	}

	// 学校列表
	public function index(){
		$where = [];
		/**搜索条件**/
		$keyword = I('request.keyword');
        if($keyword){
			$where['keyword'] = array('like',"%$keyword%");
		}

		$count= $this->order->where($where)->count();
		$page = $this->page($count, 10);
        $list =$this->order->where($where)->order("create_time DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();

		$this->assign("page", $page->show('Admin'));
		$this->assign("list",$list);
		$this->display();
	}




	// 删除关键词
	public function delete(){

            $id = I('request.id',0,'intval');
            if (!empty($id)) {
                $result =  $this->order->where(array("id"=>$id))->delete();

                if ($result!==false) {
                    $this->success("删除成功！");
                } else {
                    $this->error('删除失败！');
                }
            } else {
                $this->error('数据传入失败！');
            }
	}

	// 添加关键词
	public function addPost(){
		if (IS_POST) {
            if ($this->order->create()!==false) {
                if ($this->order->add()!==false) {
                    $this->success("添加成功！");
                } else {
                    $this->error('添加失败！');
                }
            } else {
                $this->error($this->order->getError());
            }
		}
	}

}