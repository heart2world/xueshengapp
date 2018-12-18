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
            $user_id = intval(I('request.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
            }
            //判断该用户今日是否签到
            if($user_info['sign'] == 1){
                $this->ajaxReturn(['status'=>0,'info'=>'当天已签到']);
            }
            $today_time = strtotime(date('Y-m-d'));
            //获取缓存数据
            $set_score = S('score_setting');
            if (empty($set_score) || $set_score == false) {
                $set_score = score_setting();
            }
            $gain_score = 0;//应该获得的积分
            $continue_day = 0;//连续签到天数
            $first_day = $set_score['first_day'];//首次登录获得积分20
            $increase_progressively = $set_score['increase_progressively'];//连续登录增长积分1
            $max_single_time = $set_score['max_single_time'];//连续登录单次最多获得积分10
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
                M('Users')->save(array('id'=>$user_id,'score'=>$user_info['score']+$gain_score,'online_time'=>$user_info['online_time']+1,'continuous_day'=>$continue_day));
                //添加积分记录
                $this->save_score($user_id,$gain_score,1);
                $this->ajaxReturn(['status'=>1,'info'=>'签到成功','score'=>$gain_score]);
            }else{
                $this->ajaxReturn(['status'=>-1,'info'=>'网络错误,请稍后再试']);
            }
        }
    }

    //获取首页兴趣页数据
    public function discuss_savor(){
        $user_id = intval(I('request.uid'));
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 6;
        $start = ($now_page - 1) * $pageSize;

        $total_count = 0;//总数
        $discuss = array();//讨论数组
        //获取用户感兴趣的标签
        $users_label = M('UsersLabel')->where(array('user_id'=>$user_id))->select();
        if(count($users_label) > 0){
            $label_array = array();
            foreach ($users_label as $k=>$v){
                $label_array[] = $v['label_id'];
            }
            $label_array = implode(',',$label_array);
//            //获取与这些标签相关联的讨论id
//            $where_label['label_id'] = array('in',$label_array);
//            $discuss_label = M('DiscussLabel')->where($where_label)->group('discuss_id')->select();
//            if(count($discuss_label) > 0){
//                $discuss_array = array();
//                foreach ($discuss_label as $x=>$y){
//                    $discuss_array[] = $y['discuss_id'];
//                }
//                $where['d.id'] = array('in',$discuss_array);
//                $total_count = M('Discuss')->alias('d')->where($where)->count();
//                $discuss = M('Discuss')->alias('d')
//                    ->join('h2w_users as u on u.id=d.user_id')
//                    ->join('h2w_school as s on s.id=d.school_id')
//                    ->where($where)
//                    ->field('d.*,u.user_name,u.avatar,u.user_type,s.school_name')
//                    ->order('d.create_time desc')
//                    ->limit($start,$pageSize)
//                    ->select();
//            }else{
//                $total_count = 0;
//                $discuss = array();
//            }
            $Model = new \Think\Model();
            $total_info = $Model->query("select count(*) AS number from h2w_discuss d, h2w_discuss_label dl where d.id=dl.discuss_id and d.status=1 and dl.label_id in($label_array) GROUP BY dl.discuss_id");
            $total_count = $total_info[0]['number'];
            $discuss = $Model->query("select d.* from h2w_discuss d, h2w_discuss_label dl where d.id=dl.discuss_id and d.status=1 and dl.label_id in($label_array) GROUP BY dl.discuss_id order by count(dl.label_id) desc,d.create_time desc limit $start,$pageSize");
            foreach ($discuss as $k=>$v){
                $userInfo = M('Users')->where(array('id'=>$v['user_id']))->find();
                $discuss[$k]['user_name'] = $userInfo['user_name'];
                $discuss[$k]['avatar'] = $userInfo['avatar'];
                $discuss[$k]['user_type'] = $userInfo['user_type'];
                $discuss[$k]['usc_id'] = $userInfo['school_id'];
            }
        }
        if($total_count == 0) {
            $where['d.status'] = array('eq', 1);
            $total_count = M('Discuss')->alias('d')->where($where)->count();
            $discuss = M('Discuss')->alias('d')
                ->join('h2w_users as u on u.id=d.user_id')
                ->where($where)
                ->field('d.*,u.user_name,u.avatar,u.user_type,u.school_id usc_id')
                ->order('d.update_time desc,d.click_num desc')
                ->limit($start, $pageSize)
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
            $user_id = intval(I('request.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
            }
            $discuss_id = intval(I('post.did'));
            $discuss = M('Discuss')->where(array('id'=>$discuss_id,'status'=>1))->find();
            if(!$discuss){
                $this->ajaxReturn(['status'=>0,'info'=>'当前讨论不存在或已被停用']);
            }
            //判断是否已经收藏
            $collect = M('UsersCollect')->where(array('user_id'=>$user_id,'collect_id'=>$discuss_id,'type'=>1))->find();
            if($collect){//取消收藏
                $result = M('UsersCollect')->where(array('id'=>$collect['id']))->delete();
                if($result !== false){
                    M('Discuss')->save(array('id'=>$discuss_id,'collect_num'=>$discuss['collect_num']-1));
                    $this->ajaxReturn(['status'=>1,'info'=>'取消收藏成功']);
                }
            }else{//收藏
                $dataInfo = [
                    'user_id' => $user_id,
                    'collect_id' => $discuss_id,
                    'type' => 1,
                    'create_time' => time()
                ];
                $result = M('UsersCollect')->add($dataInfo);
                if($result !== false){
                    M('Discuss')->save(array('id'=>$discuss_id,'collect_num'=>$discuss['collect_num']+1));
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
        $keyword = trim(I('request.keyword'));
        if($keyword == ''){
            $this->ajaxReturn(['status'=>0,'info'=>'请输入搜索内容']);
        }
        $where['d.name|d.content'] = array('like',"%$keyword%");
        $where['d.status'] = array('eq',1);
        $total_count = M('Discuss')->alias('d')->where($where)->count();
        $total_page = ceil($total_count/$pageSize);
        $discuss = M('Discuss')->alias('d')
            ->join('h2w_users as u on u.id=d.user_id')
            ->where($where)
            ->field('d.*,u.user_name,u.avatar,u.user_type,u.school_id usc_id')
            ->order('d.create_time desc')
            ->limit($start,$pageSize)
            ->select();
        //处理讨论信息
        $discuss = $this->discuss_action($discuss,$user_id,$keyword);
        $this->ajaxReturn(['status'=>1,'discuss'=>$discuss,'total_page'=>$total_page]);
    }

    //获取评论详情信息
    public function comment_info(){
        $comment_id = intval(I('request.comment_id'));
        $where['c.id'] = array('eq',$comment_id);
        $comment = M('Comment')->alias('c')
            ->join('h2w_users as u on u.id=c.user_id')
            ->where($where)
            ->field('c.*,u.user_name,u.avatar,u.user_type,u.school_id')
            ->find();
        if($comment){
            $comment['school_name'] = '';
            $school = M('School')->where(array('id'=>$comment['school_id']))->find();
            if($school){
                $comment['school_name'] = $school['school_name'];
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
            $this->ajaxReturn(['status'=>0,'info'=>'当前评论不存在']);
        }
    }

    //发布评论
    public function comment_action(){
        if(IS_POST){
            $user_id = intval(I('request.uid'));
            $content = trim(I('post.content'));
            if($content == ''){
                $this->ajaxReturn(['status'=>0,'info'=>'评论信息不能为空']);
            }
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
            }
            $discuss_id = intval(I('post.did'));
            $discuss = M('Discuss')->where(array('id'=>$discuss_id,'status'=>1))->find();
            if(!$discuss){
                $this->ajaxReturn(['status'=>0,'info'=>'当前讨论不存在或已被停用']);
            }
            //获取关键词并过滤
            $effect = 2;
            $where['effect_area'] = array('like',"%$effect%");
            $filter_keyword = M('FilterKeyword')->where($where)->select();
            foreach ($filter_keyword as $k=>$v){
                if(strpos($v['keyword'],$content) !== false){
                    $this->ajaxReturn(['status'=>0,'info'=>"评论失败,评论内容存在非法字符"]);
                }
            }
            $dataInfo = [
                'discuss_id' => $discuss_id,
                'user_id' => $user_id,
                'content' => $content,
                'create_time' => time()
            ];
            if(M('Comment')->add($dataInfo) !== false){
                M('Discuss')->save(array('id'=>$discuss_id,'comment_num'=>$discuss['comment_num']+1,'update_time'=>time()));
                //发送消息
                $this->save_message($discuss['user_id'],$user_id,2,$content);
                //是否获得积分
                $gain_score = $this->whether_score($user_id,2);
                if($gain_score > 0){
                    M('Users')->save(array('id'=>$user_id,'score'=>$user_info['score']+$gain_score));
                    //添加积分记录
                    $this->save_score($user_id,$gain_score,3);
                }
                //发送极光推送
                if($user_id != $discuss['user_id']) {
                    $purposeUser = M('Users')->where(array('id' => $discuss['user_id']))->field('id,push,aurora')->find();
                    if ($purposeUser && $purposeUser['push'] == 1 && !empty($purposeUser['aurora'])) {
                        $this->send_pub($purposeUser['aurora'], $user_info['user_name'] . "评论了您");
                    }
                }
                $this->ajaxReturn(['status'=>1,'info'=>'评论成功','score'=>$gain_score]);
            }else{
                $this->ajaxReturn(['status'=>0,'info'=>'网络错误,请稍后再试']);
            }
        }
    }

    //发布回复
    public function reply_action(){
        if(IS_POST){
            $user_id = intval(I('request.uid'));
            $content = trim(I('post.content'));
            if($content == ''){
                $this->ajaxReturn(['status'=>0,'info'=>'回复信息不能为空']);
            }
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
            }
            $comment_id = intval(I('post.comment_id'));
            $comment = M('Comment')->where(array('id'=>$comment_id))->find();
            if(!$comment){
                $this->ajaxReturn(['status'=>0,'info'=>'当前评论不存在']);
            }
            $discuss = M('Discuss')->where(array('id'=>$comment['discuss_id'],'status'=>1))->find();
            if(!$discuss){
                $this->ajaxReturn(['status'=>0,'info'=>'当前评论所属讨论不存在或已被停用']);
            }
            //获取关键词并过滤
            $effect = 2;
            $where['effect_area'] = array('like',"%$effect%");
            $filter_keyword = M('FilterKeyword')->where($where)->select();
            foreach ($filter_keyword as $k=>$v){
                if(strpos($v['keyword'],$content) !== false){
                    $this->ajaxReturn(['status'=>0,'info'=>"回复失败,回复内容存在非法字符"]);
                }
            }
            $dataInfo = [
                'discuss_id' => $comment['discuss_id'],
                'comment_id' => $comment_id,
                'user_id' => $user_id,
                'content' => $content,
                'create_time' => time()
            ];
            if(M('Reply')->add($dataInfo) !== false){
                M('Comment')->save(array('id'=>$comment_id,'reply_num'=>$comment['reply_num']+1));
                M('Discuss')->save(array('id'=>$discuss['id'],'update_time'=>time()));
                //发送消息
                $this->save_message($comment['user_id'],$user_id,3,$content);
                //是否获得积分
                $gain_score = $this->whether_score($user_id,2);
                if($gain_score > 0){
                    M('Users')->save(array('id'=>$user_id,'score'=>$user_info['score']+$gain_score));
                    //添加积分记录
                    $this->save_score($user_id,$gain_score,3);
                }
                //发送极光推送
                if($user_id != $comment['user_id']) {
                    $purposeUser = M('Users')->where(array('id' => $comment['user_id']))->field('id,push,aurora')->find();
                    if ($purposeUser && $purposeUser['push'] == 1 && !empty($purposeUser['aurora'])) {
                        $this->send_pub($purposeUser['aurora'], $user_info['user_name'] . "回复了您");
                    }
                }
                $this->ajaxReturn(['status'=>1,'info'=>'回复成功','score'=>$gain_score]);
            }else{
                $this->ajaxReturn(['status'=>0,'info'=>'网络错误,请稍后再试']);
            }
        }
    }

    //评论点赞
    public function comment_like(){
        if(IS_POST){
            $user_id = intval(I('request.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
            }
            $comment_id = intval(I('post.comment_id'));
            $comment = M('Comment')->where(array('id'=>$comment_id))->find();
            if(!$comment){
                $this->ajaxReturn(['status'=>0,'info'=>'当前评论不存在']);
            }
            //判断是否已经点赞
            $like = M('UsersComment')->where(array('user_id'=>$user_id,'comment_id'=>$comment_id,'type'=>1))->find();
            if($like){//已经点过赞
                $this->ajaxReturn(['status'=>0,'info'=>'你已点赞过该评论,不能重复点赞哦']);
            }else{//点赞
                $dataInfo = [
                    'user_id' => $user_id,
                    'comment_id' => $comment_id,
                    'type' => 1,
                    'create_time' => time()
                ];
                $result = M('UsersComment')->add($dataInfo);
                if($result !== false){
                    M('Comment')->save(array('id'=>$comment_id,'like_num'=>$comment['like_num']+1));
                    $discuss = M('Discuss')->where(array('id'=>$comment['discuss']))->find();
                    $like_num = $discuss['like_num']+1;
                    if($discuss){
                        M('Discuss')->save(array('id'=>$discuss['id'],'like_num'=>$like_num));
                    }
                    //发送消息
                    $this->save_message($comment['user_id'],$user_id,1);
                    $this->ajaxReturn(['status'=>1,'info'=>'点赞成功','like_num'=>$like_num,'cid'=>$comment_id]);
                }
            }
            $this->ajaxReturn(['status'=>-1,'info'=>'网络错误,请稍后再试']);
        }
    }

    //发布讨论页
    public function publish(){
        //获取分类
        $category = M('category')->where(array('status'=>1))->field('id,name')->order('create_time desc')->select();
        //获取标签
        $label = M('Label')->where(array('status'=>1))->order('listorder asc')->field('id,name')->select();
        $this->ajaxReturn(['status'=>1,'category'=>$category,'label'=>$label]);
    }

    //发布讨论
    public function discuss_publish(){
        if(IS_POST){
            $user_id = intval(I('request.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
            }
            $name = trim(I('post.name'));
            $content = trim(I('post.content'));
            $image = I('post.image');
            if($content == ''){
                $this->ajaxReturn(['status'=>0,'info'=>'内容不能为空']);
            }
            if(!empty($image)){
                $images = explode(',',$image);
                if(count($images) > 5){
                    $this->ajaxReturn(['status'=>0,'info'=>'发布失败,图片上限为5张']);
                }
            }
            $school_id = intval(I('post.sid'));
            $school = M('School')->where(array('id'=>$school_id,'status'=>0,'type'=>1))->find();
            if(!$school){
                $this->ajaxReturn(['status'=>0,'info'=>'发布失败,该大学讨论区已关闭']);
            }
            //获取关键词并过滤
            $effect = 3;
            $where['effect_area'] = array('like',"%$effect%");
            $filter_keyword = M('FilterKeyword')->where($where)->select();
            foreach ($filter_keyword as $k=>$v){
                if(strpos($v['keyword'],$content) !== false){
                    $this->ajaxReturn(['status'=>0,'info'=>"发布失败,发布内容存在非法字符"]);
                }
                if(!empty($name)){
                    if(strpos($v['keyword'],$name) !== false){
                        $this->ajaxReturn(['status'=>0,'info'=>"发布失败,发布标题存在非法字符"]);
                    }
                }
            }
            $category_id = I('post.cid',0,'intval');
            if(empty($category_id)){
                $this->ajaxReturn(['status'=>0,'info'=>'请选择分类']);
            }
            $label = I('post.label');
            $fit_number = 0;//契合度
            //获取用户的标签信息
            if(!empty($label)){
                $users_label = M('UsersLabel')->where(array('user_id'=>$user_id))->select();
                if(count($users_label) > 0){
                    $label_array = array();
                    foreach ($users_label as $k=>$v){
                        $label_array[] = $v['label_id'];
                    }
                    $post_label = explode(',',$label);
                    foreach ($post_label as $v){
                        if(in_array($v,$label_array)){
                            $fit_number = $fit_number+1;
                        }
                    }
                }
            }
            $dateInfo = [
                'user_id' => $user_id,
                'school_id' => $school['id'],
                'name' => $name,
                'content' => $content,
                'image' => $image,
                'category_id' => $category_id,
                'label' => $label,
                'create_time' => time(),
                'update_time' => time(),
                'fit' => $fit_number
            ];
            $discuss_id = M('Discuss')->add($dateInfo);
            if($discuss_id !== false){
                //添加讨论与标签关联
                if(!empty($label)){
                    $label_list = explode(',',$label);
                    $label_connect = array();
                    foreach ($label_list as $v){
                        $label_connect[] = [
                            'discuss_id' => $discuss_id,
                            'label_id' => $v
                        ];
                    }
                    M('DiscussLabel')->addAll($label_connect);
                }
                //是否获得积分
                $gain_score = $this->whether_score($user_id,1);
                if($gain_score > 0){
                    M('Users')->save(array('id'=>$user_id,'score'=>$user_info['score']+$gain_score));
                    //添加积分记录
                    $this->save_score($user_id,$gain_score,2);
                }
                $this->ajaxReturn(['status'=>1,'info'=>'讨论发布成功','score'=>$gain_score]);
            }else{
                $this->ajaxReturn(['status'=>-1,'info'=>'网络错误,请稍后再试']);
            }
        }
    }

    //用户profile页
    public function profile(){
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 20;
        $start = ($now_page - 1) * $pageSize;
        //查询
        $user_id = intval(I('request.uid'));
        $to_user_id = intval(I('request.to_uid'));
        $user_info = $this->user_info($to_user_id);//获取用户信息
        if ($user_info == false) {
            $this->ajaxReturn(['status' => 0, 'info' => '当前用户不存在或禁用']);
        }

        //讨论数量
        $where_discuss['user_id'] = array('eq',$to_user_id);
        $where_discuss['status'] = array('eq',1);
        $total_count_dis = M('Discuss')->where($where_discuss)->count();
        //评论数量
        $where_comment['user_id'] = array('eq',$to_user_id);
        $total_count_com = M('Comment')->where($where_comment)->count();

        //获取类型
        $type = I('request.type',1,'intval');
        if($type == 1){//获取讨论
            $total_page = ceil($total_count_dis/$pageSize);
            $discuss = M('Discuss')->where($where_discuss)->order('create_time desc')->limit($start,$pageSize)->select();
            foreach ($discuss as $k=>$v){
                $collect = M('UsersCollect')->where(array('user_id'=>$user_id,'collect_id'=>$v['id'],'type'=>1))->find();
                if($collect){
                    $discuss[$k]['collect'] = 1;
                }else{
                    $discuss[$k]['collect'] = 0;
                }
                if(!empty($v['image'])) {
                    $images = explode(',', $v['image']);
                    foreach ($images as $x => $y) {
                        $images[$x] = sp_get_image_preview_url($y);
                    }
                }else{
                    $images = array();
                }
                $discuss[$k]['image'] = $images;
                $time_distance = time()-$v['create_time'];
                if($time_distance >= 3600*24*5){
                    $discuss[$k]['time_ago'] = date('Y-m-d',$v['create_time']);
                }elseif ($time_distance >= 3600*24){
                    $discuss[$k]['time_ago'] = floor($time_distance/86400).'天前';
                }elseif ($time_distance >= 3600){
                    $discuss[$k]['time_ago'] = floor($time_distance/3600).'小时前';
                }else{
                    $discuss[$k]['time_ago'] = floor($time_distance/60).'分钟前';
                }
                if(empty($v['name'])){
                    $content = strip_tags(htmlspecialchars_decode($v['content']));
                    $discuss[$k]['name'] = mb_substr($content, 0, 30,"utf-8");
                }
            }
            $this->ajaxReturn(['status'=>1,'user'=>$user_info,'discuss'=>$discuss,'total_page'=>$total_page,'dis_count'=>$total_count_dis,'com_count'=>$total_count_com]);
        }else{//获取评论
            $total_page = ceil($total_count_com/$pageSize);
            $comment = M('Comment')->where($where_comment)->order('create_time desc')->limit($start,$pageSize)->select();
            foreach ($comment as $k=>$v){
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
                $comment[$k]['reply'] = $reply;
            }
            $this->ajaxReturn(['status'=>1,'user'=>$user_info,'comment'=>$comment,'total_page'=>$total_page,'dis_count'=>$total_count_dis,'com_count'=>$total_count_com]);
        }
    }

    //消息列表页
    public function message(){
        $user_id = intval(I('request.uid'));
        //分页
        $page = I('request.page',1,'intval');
        ($page > 1) ? $now_page = $page : $now_page = 1;
        $pageSize = 20;
        $start = ($now_page - 1) * $pageSize;
        $where['user_id'] = array('eq',$user_id);
        $userMessage = M('UsersMessage');
        $total_count = $userMessage->where($where)->count();
        $total_page = ceil($total_count/$pageSize);
        $message = $userMessage->where($where)->order('`read` asc,create_time desc')->limit($start,$pageSize)->select();

        $where['read'] = array('eq',0);
        $unread = $userMessage->where($where)->count();
        foreach ($message as $k=>$v){
            if(!empty($v['from_avatar'])){
                $message[$k]['from_avatar'] = sp_get_image_preview_url($v['from_avatar']);
            }
            if($v['read'] == 0){
                $unread--;
                $userMessage->save(array('id'=>$v['id'],'read'=>1));
            }
        }
        $this->ajaxReturn(['status'=>1,'message'=>$message,'total_page'=>$total_page,'unread'=>$unread]);
    }
}