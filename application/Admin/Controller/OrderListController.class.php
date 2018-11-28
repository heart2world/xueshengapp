<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\OrderListModel;
use Think\Upload;

class OrderListController extends AdminbaseController{

    /**
     * @var OrderListModel
     */
	protected $order;

	public function _initialize() {
		parent::_initialize();
        $this->order = D("Common/OrderList");
	}

	// 订单列表
	public function index(){
		$where = [];
		/**搜索条件**/
		$keyword = I('request.keyword');

        $status = I('request.status');
        if ($status!=""){
            $where['status'] = $status;
        }
        $time = I('request.time');
        if ($time){
            $where['create_time'] = ['EGT', strtotime($time)];
        }

        if($keyword){
			$where['gift_name|user_name|mobile'] = array('like',"%$keyword%");
		}

		$count= $this->order->where($where)->count();
		$page = $this->page($count, 10);
        $list =$this->order
            ->where($where)
            ->order("create_time DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();

		$this->assign("page", $page->show('Admin'));
		$this->assign("list",$list);
		$this->display();
	}




	// 发货提交
	public function invoice(){
		if (IS_POST) {
            $id = I('request.id',0,'intval');
            $status = I('request.status',0,'intval');
            if (!in_array($status,[1,2])){
                $this->error("操作失败!请刷新后重试");
            }
            if (!empty($id)) {
                $result =  $this->order->where(array("id"=>$id ,'status'=>'0'))->setField('status',$status);
                if ($result!==false) {
                    $this->success("操作成功！");
                } else {
                    $this->error('操作失败！');
                }
            } else {
                $this->error('数据传入失败！');
            }
		}
	}

}