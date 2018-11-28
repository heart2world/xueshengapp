<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/28
 * Time: 10:27
 */

namespace Mobile\Controller;


class IndexController extends BaseController
{
    //判断是否签到并获取积分
    public function sign_in(){
        if(IS_POST){
            $user_id = intval(I('post.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在']);
            }
            //判断该用户今日是否签到
            $today_time = strtotime(date('Y-m-d'));
            $sign = M('UsersSign')->where(array('user_id'=>$user_id,'sign_time'=>$today_time))->find();
            if($sign){
                $this->ajaxReturn(['status'=>0,'info'=>'当天已签到']);
            }
            //获取缓存数据
            $set_score = S('score_setting');
            if (empty($set_score) || $set_score == false) {
                $set_score = score_setting();
            }
            $gain_score = 0;//应该获得的积分
            $continue_day = 0;//连续签到天数
            $first_day = $set_score['first_day'];//首次登录获得积分
            $increase_progressively = $set_score['increase_progressively'];//连续登录增长积分
            $max_single_time = $set_score['max_single_time'];//连续登录单次最多获得积分
            if($user_info['continuous_day'] == 0){//第一次签到
                $gain_score = $first_day;
                $continue_day = 1;
            }else{
                //判断是否是连续登录
                $last_day_time = $today_time-3600*24;//昨天时间
                $last_day_sign = M('UsersSign')->where(array('user_id'=>$user_id,'sign_time'=>$last_day_time))->find();
                if($last_day_sign){//是连续签到
                    $continue_day = $user_info['continuous_day']+1;
                    $calculate_score = ($continue_day-1)*$increase_progressively+$first_day;
                    if($calculate_score > $max_single_time){
                        $gain_score = $max_single_time;
                    }else{
                        $gain_score = $calculate_score;
                    }
                }else{//不是连续签到
                    $gain_score = $first_day;
                    $continue_day = 1;
                }
            }
            //添加签到记录
            $signData = [
                'user_id' => $user_id,
                'sign_time' => $today_time,
                'score' => $gain_score,
                'create_time' => time()
            ];
            if(M('UsersSign')->add($signData) !== false){
                //修改用户积分
                M('Users')->save(array('id'=>$user_id,'score'=>$user_info['score']+$gain_score,'continuous_day'=>$continue_day));
                $this->ajaxReturn(['status'=>1,'info'=>'签到成功','score'=>$gain_score]);
            }else{
                $this->ajaxReturn(['status'=>-1,'info'=>'网络错误,请稍后再试']);
            }
        }
    }

    //获取首页热门讨论
    public function index_hot(){
        $user_id = intval(I('request.uid'));
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 20;
        $start = ($now_page - 1) * $pageSize;
        $banner = array();
        if($now_page == 1) {//获取轮播图
            $banner = M('Slide')->where(array('slide_status' => 1))->field('id,slide_pic,slide_url')->order('listorder asc')->select();
            foreach ($banner as $k => $v) {
                $banner[$k]['slide_pic'] = sp_get_image_preview_url($v['slide_pic']);
            }
        }
        //获取热门列表
        $where['d.hot'] = array('eq',2);
        $where['d.status'] = array('eq',1);
        $total_count = M('Discuss')->alias('d')->where($where)->count();
        $total_page = ceil($total_count/$pageSize);
        $discuss = M('Discuss')->alias('d')
            ->join('h2w_users as u on u.id=d.user_id')
            ->join('h2w_school as s on s.id=d.school_id')
            ->where($where)
            ->field('d.*,u.user_name,u.avatar,u.user_type,s.school_name')
            ->order('d.create_time desc')
            ->limit($start,$pageSize)
            ->select();
        //处理讨论信息
        $discuss = $this->discuss_action($discuss,$user_id);
        $this->ajaxReturn(['status'=>1,'discuss'=>$discuss,'banner'=>$banner,'total_page'=>$total_page]);
    }

    //获取首页兴趣页数据
    public function discuss_savor(){
        $user_id = intval(I('request.uid'));
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 20;
        $start = ($now_page - 1) * $pageSize;
        $where['d.status'] = array('eq',1);
        //获取用户感兴趣的标签
        $users_label = M('UsersLabel')->where(array('user_id'=>$user_id))->select();
        if(count($users_label) > 0){
            $label_array = array();
            foreach ($users_label as $k=>$v){
                $label_array[] = $v['label_id'];
            }
            //获取与这些标签相关联的讨论id
            $where_label['label_id'] = array('in',$label_array);
            $discuss_label = M('DiscussLabel')->where($where_label)->group('discuss_id')->select();
            if(count($discuss_label) > 0){
                $discuss_array = array();
                foreach ($discuss_label as $x=>$y){
                    $discuss_array[] = $y['discuss_id'];
                }
                $where['d.id'] = array('in',$discuss_array);
                $total_count = M('Discuss')->alias('d')->where($where)->count();
                $discuss = M('Discuss')->alias('d')
                    ->join('h2w_users as u on u.id=d.user_id')
                    ->join('h2w_school as s on s.id=d.school_id')
                    ->where($where)
                    ->field('d.*,u.user_name,u.avatar,u.user_type,s.school_name')
                    ->order('d.create_time desc')
                    ->limit($start,$pageSize)
                    ->select();
            }else{
                $total_count = 0;
                $discuss = array();
            }
        }else{
            $total_count = M('Discuss')->alias('d')->where($where)->count();
            $discuss = M('Discuss')->alias('d')
                ->join('h2w_users as u on u.id=d.user_id')
                ->join('h2w_school as s on s.id=d.school_id')
                ->where($where)
                ->field('d.*,u.user_name,u.avatar,u.user_type,s.school_name')
                ->order('d.create_time desc')
                ->limit($start,$pageSize)
                ->select();
        }
        $total_page = ceil($total_count/$pageSize);
        //处理讨论信息
        $discuss = $this->discuss_action($discuss,$user_id);
        $this->ajaxReturn(['status'=>1,'discuss'=>$discuss,'total_page'=>$total_page]);
    }

    //讨论收藏操作
    public function discuss_collect(){
        if(IS_POST){
            $user_id = intval(I('post.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在']);
            }
            $discuss_id = intval(I('post.did'));
            //判断是否已经收藏
            $collect = M('UsersDiscuss')->where(array('user_id'=>$user_id,'discuss_id'=>$discuss_id,'type'=>1))->find();
            if($collect){//取消收藏
                $result = M('UsersDiscuss')->where(array('id'=>$collect['id']))->delete();
                if($result !== false){
                    $this->ajaxReturn(['status'=>1,'info'=>'取消收藏成功']);
                }
            }else{//收藏
                $dataInfo = [
                    'user_id' => $user_id,
                    'discuss_id' => $discuss_id,
                    'type' => 1,
                    'create_time' => time()
                ];
                $result = M('UsersDiscuss')->add($dataInfo);
                if($result !== false){
                    $this->ajaxReturn(['status'=>1,'info'=>'收藏成功']);
                }
            }
            $this->ajaxReturn(['status'=>-1,'info'=>'网络错误,请稍后再试']);
        }
    }

    //讨论搜索页
    public function search(){
        $user_id = intval(I('request.uid'));
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 20;
        $start = ($now_page - 1) * $pageSize;
        //关键词
        $keyword = I('request.keyword');
        if(empty($keyword)){
            $this->ajaxReturn(['status'=>0,'info'=>'请输入搜索词']);
        }
        $where['d.name|d.content'] = array('like',"%$keyword%");
        $where['d.status'] = array('eq',1);
        $total_count = M('Discuss')->alias('d')->where($where)->count();
        $total_page = ceil($total_count/$pageSize);
        $discuss = M('Discuss')->alias('d')
            ->join('h2w_users as u on u.id=d.user_id')
            ->join('h2w_school as s on s.id=d.school_id')
            ->where($where)
            ->field('d.*,u.user_name,u.avatar,u.user_type,s.school_name')
            ->order('d.create_time desc')
            ->limit($start,$pageSize)
            ->select();
        //处理讨论信息
        $discuss = $this->discuss_action($discuss,$user_id);
        $this->ajaxReturn(['status'=>1,'discuss'=>$discuss,'total_page'=>$total_page]);
    }

    //讨论区数据
    public function discuss_area(){
        $user_id = intval(I('request.uid'));
        $school_id = intval(I('request.sid'));
        $category_id = I('request.cid',0,'intval');
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 20;
        $start = ($now_page - 1) * $pageSize;
        /*搜索条件*/
        $where['d.school_id'] = array('eq',$school_id);
        $where['d.status'] = array('eq',1);
        $category = array();
        if($category_id){
            $where['d.category_id'] = array('eq',$category_id);
        }else{
            $category = M('Category')->where(array('status'=>1))->order('listorder asc')->select();
        }
        $total_count = M('Discuss')->alias('d')->where($where)->count();
        $total_page = ceil($total_count/$pageSize);
        $discuss = M('Discuss')->alias('d')
            ->join('h2w_users as u on u.id=d.user_id')
            ->join('h2w_school as s on s.id=d.school_id')
            ->where($where)
            ->field('d.*,u.user_name,u.avatar,u.user_type,s.school_name')
            ->order('d.create_time desc')
            ->limit($start,$pageSize)
            ->select();
        //处理讨论信息
        $discuss = $this->discuss_action($discuss,$user_id);
        $this->ajaxReturn(['status'=>1,'category'=>$category,'discuss'=>$discuss,'total_page'=>$total_page]);
    }
}