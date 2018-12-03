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

	// 关键词列表
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

        $filter = [1=>"用户昵称",2=>"评论及回复",3=>"讨论标题及正文"];
        foreach ($list as $k=>$v){
            $name = '';
            $filter_ids = explode(',',$v['effect_area']);
            foreach ($filter_ids as $n){
                ($name == '') ? $name.=$filter[$n] : $name.='、'.$filter[$n];
            }
            $list[$k]['effect_area'] = $name;
        }

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