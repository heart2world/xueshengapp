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
		$page = $this->page($count, 20);
        $list =$this->order
            ->where($where)
            ->order("status ASC,create_time DESC")
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
                $order = $this->order->where(array("id"=>$id ,'status'=>'0'))->find();
                if(!$order){
                    $this->error('操作失败！');
                }
                $result =  $this->order->where(array("id"=>$id ,'status'=>'0'))->setField('status',$status);
                if ($result!==false) {
                    if($status == 2){//关闭订单拒绝发货
                        //获取用户信息
                        $userInfo = M('Users')->where(array('id'=>$order['user_id']))->find();
                        if($userInfo){
                            //将用户积分加回去
                            M('Users')->save(array('id'=>$userInfo['id'],'score'=>$userInfo['score']+$order['price']));
                            $this->save_score($userInfo['id'],$order['price'],6);
                        }
                        //获取商品信息
                        $gift = M('Gift')->where(array('id'=>$order['gift_id']))->find();
                        if($gift){
                            M('Gift')->save(array('id'=>$gift['id'],'surplus'=>$gift['surplus']+$order['number']));
                        }
                    }
                    $this->success("操作成功！");
                } else {
                    $this->error('操作失败！');
                }
            } else {
                $this->error('数据传入失败！');
            }
		}
	}

    //保存积分记录
    function save_score($user_id,$score,$type){
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
}