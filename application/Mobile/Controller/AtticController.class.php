<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/29
 * Time: 8:55
 */

namespace Mobile\Controller;


class AtticController extends BaseController
{
    //笔记收藏操作
    public function note_collect(){
        if(IS_POST){
            $user_id = intval(I('request.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
            }
            $note_id = intval(I('post.nid'));
            $note = M('Scripture')->where(array('id'=>$note_id,'status'=>0))->find();
            if(!$note){
                $this->ajaxReturn(['status'=>0,'info'=>'当前笔记不存在或已被禁用']);
            }
            //判断是否已经收藏
            $collect = M('UsersCollect')->where(array('user_id'=>$user_id,'collect_id'=>$note_id,'type'=>2))->find();
            if($collect){//取消收藏
                $result = M('UsersCollect')->where(array('id'=>$collect['id']))->delete();
                if($result !== false){
                    M('Scripture')->save(array('id'=>$note_id,'collect'=>$note['collect']-1));
                    $this->ajaxReturn(['status'=>1,'info'=>'取消收藏成功']);
                }
            }else{//收藏
                $dataInfo = [
                    'user_id' => $user_id,
                    'collect_id' => $note_id,
                    'type' => 2,
                    'create_time' => time()
                ];
                $result = M('UsersCollect')->add($dataInfo);
                if($result !== false){
                    M('Scripture')->save(array('id'=>$note_id,'collect'=>$note['collect']+1));
                    $this->ajaxReturn(['status'=>1,'info'=>'收藏成功']);
                }
            }
            $this->ajaxReturn(['status'=>-1,'info'=>'网络错误,请稍后再试']);
        }
    }

    //兑换商品页
    public function exchange(){
        $user_id = intval(I('request.uid'));
        $user_info = $this->user_info($user_id);//获取用户信息
        if($user_info == false){
            $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
        }
        $goods_id = intval(I('request.gid'));
        $goods = M('Gift')->where(array('id'=>$goods_id,'status'=>0))->field('id,gift_name,price,surplus,cover_img,type')->find();
        if(!$goods){
            $this->ajaxReturn(['status'=>0,'info'=>'该商品不存在或已下架']);
        }
        if($goods['type'] != 1){
            $this->ajaxReturn(['status'=>0,'info'=>'只能兑换积分商城商品']);
        }
        if($goods['surplus'] < 1){
            $this->ajaxReturn(['status'=>0,'info'=>'该商品库存不足']);
        }
        if(!empty($goods['cover_img'])){
            $goods['cover_img'] = sp_get_image_preview_url($goods['cover_img']);
        }
        $this->ajaxReturn(['status'=>1,'goods'=>$goods,'score'=>$user_info['score']]);
    }

    //确认兑换商品
    public function exchange_action(){
        if(IS_POST){
            $user_id = intval(I('request.uid'));
            $user_info = $this->user_info($user_id);//获取用户信息
            if($user_info == false){
                $this->ajaxReturn(['status'=>0,'info'=>'当前用户不存在或禁用']);
            }
            $goods_id = intval(I('post.gid'));
            $goods = M('Gift')->where(array('id'=>$goods_id,'status'=>0))->field('id,gift_name,price,surplus,cover_img,type')->find();
            if(!$goods){
                $this->ajaxReturn(['status'=>0,'info'=>'该商品不存在或已下架']);
            }
            if($goods['type'] != 1){
                $this->ajaxReturn(['status'=>0,'info'=>'只能兑换积分商城商品']);
            }
            if($goods['surplus'] < 1){
                $this->ajaxReturn(['status'=>0,'info'=>'该商品库存不足']);
            }
            $username = I('post.username');
            $mobile = I('post.mobile');
            $area = I('post.area');
            $address = I('post.address');
            if(empty($username) || empty($mobile) || empty($area) || empty($address)){
                $this->ajaxReturn(['status'=>0,'info'=>'请填写完整收货信息']);
            }
            if(preg_match('/^1[3456789][0-9]{9}$/',$mobile) < 1){
                $this->ajaxReturn(['status'=>0,'info'=>'请输入正确的手机号']);
            }
            $number = I('post.num',1,'intval');
            if($number < 1){
                $number = 1;
            }
            if($number > $goods['surplus']){
                $this->ajaxReturn(['status'=>0,'info'=>'购买数量已超出该商品库存']);
            }
            $need_score = $number*$goods['price'];
            if($need_score > $user_info['score']){
                $this->ajaxReturn(['status'=>0,'info'=>'您的积分不足,兑换失败']);
            }
            $fp = fopen("lock.txt", "w+");
            if(flock($fp,LOCK_EX)){//锁定当前指针
                //..处理订单
                //生成订单
                $dataInfo = [
                    'gift_id' => $goods['id'],
                    'gift_name' => $goods['gift_name'],
                    'gift_img' => $goods['cover_img'],
                    'number' => $number,
                    'price' => $need_score,
                    'user_name' => $username,
                    'address' => $area.$address,
                    'mobile' => $mobile,
                    'user_id' => $user_id,
                    'create_time' => time(),
                ];
                $result_id = M('OrderList')->add($dataInfo);
                if($result_id !== false){
                    //减少商品库存
                    M('Gift')->save(array('id'=>$goods_id,'surplus'=>$goods['surplus']-$number));
                    //修改用户积分
                    M('Users')->save(array('id'=>$user_id,'score'=>$user_info['score']-$need_score));
                    //添加积分记录
                    $this->save_score($user_id,$need_score,5);
                    flock($fp,LOCK_UN);
                    fclose($fp);
                    $this->ajaxReturn(['status'=>1,'info'=>'兑换成功']);
                }else{
                    flock($fp,LOCK_UN);
                    fclose($fp);
                    $this->ajaxReturn(['status'=>-1,'info'=>'兑换失败,网络错误请稍后再试']);
                }
            }
        }
    }
}