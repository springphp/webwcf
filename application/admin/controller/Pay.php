<?php
namespace app\admin\controller;
use app\admin\controller\AdminBase;
use app\admin\model\Bankcard;
use extend\Upload;
use think\Db;
use think\File;
/**
* 支付管理
* @author iwater <[email address]>
* @version 2017/08/29 [description]
*/
class Pay extends AdminBase
{
	public function defaults(){
		return $this->redirect(url('Pay/card_list'));
	}

	public function card_list(){
		$cards = db('banks')->field('id,card_vcode,card_name,card_log')->select();
		return view(['cards'=>$cards]);
	}

	public function my_card(){
		$data = input();
        _pageconfig(5);
		$cards = model('Bankcard')->select_card($data);
        
        $banklist = db('banks')->column('card_name');

		return view(['cards'=>$cards,'create_name'=>$_SESSION['admin']['user']['account'],'banks'=>$banklist]);
	}

	public function add(){
		if(request()->isAjax()){
			$data = input();
			if(isset($data['uploadImg'])){//判断是否有图片
                $year = date('Y/m',time());
                $re  = Upload::uploadImg("bank/$year")->getInfo();
                $bank_img["icon"] = "/bank/".$year."/".$re[0];
                unset($data['uploadImg']);
                $data = $data+$bank_img;
            }else{
                Api()->setApi('msg','请选择上传图标！')->ApiError();
            }
            $data['icon'] = $this->getUrl($data['icon']);//url处理
            $data['user_id'] = session('user.admin_id');

            $rs = model('Bankcard')->add_card($data);
            if($rs>0){
            	Api()->setApi('url',input('location'))->ApiSuccess($rs);
            }else{
            	Api()->setApi('msg',$rs)->setApi('url',0)->ApiError();
            }
		}else{
			$banks = config('bank_config');
			return view(['banks'=>$banks]);
		}
	}

	public function edit(){
		if(request()->isAjax()){
			$data = input();
			if(isset($data['uploadImg'])){//判断是否有图片
                $year = date('Y/m',time());
                $re  = Upload::uploadImg("bank/$year")->getInfo();
                $bank_img["icon"] = "/bank/".$year."/".$re[0];
                unset($data['uploadImg']);
                $data = $data+$bank_img;
                $data['icon'] = $this->getUrl($data['icon']);//url处理
                if(!empty($data['img'])){
                    // unlink('./public/upload/'.$data['img']);
                    unset($data['img']);
                }
            }else{
            	if(empty($data['img'])){
            		Api()->setApi('msg','请选择上传图标！')->setApi('url',0)->ApiError();
            	}else{
            		unset($data['icon']);unset($data['img']);
            	}
            }

            $rs = model('Bankcard')->edit_card($data);
            if($rs>0){
            	Api()->setApi('url',input('location'))->ApiSuccess($rs);
            }else{
            	Api()->setApi('msg',$rs)->setApi('url',0)->ApiError();
            }
		}else{
			$id = input('id');
			$cards = db('Bankcard')->where("id = {$id}")->find();
			$banks = config('bank_config');
			return view(['cards'=>$cards,'banks'=>$banks]);
		}
	}
	 /**
     * 删除银行卡
     */
    public function del(){
      if(request()->isAjax()){
        $time = time();
        $data = input();
        $obj =$this->setStatus('Bankcard',$time,$data['id'],'id','delete_time');
        if(1 == $obj->code){
            $obj->setApi('url',input('location'))->apiEcho();
        }else{
            $obj->setApi('url',0)->apiEcho();
        }
      }
    }

    /**
     * 改变状态：启用|禁用
     */
    public function change_status(){
        $obj =$this->setStatus('Bankcard',input('status'),input('id'),'id');
    	if(1 == $obj->code){
    		$obj->setApi('url',input('location'))->apiEcho();
    	}else{
    		$obj->apiEcho();
    	}
    }

    
	
}
