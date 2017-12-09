<?php
namespace app\admin\controller;
use app\admin\controller\AdminBase;
use app\admin\Model\Income;
use app\admin\Model\Order;
use extend\Upload;
use think\Request;
use think\Session;
use think\Db;
/**
 * 数据查询控制器
 * @author  wanggang
 * @version 2017/8/30
 */
class Data extends AdminBase{
    public function defaluts(){
        $this->redirect(url('User/index'));
    }
    /**
     * 财务结算
     */
    public function index(){
    	$lists = model('Income')->select_income(input());
    	return view('',['lists'=>$lists]);
    } 
    /**
     * 借款列表
     */
    public function borrow_list(){
    	$lists = model('order')->select_order(input());
    	return view('',['lists'=>$lists]);
    }
    /**
     * 借款协议
     */
    public function protocol(){
    	$protocol_info = model('order')->where('id',input('order_id'))->find();
    	$config = model('config')->where(['group'=>'procotol'])->select();
    	resultToArray($config);
    	if($protocol_info){
    		$protocol_info = $protocol_info->toArray();
    		$invest_user   = model('member')->where(['user_id'=>$protocol_info['invest_id']])->find();
            if($invest_user){
                $invest_user = $invest_user->toArray();
                $protocol_info['invest_user_name']     = $invest_user['realname'];
                $protocol_info['invest_user_idcard']   = $invest_user['idcard'];
                $protocol_info['invest_user_tel']      = $invest_user['mobile'];
            }else{
                $protocol_info['invest_user_name']     = '--';
                $protocol_info['invest_user_idcard']   = '--';
                $protocol_info['invest_user_tel']      = '--';
            }
    		$borrow_user   = model('member')->where(['user_id'=>$protocol_info['borrow_id']])->find()->toArray();
    		$protocol_info['borrow_user_name']     = $borrow_user['realname'];
    		$protocol_info['borrow_user_idcard']   = $borrow_user['idcard'];
    		$protocol_info['borrow_user_tel']      = $borrow_user['mobile'];
    		$protocol_info['PROTOCOL_NAME'] = $config[0]['config_value'];
    		$protocol_info['PROTOCOL_TEL']  = $config[1]['config_value'];
    		$protocol_info['PROTOCOL_CONTENT'] = $config[2]['config_value'];
    	}
    	return view('',['protocol'=>$protocol_info]);
    }
    /**
     * 借款视频
     */
    public function video(){
        // dump(input('order_id'));
    	$video = model('order')->where('id',input('order_id'))->value('video');
        // dump($video);
    	return view('',['video'=>$video]);
    }
    /**
     * 投资列表
     */
    public function invest_list(){
    	$lists = model('order')->select_order(input(),array('is_done'=>1));
    	return view('',['lists'=>$lists]);
    }
    /**
     * 催收令
     */
    public function collection(){
        $lists = model('collection')->select_collection(input());
        // dump($lists);die;
        return view('',['lists'=>$lists]);
    }
    /**
     * 催收信息
     */
    public function collection_mobile(){
        $borrow_id = model('order')->where(['id'=>input('order_id')])->value('borrow_id');
        $user = model('member')->where(['user_id'=>$borrow_id])->find();
        if($user){
            $user = $user->toArray();
        }else{
            $user = array();
        }
        $relate = db('related')->where(['user_id'=>$user['user_id']])->select();
        $mobile = $user['mobile']?:'';
        $task_id = $user['taskid']?:'';
        if($mobile && $task_id){
            $mobile =$this->get_mobiles($mobile,$task_id);
        }else{
            $mobile = array();
        }
        return view('',['user'=>$user,'relate'=>$relate,'mobile'=>$mobile]);
    }
    /**
     * 获取常用联系人手机号
     * @param  [sring] $mobile  [手机号]
     * @param  [string] $task_id [手机运营商认证返回的，运营商认证的唯一标识]
     * @return [array]          [通话次数由高到低的手机号数组]
     */
    public function get_mobiles($mobile,$task_id){
        $url = 'https://api.51datakey.com/carrier/v3/mobiles/'.$mobile.'/mxdata?task_id='.$task_id;
        // $url = 'https://api.51datakey.com/carrier/v3/mobiles/13510254650/mxdata?task_id=e98a6f90-c2c1-11e7-a013-00163e13e22b';//测试账号
        $header = ['Content-Type: application/json; charset=utf-8',
                    'Authorization:token 25136488e83f4b38b534fac141e8ffd6'
                ];
        $res = curl_send_get($url,$header);
        if($res['calls']){
            //取到所有通话记录电话号码，结果为二维数组
            $results = array();
            foreach ($res['calls'] as $key => $value) {
               $results[$key] = array_column($value['items'],'peer_number');
            }
            //二位数组电话号变一位数组
            $result = [];
            array_walk_recursive($results, function($value) use (&$result) {
                array_push($result, $value);
            });
            $result = array_count_values($result);  // 统计数组中所有值出现的次数
            arsort($result);
            $result = array_slice($result,0,10,true);
            $mobile = array_keys($result);
        }else{
            $mobile = array();
        }
        return $mobile;
    }
    /**
     * 改变催收令状态
     */
    public function set_collection_status(){
        if(request()->isAjax()){
            $data = input();
            $obj =$this->setStatus('collection',$data['status'],$data['id'],'id','status');
            if(1 == $obj->code){
                $obj->setApi('url',input('location'))->apiEcho();
            }else{
                $obj->setApi('url',0)->apiEcho();
            }
        }
    }
    /**
     * 删除催收令
     */
    public function del_collection(){
        if(request()->isAjax()){
            $time = time();
            $data = input();
            $obj =$this->setStatus('collection',$time,$data['id'],'id','delete_time');
            if(1 == $obj->code){
                $obj->setApi('url',input('location'))->apiEcho();
            }else{
                $obj->setApi('url',0)->apiEcho();
            }
        }
    }
}