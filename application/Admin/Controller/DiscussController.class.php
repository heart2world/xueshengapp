<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class DiscussController extends AdminbaseController{
	
	protected $category_model,$label_model,$discuss_model,$comment_model,$reply_model;
	
	public function _initialize() {
		parent::_initialize();
		$this->category_model = D("Common/Category");
		$this->label_model = D("Common/Label");
		$this->discuss_model = D("Common/Discuss");
		$this->comment_model = D("Common/Comment");
        $this->reply_model = D("Common/Reply");
	}

    // 讨论列表
    public function index(){
	    $where = array();
	    /*搜索条件*/
	    $keyword = I('request.keyword');//文本搜索
	    if($keyword){
	        $where['d.name|u.user_name|s.school_name'] = array('like',"%$keyword%");
        }
        $number_type = I('number_type',0,'intval');//量
	    if($number_type && in_array($number_type,array(1,2,3,4))){
	        $num_last = I('request.num_last');
	        $num_next = I('request.num_next');
	        if($number_type == 1){
	            if($num_last >= 0 && $num_next >= 0 && $num_last != null && $num_next != null){
	                $where['d.click_num'] = array('between',[$num_last,$num_next]);
                }elseif ($num_last >= 0 && $num_last != null){
                    $where['d.click_num'] = array('egt',$num_last);
                }elseif ($num_next >= 0 && $num_next != null){
                    $where['d.click_num'] = array('elt',$num_next);
                }
            }elseif ($number_type == 2){
                if($num_last >= 0 && $num_next >= 0 && $num_last != null && $num_next != null){
                    $where['d.comment_num'] = array('between',[$num_last,$num_next]);
                }elseif ($num_last >= 0 && $num_last != null){
                    $where['d.comment_num'] = array('egt',$num_last);
                }elseif ($num_next >= 0 && $num_next != null){
                    $where['d.comment_num'] = array('elt',$num_next);
                }
            }elseif ($number_type == 3){
                if($num_last >= 0 && $num_next >= 0 && $num_last != null && $num_next != null){
                    $where['d.collect_num'] = array('between',[$num_last,$num_next]);
                }elseif ($num_last >= 0 && $num_last != null){
                    $where['d.collect_num'] = array('egt',$num_last);
                }elseif ($num_next >= 0 && $num_next != null){
                    $where['d.collect_num'] = array('elt',$num_next);
                }
            }else{
                if($num_last >= 0 && $num_next >= 0 && $num_last != null && $num_next != null){
                    $where['d.like_num'] = array('between',[$num_last,$num_next]);
                }elseif ($num_last >= 0 && $num_last != null){
                    $where['d.like_num'] = array('egt',$num_last);
                }elseif ($num_next >= 0 && $num_next != null){
                    $where['d.like_num'] = array('elt',$num_next);
                }
            }
        }
        $hot_type = I('request.hot_type',0,'intval');//是否热门
	    if($hot_type){
	        $where['d.hot'] = array('eq',$hot_type);
        }
        $status = I('request.status',0,'intval');//热门状态
	    if($status){
	        $where['d.status'] = array('eq',$status);
        }
        $time_type = I('request.time_type',0,'intval');//时间选择
	    if($time_type && in_array($time_type,array(1,2))){
	        $start_time = I('request.start_time');
	        $end_time = I('request.end_time');
	        if($start_time){
	            $start_time = strtotime($start_time);
            }
            if($end_time){
	            $end_time = strtotime($end_time);
            }
            if($time_type == 1){
                if($start_time > 0 && $end_time >= 0){
                    $where['d.create_time'] = array('between',[$start_time,$end_time]);
                }elseif ($start_time > 0){
                    $where['d.create_time'] = array('egt',$start_time);
                }elseif ($end_time > 0){
                    $where['d.create_time'] = array('elt',$end_time);
                }
            }else{
                if($start_time > 0 && $end_time >= 0){
                    $where['d.update_time'] = array('between',[$start_time,$end_time]);
                }elseif ($start_time > 0){
                    $where['d.update_time'] = array('egt',$start_time);
                }elseif ($end_time > 0){
                    $where['d.update_time'] = array('elt',$end_time);
                }
            }
        }

	    $count = $this->discuss_model->alias('d')
            ->join('h2w_users as u on u.id=d.user_id')
            ->join('h2w_school as s on s.id=d.school_id')
            ->where($where)
            ->count();
	    $page = $this->page($count,20);
	    $discuss = $this->discuss_model->alias('d')
            ->join('h2w_users as u on u.id=d.user_id')
            ->join('h2w_school as s on s.id=d.school_id')
            ->join('h2w_category as c on c.id=d.category_id')
            ->where($where)
            ->field('d.*,u.user_name,s.school_name,c.name category_name')
            ->order('d.hot desc,d.update_time desc')
            ->limit($page->firstRow,$page->listRows)
            ->select();
	    foreach ($discuss as $k=>$v){
	        $label = '';
	        if(!empty($v['label'])){
	            $labels = M('Label')->where(array('id'=>array('in',$v['label'])))->select();
	            foreach ($labels as $m=>$n){
                    ($label == '') ? $label = $n['name'] : $label.='、'.$n['name'];
                }
            }
            if($label == ''){
                $label = '——';
            }
            $discuss[$k]['label'] = $label;
            if(empty($v['name'])){
	            $content = strip_tags(htmlspecialchars_decode($v['content']));
                $length = mb_strlen($content);
	            if($length > 15) {
                    $discuss[$k]['name'] = mb_substr($content, 0, 15, "utf-8").'...';
                }else{
                    $discuss[$k]['name'] = $content;
                }
            }
        }
        $this->assign("discuss",$discuss);
	    $this->assign("page",$page->show('Admin'));
        $this->display();
    }

    //讨论启用或停用
    public function discuss_status(){
        $id = I('request.id',0,'intval');
        $discuss = $this->discuss_model->find($id);
        if($discuss){
            if($discuss['status'] == 1){//停用
                if($this->discuss_model->save(array('id'=>$id,'status'=>2)) !== false){
                    $this->success("停用成功");
                }
            }else{//启用
                if($this->discuss_model->save(array('id'=>$id,'status'=>1)) !== false){
                    $this->success("启用成功");
                }
            }
            $this->error("操作失败");
        }else{
            $this->error("操作失败");
        }
    }

    //讨论设为或取消热门
    public function discuss_hot(){
        $id = I('request.id',0,'intval');
        $discuss = $this->discuss_model->find($id);
        if($discuss){
            if($discuss['hot'] == 1){//设为热门
//                $count = $this->discuss_model->where(array('hot'=>2))->count();
//                if($count >= 6){
//                    $this->error("设置失败,热门讨论数量已经有6个了");
//                }
                if($this->discuss_model->save(array('id'=>$id,'hot'=>2)) !== false){
                    $this->success("设为热门成功");
                }
            }else{//取消热门
                if($this->discuss_model->save(array('id'=>$id,'hot'=>1)) !== false){
                    $this->success("取消热门成功");
                }
            }
            $this->error("操作失败");
        }else{
            $this->error("操作失败");
        }
    }

    //讨论详情页
    public function info(){
        $id = I('request.id',0,'intval');
        $discuss = $this->discuss_model->alias('d')
            ->join('h2w_users as u on u.id=d.user_id')
            ->join('h2w_school as s on s.id=d.school_id')
            ->join('h2w_category as c on c.id=d.category_id')
            ->field('d.*,u.user_name,s.school_name,c.name category_name')
            ->where(array('d.id'=>$id))->find();
        if(!$discuss){
            $this->error("不存在该讨论信息",U('Discuss/index'));
        }
        $label = '';
        if(!empty($discuss['label'])){
            $labels = M('Label')->where(array('id'=>array('in',$discuss['label'])))->select();
            foreach ($labels as $m=>$n){
                ($label == '') ? $label = $n['name'] : $label.='、'.$n['name'];
            }
        }
        if($label == ''){
            $label = '——';
        }
        $discuss['label'] = $label;
        if(empty($discuss['name'])){
            $content = strip_tags(htmlspecialchars_decode($discuss['content']));
            $length = mb_strlen($content);
            if($length > 15) {
                $discuss['name'] = mb_substr($content, 0, 15, "utf-8").'...';
            }else{
                $discuss['name'] = $content;
            }
        }
        $image = array();
        if(!empty($discuss['image'])){
            $image = explode(',',$discuss['image']);
        }
        $discuss['image'] = $image;
        $this->assign($discuss);
        $this->display();
    }

    //评论列表
    public function comment(){
        $id = I('request.id',0,'intval');
        $where['c.discuss_id'] = array('eq',$id);
        /*搜索条件*/
        $keyword = I('request.keyword');//文本搜索
        if($keyword){
            $where['c.content|u.user_name'] = array('like',"%$keyword%");
        }
        $number_type = I('number_type',0,'intval');//量
        if($number_type && in_array($number_type,array(1,2,3))){
            $num_last = I('request.num_last');
            $num_next = I('request.num_next');
            if ($number_type == 1) {
                if ($num_last >= 0 && $num_next >= 0 && $num_last != null && $num_next != null) {
                    $where['c.id'] = array('between', [$num_last, $num_next]);
                } elseif ($num_last >= 0 && $num_last != null) {
                    $where['c.id'] = array('egt', $num_last);
                } elseif ($num_next >= 0 && $num_next != null) {
                    $where['c.id'] = array('elt', $num_next);
                }
            } elseif ($number_type == 2) {
                if ($num_last >= 0 && $num_next >= 0 && $num_last != null && $num_next != null) {
                    $where['c.like_num'] = array('between', [$num_last, $num_next]);
                } elseif ($num_last >= 0 && $num_last != null) {
                    $where['c.like_num'] = array('egt', $num_last);
                } elseif ($num_next >= 0 && $num_next != null) {
                    $where['c.like_num'] = array('elt', $num_next);
                }
            } else {
                if ($num_last >= 0 && $num_next >= 0 && $num_last != null && $num_next != null) {
                    $where['c.reply_num'] = array('between', [$num_last, $num_next]);
                } elseif ($num_last >= 0 && $num_last != null) {
                    $where['c.reply_num'] = array('egt', $num_last);
                } elseif ($num_next >= 0 && $num_next != null) {
                    $where['c.reply_num'] = array('elt', $num_next);
                }
            }
        }
        $start_time = I('request.start_time');
        $end_time = I('request.end_time');
        if($start_time){
            $start_time = strtotime($start_time);
        }
        if($end_time){
            $end_time = strtotime($end_time);
        }
        if($start_time > 0 && $end_time >= 0){
            $where['c.create_time'] = array('between',[$start_time,$end_time]);
        }elseif ($start_time > 0){
            $where['c.create_time'] = array('egt',$start_time);
        }elseif ($end_time > 0){
            $where['c.create_time'] = array('elt',$end_time);
        }

        $count = $this->comment_model->alias('c')->join('h2w_users as u on u.id=c.user_id')->where($where)->count();
        $page = $this->page($count,20);
        $comment = $this->comment_model->alias('c')
            ->join('h2w_users as u on u.id=c.user_id')
            ->field('c.*,u.user_name')
            ->where($where)
            ->order('c.create_time asc')
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $this->assign('id',$id);
        $this->assign('comment',$comment);
        $this->assign('page',$page->show('Admin'));
        $this->display();
    }

    //删除评论
    public function comment_delete(){
        $id = I('request.id',0,'intval');
        $comment = $this->comment_model->find($id);
        if($comment){
            if($this->comment_model->where(array('id'=>$id))->delete() !== false){
                $discuss = $this->discuss_model->where(array('id'=>$comment['discuss_id']))->find();
                if($discuss){//将讨论的评论量减去1
                    $this->discuss_model->save(array('id'=>$discuss['id'],'comment_num'=>$discuss['comment_num']-1,'like_num'=>$discuss['like_num']-$comment['like_num']));
                }
                //删除回复
                $this->reply_model->where(array('comment_id'=>$id))->delete();
                $this->success("删除成功");
            }else{
                $this->error("删除失败，网络错误");
            }
        }else{
            $this->error("操作失败");
        }
    }

    //评论详情
    public function reply(){
        $id = I('request.id',0,'intval');
        $where['c.id'] = array('eq',$id);
        $comment = $this->comment_model->alias('c')->join('h2w_users as u on u.id=c.user_id')->field('c.*,u.user_name')->where($where)->find();
        if(!$comment){
            $this->error("不存在该评论",U('Discuss/index'));
        }
        $count = $this->reply_model->where(array('comment_id'=>$id))->count();
        $page = $this->page($count,20);
        $reply = $this->reply_model->alias('r')->join('h2w_users as u on u.id=r.user_id')->field('r.*,u.user_name')->where(array('r.comment_id'=>$id))->select();
        $this->assign($comment);
        $this->assign("reply",$reply);
        $this->assign("page",$page->show('Admin'));
        $this->display();
    }

    //删除回复
    public function reply_delete(){
        $id = I('request.id',0,'intval');
        $reply = $this->reply_model->find($id);
        if($reply){
            if($this->reply_model->where(array('id'=>$id))->delete() !== false){
                $this->success("删除成功");
            }else{
                $this->error("删除失败，网络错误");
            }
        }else{
            $this->error("操作失败");
        }
    }

	//分类管理
    public function category(){
	    $count = $this->category_model->count();
	    $page = $this->page($count,20);
	    $category = $this->category_model->order('listorder asc,create_time desc')->limit($page->firstRow,$page->listRows)->select();
	    $this->assign('category',$category);
	    $this->assign('page',$page->show('Admin'));
	    $this->display();
    }

    //添加分类
    public function cate_add(){
        $this->display();
    }

    //添加分类提交
    public function cate_add_post(){
        if(IS_POST){
            if($this->category_model->create() !== false){
                if($this->category_model->add() !== false){
                    $this->ajaxReturn(['status'=>1,'info'=>'保存成功']);
                }else{
                    $this->ajaxReturn(['status'=>0,'info'=>'保存失败，网络错误']);
                }
            }else{
                $this->ajaxReturn(['status'=>0,'info'=>$this->category_model->getError()]);
            }
        }
    }

    //编辑分类
    public function cate_edit(){
        $id = I('request.id',0,'intval');
        $category = $this->category_model->find($id);
        $this->assign($category);
        $this->display();
    }

    //编辑分类提交
    public function cate_edit_post(){
        if(IS_POST){
            if($this->category_model->create() !== false){
                if($this->category_model->save() !== false){
                    $this->ajaxReturn(['status'=>1,'info'=>'更新成功']);
                }else{
                    $this->ajaxReturn(['status'=>0,'info'=>'更新失败，网络错误']);
                }
            }else{
                $this->ajaxReturn(['status'=>0,'info'=>$this->category_model->getError()]);
            }
        }
    }

    //显示或隐藏分类
    public function cate_action(){
        $id = I('request.id',0,'intval');
        $category = $this->category_model->find($id);
        if($category){
            if($category['status'] == 1){//隐藏
                if($this->category_model->save(array('id'=>$id,'status'=>0)) !== false){
                    $this->success("隐藏成功");
                }
            }else{//显示
                if($this->category_model->save(array('id'=>$id,'status'=>1)) !== false){
                    $this->success("显示成功");
                }
            }
            $this->error("操作失败");
        }else{
            $this->error("操作失败");
        }
    }

    //标签管理
    public function label(){
        $count = $this->label_model->count();
        $page = $this->page($count,20);
        $label = $this->label_model->order('listorder asc,create_time desc')->limit($page->firstRow,$page->listRows)->select();
        $this->assign('label',$label);
        $this->assign('page',$page->show('Admin'));
        $this->display();
    }

    //添加标签
    public function label_add(){
        $this->display();
    }

    //添加标签提交
    public function label_add_post(){
        if(IS_POST){
            $name = I('post.name');
            if(!empty($name)){
                if(strpos($name,',') !==false){
                    $this->ajaxReturn(['status'=>0,'info'=>"标签名字不能包含','"]);
                }
            }
            if($this->label_model->create() !== false){
                if($this->label_model->add() !== false){
                    $this->ajaxReturn(['status'=>1,'info'=>'保存成功']);
                }else{
                    $this->ajaxReturn(['status'=>0,'info'=>'保存失败，网络错误']);
                }
            }else{
                $this->ajaxReturn(['status'=>0,'info'=>$this->label_model->getError()]);
            }
        }
    }

    //编辑标签
    public function label_edit(){
        $id = I('request.id',0,'intval');
        $label = $this->label_model->find($id);
        $this->assign($label);
        $this->display();
    }

    //编辑标签提交
    public function label_edit_post(){
        if(IS_POST){
            $name = I('post.name');
            if(!empty($name)){
                if(strpos($name,',') !==false){
                    $this->ajaxReturn(['status'=>0,'info'=>"标签名字不能包含','"]);
                }
            }
            if($this->label_model->create() !== false){
                if($this->label_model->save() !== false){
                    $this->ajaxReturn(['status'=>1,'info'=>'更新成功']);
                }else{
                    $this->ajaxReturn(['status'=>0,'info'=>'更新失败，网络错误']);
                }
            }else{
                $this->ajaxReturn(['status'=>0,'info'=>$this->label_model->getError()]);
            }
        }
    }

    //显示或隐藏标签
    public function label_action(){
        $id = I('request.id',0,'intval');
        $label = $this->label_model->find($id);
        if($label){
            if($label['status'] == 1){//隐藏
                if($this->label_model->save(array('id'=>$id,'status'=>0)) !== false){
                    $this->success("隐藏成功");
                }
            }else{//显示
                if($this->label_model->save(array('id'=>$id,'status'=>1)) !== false){
                    $this->success("显示成功");
                }
            }
            $this->error("操作失败");
        }else{
            $this->error("操作失败");
        }
    }
}