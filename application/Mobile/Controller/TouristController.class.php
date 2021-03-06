<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/3
 * Time: 13:46
 */

namespace Mobile\Controller;

use Common\Controller\AppframeController;

class TouristController extends AppframeController
{
    //获取首页热门讨论
    public function index_hot(){
        $user_id = intval(I('request.uid'));
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 6;
        $start = ($now_page - 1) * $pageSize;
        $banner = array();
        if($now_page == 1) {//获取轮播图
            $banner = M('Slide')->where(array('slide_status' => 1))->field('slide_id,slide_pic,slide_url')->order('listorder asc')->select();
            foreach ($banner as $k => $v) {
                $banner[$k]['slide_pic'] = sp_get_image_preview_url($v['slide_pic']);
            }
        }
        //获取热门列表
        $where['d.hot'] = array('eq',2);
        $where['d.status'] = array('eq',1);
        $total_count = M('Discuss')->where(array('hot'=>2,'status'=>1))->count();
        $total_page = ceil($total_count/$pageSize);
        $discuss = M('Discuss')->alias('d')
            ->join('h2w_users as u on u.id=d.user_id')
            ->where($where)
            ->field('d.*,u.user_name,u.avatar,u.user_type,u.verify_id usc_id,u.verify')
            ->order('d.create_time desc')
            ->limit($start,$pageSize)
            ->select();
        //处理讨论信息
        $discuss = $this->discuss_action($discuss,$user_id);
        $this->ajaxReturn(['status'=>1,'discuss'=>$discuss,'banner'=>$banner,'total_page'=>$total_page]);
    }

    //讨论区数据
    public function discuss_area(){
        $user_id = intval(I('request.uid'));
        $school_id = intval(I('request.sid'));
        if(empty($school_id)){
            $school_id = 1;
        }
        $category_id = I('request.cid',0,'intval');
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 20;
        $start = ($now_page - 1) * $pageSize;
        $school = M('School')->where(array('id'=>$school_id,'status'=>0))->find();
        if(!$school){
            $this->ajaxReturn(['status'=>-5,'info'=>'该大学讨论区已关闭']);
        }
        if($school['type'] != 1){
            $this->ajaxReturn(['status'=>0,'info'=>'只有大学才存在讨论区']);
        }
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
            ->where($where)
            ->field('d.*,u.user_name,u.avatar,u.user_type,u.verify_id usc_id,u.verify')
            ->order('d.update_time desc')
            ->limit($start,$pageSize)
            ->select();
        //处理讨论信息
        $discuss = $this->discuss_action($discuss,$user_id);
        //更新用户所属大学讨论区
        M('Users')->save(array('id'=>$user_id,'school_area'=>$school_id));
        $this->ajaxReturn(['status'=>1,'category'=>$category,'discuss'=>$discuss,'total_page'=>$total_page,'school_id'=>$school_id,'school_name'=>$school['school_name']]);
    }

    //处理讨论信息
    function discuss_action($discuss,$user_id = '',$keyword = ''){
        foreach ($discuss as $k=>$v){
            $discuss[$k]['school_name'] = '';//获取学校名称
            $discuss[$k]['school_types'] = '';
            if($v['usc_id'] > 0 && $v['user_type'] == 2 && $v['verify'] == 1){
                $school = M('School')->where(array('id'=>$v['usc_id']))->find();
                if($school){
                    $discuss[$k]['school_name'] = $school['school_name'];
                    $discuss[$k]['school_types'] = $school['type'];
                }
            }
            if(empty($v['avatar'])){//头像
                $v['avatar'] = 'mobile/avatar.jpg';
            }
            $discuss[$k]['avatar'] = sp_get_image_preview_url($v['avatar']);
            if(!empty($user_id)) {//收藏
                $collect = M('UsersCollect')->where(array('user_id' => $user_id, 'collect_id' => $v['id'], 'type' => 1))->find();
                if ($collect) {
                    $discuss[$k]['collect'] = 1;
                } else {
                    $discuss[$k]['collect'] = 0;
                }
            }else{
                $discuss[$k]['collect'] = 0;
            }
            if(!empty($v['image'])) {//图片
                $images = explode(',', $v['image']);
                foreach ($images as $x => $y) {
                    $images[$x] = sp_get_image_preview_url($y);
                }
            }else{
                $images = array();
            }
            $discuss[$k]['image'] = $images;
            $time_distance = time()-$v['create_time'];//多久之前
            if($time_distance >= 3600*24*5){
                $discuss[$k]['time_ago'] = date('Y-m-d',$v['create_time']);
            }elseif ($time_distance >= 3600*24){
                $discuss[$k]['time_ago'] = floor($time_distance/86400).'天前';
            }elseif ($time_distance >= 3600){
                $discuss[$k]['time_ago'] = floor($time_distance/3600).'小时前';
            }else{
                $discuss[$k]['time_ago'] = floor($time_distance/60).'分钟前';
            }
            //处理名称及内容
            if($keyword == '') {
                if (empty($v['name'])) {
                    $content = strip_tags(htmlspecialchars_decode($v['content']));
                    $discuss[$k]['name'] = mb_substr($content, 0, 30, "utf-8");
                }
            }else{
                $content = strip_tags(htmlspecialchars_decode($v['content']));//内容
                $position = mb_stripos($content,$keyword);//字符在字符串中的位置
                if($position !== false){
                    $start = $position-10;
                    if($start < 0){
                        $start = 0;
                    }
                    $length = 10;
                    if($position < 10){
                        $length = $position;
                    }
                    $the_info = mb_substr($content,$start,$length,"utf-8")."<span style='color: #FF0000;'>$keyword</span>".mb_substr($content,$position+mb_strlen($keyword),20,"utf-8");
                }else{
                    $the_info = mb_substr($content, 0, 30, "utf-8");
                }
                if(empty($v['name'])){//如果标题为空
                    $discuss[$k]['name'] = $the_info;//则内容变为标题
                    $discuss[$k]['content'] = '';//内容再变为空
                }else{//如果有标题
                    $discuss[$k]['name'] = str_replace($keyword,"<span style='color: #FF0000;'>$keyword</span>",$v['name']);
                    if($position){//如果内容符合
                        if($position > 10){//如果符合位置大于10
                            $discuss[$k]['content'] = '...'.$the_info.'...';
                        }else{//如果符合位置小于10
                            $discuss[$k]['content'] = $the_info.'...';
                        }
                    }else{//如果内容不符合
                        $discuss[$k]['content'] = '';//则内容变为空
                    }
                }
            }
        }
        return $discuss;
    }

    //获取讨论详情
    public function discuss_info(){
        $user_id = intval(I('request.uid'));
        $discuss_id = intval(I('request.did'));
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 20;
        $start = ($now_page - 1) * $pageSize;
        $discuss = array();
        if($now_page == 1) {
            $where['d.id'] = array('eq', $discuss_id);
            $where['d.status'] = array('eq', 1);
            $discuss = M('Discuss')->alias('d')
                ->join('h2w_users as u on u.id=d.user_id')
                ->where($where)
                ->field('d.*,u.user_name,u.avatar,u.user_type,u.verify_id usc_id,u.verify')
                ->find();
            if ($discuss) {
                $school = M('School')->where(array('id'=>$discuss['school_id'],'status'=>0))->find();
                if(!$school){
                    $this->ajaxReturn(['status'=>-5,'info'=>'该大学讨论区已关闭']);
                }
                $discuss['school_name'] = '';//学校
                $discuss['school_types'] = '';//学校
                if($discuss['usc_id'] > 0 && $discuss['user_type'] == 2 && $discuss['verify'] == 1){
                    $schoolInfo = M('School')->where(array('id'=>$discuss['usc_id']))->find();
                    if($schoolInfo){
                        $discuss['school_name'] = $schoolInfo['school_name'];
                        $discuss['school_types'] = $schoolInfo['type'];
                    }
                }
                M('Discuss')->save(array('id'=>$discuss_id,'click_num'=>$discuss['click_num']+1));
                if(empty($discuss['avatar'])){
                    $discuss['avatar'] = 'mobile/avatar.jpg';
                }
                $discuss['avatar'] = sp_get_image_preview_url($discuss['avatar']);
                if(!empty($user_id)) {
                    $collect = M('UsersCollect')->where(array('user_id' => $user_id, 'collect_id' => $discuss_id, 'type' => 1))->find();
                    if ($collect) {
                        $discuss['collect'] = 1;
                    } else {
                        $discuss['collect'] = 0;
                    }
                }else{
                    $discuss['collect'] = 0;
                }
                if (!empty($discuss['image'])) {
                    $images = explode(',', $discuss['image']);
                    foreach ($images as $x => $y) {
                        $images[$x] = sp_get_image_preview_url($y);
                    }
                } else {
                    $images = array();
                }
                $discuss['image'] = $images;
                $time_distance = time()-$discuss['create_time'];
                if($time_distance >= 3600*24*5){
                    $discuss['time_ago'] = date('Y-m-d',$discuss['create_time']);
                } elseif ($time_distance >= 3600 * 24) {
                    $discuss['time_ago'] = floor($time_distance / 86400) . '天前';
                } elseif ($time_distance >= 3600) {
                    $discuss['time_ago'] = floor($time_distance / 3600) . '小时前';
                } else {
                    $discuss['time_ago'] = floor($time_distance / 60) . '分钟前';
                }
                $label = array();
                if (!empty($discuss['label'])) {
                    $labels = M('Label')->where(array('id'=>array('in',$discuss['label'])))->select();
                    foreach ($labels as $m=>$n){
                        $label[] = $n['name'];
                    }
                }
                $discuss['label'] = $label;
            } else {
                $this->ajaxReturn(['status' => -6, 'info' => '该讨论不存在或已被停用']);
            }
        }
        //获取评论列表及评论回复
        $where_comment['c.discuss_id'] = array('eq',$discuss_id);
        $total_count = M('Comment')->alias('c')->where($where_comment)->count();
        $total_page = ceil($total_count/$pageSize);
        $comment = M('Comment')->alias('c')
            ->join('h2w_users as u on u.id=c.user_id')
            ->where($where_comment)
            ->field('c.*,u.user_name,u.avatar,u.user_type,u.verify_id,u.verify')
            ->order('c.like_num desc,c.create_time desc')
            ->limit($start,$pageSize)
            ->select();

        foreach ($comment as $k=>$v){
            $comment[$k]['school_name'] = '';
            $comment[$k]['school_types'] = '';//学校
            if($v['verify_id'] > 0 && $v['user_type'] == 2 && $v['verify'] == 1) {
                $school = M('School')->where(array('id' => $v['verify_id']))->find();
                if ($school) {
                    $comment[$k]['school_name'] = $school['school_name'];
                    $comment[$k]['school_types'] = $school['type'];
                }
            }
            if(empty($v['avatar'])){
                $v['avatar'] = 'mobile/avatar.jpg';
            }
            $comment[$k]['avatar'] = sp_get_image_preview_url($v['avatar']);
            $time_comment = time()-$v['create_time'];
            if($time_comment >= 3600*24*5){
                $comment[$k]['time_ago'] = date('Y-m-d',$v['create_time']);
            } elseif ($time_comment >= 3600 * 24) {
                $comment[$k]['time_ago'] = floor($time_comment / 86400) . '天前';
            } elseif ($time_comment >= 3600) {
                $comment[$k]['time_ago'] = floor($time_comment / 3600) . '小时前';
            } else {
                $comment[$k]['time_ago'] = floor($time_comment / 60) . '分钟前';
            }
            if(!empty($user_id)) {
                $like = M('UsersComment')->where(array('user_id'=>$user_id,'comment_id'=>$v['id'],'type'=>1))->find();
                if ($like) {
                    $comment[$k]['like'] = 1;
                } else {
                    $comment[$k]['like'] = 0;
                }
            }else{
                $comment[$k]['like'] = 0;
            }
            $reply_count = M('Reply')->where(array('comment_id'=>$v['id']))->count();//回复数量
            //获取该评论下的回复
            $reply = M('Reply')->alias('r')
                ->join('h2w_users as u on u.id=r.user_id')
                ->where(array('r.comment_id'=>$v['id']))
                ->field('r.*,u.user_name')
                ->order('r.create_time asc')
                ->limit(3)
                ->select();
            foreach ($reply as $x=>$y){
                $time_dis = time()-$y['create_time'];
                if($time_dis >= 3600*24*5){
                    $reply[$x]['time_ago'] = date('Y-m-d',$y['create_time']);
                } elseif ($time_dis >= 3600 * 24) {
                    $reply[$x]['time_ago'] = floor($time_dis / 86400) . '天前';
                } elseif ($time_dis >= 3600) {
                    $reply[$x]['time_ago'] = floor($time_dis / 3600) . '小时前';
                } else {
                    $reply[$x]['time_ago'] = floor($time_dis / 60) . '分钟前';
                }
            }
            $comment[$k]['reply_count'] = $reply_count;
            $comment[$k]['reply'] = $reply;
        }
        $this->ajaxReturn(['status'=>1,'discuss'=>$discuss,'comment'=>$comment,'total_page'=>$total_page]);
    }

    //获取评论详情信息
    public function comment_info(){
        $user_id = intval(I('request.uid'));
        $comment_id = intval(I('request.comment_id'));
        $where['c.id'] = array('eq',$comment_id);
        $comment = M('Comment')->alias('c')
            ->join('h2w_users as u on u.id=c.user_id')
            ->where($where)
            ->field('c.*,u.user_name,u.avatar,u.user_type,u.verify_id,u.verify')
            ->find();
        if($comment){
            $comment['school_name'] = '';
            $comment['school_types'] = '';//学校
            if($comment['verify_id'] > 0 && $comment['user_type'] == 2 && $comment['verify'] == 1) {
                $school = M('School')->where(array('id' => $comment['verify_id']))->find();
                if ($school) {
                    $comment['school_name'] = $school['school_name'];
                    $comment['school_types'] = $school['type'];
                }
            }
            if(empty($comment['avatar'])){
                $comment['avatar'] = 'mobile/avatar.jpg';
            }
            $comment['avatar'] = sp_get_image_preview_url($comment['avatar']);
            $time_comment = time()-$comment['create_time'];
            if($time_comment >= 3600*24*5){
                $comment['time_ago'] = date('Y-m-d',$comment['create_time']);
            } elseif ($time_comment >= 3600 * 24) {
                $comment['time_ago'] = floor($time_comment / 86400) . '天前';
            } elseif ($time_comment >= 3600) {
                $comment['time_ago'] = floor($time_comment / 3600) . '小时前';
            } else {
                $comment['time_ago'] = floor($time_comment / 60) . '分钟前';
            }
            if(!empty($user_id)) {
                $like = M('UsersComment')->where(array('user_id'=>$user_id,'comment_id'=>$comment_id,'type'=>1))->find();
                if ($like) {
                    $comment['like'] = 1;
                } else {
                    $comment['like'] = 0;
                }
            }else{
                $comment['like'] = 0;
            }
            //获取该评论下的回复
            $reply = M('Reply')->alias('r')
                ->join('h2w_users as u on u.id=r.user_id')
                ->where(array('r.comment_id'=>$comment_id))
                ->field('r.*,u.user_name')
                ->order('r.create_time asc')
                ->select();
            foreach ($reply as $x=>$y){
                $time_dis = time()-$y['create_time'];
                if($time_dis >= 3600*24*5){
                    $reply[$x]['time_ago'] = date('Y-m-d',$y['create_time']);
                } elseif ($time_dis >= 3600 * 24) {
                    $reply[$x]['time_ago'] = floor($time_dis / 86400) . '天前';
                } elseif ($time_dis >= 3600) {
                    $reply[$x]['time_ago'] = floor($time_dis / 3600) . '小时前';
                } else {
                    $reply[$x]['time_ago'] = floor($time_dis / 60) . '分钟前';
                }
            }
            $comment['reply'] = $reply;
            $this->ajaxReturn(['status'=>1,'comment'=>$comment]);
        }else{
            $this->ajaxReturn(['status'=>-6,'info'=>'当前评论不存在']);
        }
    }

    //藏经阁
    public function index(){
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 20;
        $start = ($now_page - 1) * $pageSize;

        //获取链接信息
        $link = array();
        if($now_page == 1){
            $link = clinks();
        }
        //获取笔记
        $where['status'] = array('eq',0);
        $total_count = M('Scripture')->where($where)->count();
        $total_page = ceil($total_count/$pageSize);
        $note = M('Scripture')->where($where)->order('create_time desc')->limit($start,$pageSize)->select();
        foreach ($note as $k=>$v){
            if(!empty($v['cover_img'])){
                $note[$k]['cover_img'] = sp_get_image_preview_url($v['cover_img']);
            }
            if(!empty($v['content'])){
                $note[$k]['content'] = strip_tags($v['content']);
            }
        }
        $this->ajaxReturn(['status'=>1,'link'=>$link,'note'=>$note,'total_page'=>$total_page]);
    }

    //学霸笔记详情
    public function note_info(){
        $user_id = intval(I('request.uid'));
        $note_id = intval(I('request.nid'));
        $note = M('Scripture')->where(array('id'=>$note_id,'status'=>0))->find();
        if(!$note){
            $this->ajaxReturn(['status'=>-7,'info'=>'当前笔记不存在或已被禁用']);
        }
        if(!empty($note['content'])){
            $note['content'] = htmlspecialchars_decode($note['content']);
        }
        if(!empty($note['cover_img'])){
            $note['cover_img'] = sp_get_image_preview_url($note['cover_img']);
        }
        //查询该笔记是否被收藏
        if(!empty($user_id)) {
            $collect = M('UsersCollect')->where(array('user_id' => $user_id, 'collect_id' => $note_id, 'type'=>2))->find();
            if ($collect) {
                $note['keep'] = 1;
            } else {
                $note['keep'] = 0;
            }
            M('Scripture')->save(array('id'=>$note_id,'views'=>$note['views']+1));
        }else{
            $note['keep'] = 0;
        }
        $this->ajaxReturn(['status'=>1,'note'=>$note]);
    }

    //藏宝阁
    public function mall(){
        $type = I('request.type',1,'intval');
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 20;
        $start = ($now_page - 1) * $pageSize;
        $where['status'] = array('eq',0);
        ($type == 1) ? $where['type'] = array('eq',1) : $where['type'] = array('eq',2);
        $total_count = M('Gift')->where($where)->count();
        $total_page = ceil($total_count/$pageSize);
        $goods = M('Gift')->where($where)->order('create_time desc')
            ->field('id,gift_name,price,surplus,cover_img')
            ->limit($start,$pageSize)
            ->select();
        foreach ($goods as $k=>$v){
            if(!empty($v['cover_img'])){
                $goods[$k]['cover_img'] = sp_get_image_preview_url($v['cover_img']);
            }
        }
        $this->ajaxReturn(['status'=>1,'goods'=>$goods,'total_page'=>$total_page]);
    }

    //商品详情
    public function goods_info(){
        $goods_id = intval(I('request.gid'));
        $goods = M('Gift')->where(array('id'=>$goods_id,'status'=>0))->find();
        if(!$goods){
            $this->ajaxReturn(['status'=>-8,'info'=>'该商品不存在或已下架']);
        }
        if(!empty($goods['cover_img'])){
            $goods['cover_img'] = sp_get_image_preview_url($goods['cover_img']);
        }
        if(!empty($goods['product_intro'])){
            $product_intro = json_decode(htmlspecialchars_decode($goods['product_intro']),true);
            array_multisort(array_column($product_intro,'sort'),SORT_ASC,$product_intro);
            foreach ($product_intro as $k=>$v){
                $product_intro[$k]['img'] = sp_get_image_preview_url($v['img']);
            }
            $goods['product_intro'] = $product_intro;
        }
        $this->ajaxReturn(['status'=>1,'goods'=>$goods]);
    }
}