<?php
namespace app\admin\controller;
use app\admin\controller\AdminBase;
use app\admin\model\Bankcard;
use extend\Upload;
use think\Db;
use think\File;
/**
* banner管理
*/
class Banner extends AdminBase{
	//列表
	public function banner_list(){
		$list = model('banner')->select_banner();
		return view(['list'=>$list]);
	}
	//新增
	public function add(){
		if(request()->isAjax()){
			$data = input();
			if(isset($data['uploadImg'])){//判断是否有图片
                $year = date('Y/m',time());
                $re  = Upload::uploadImg("banner/$year")->getInfo();
                $bank_img["image"] = "/banner/".$year."/".$re[0];
                unset($data['uploadImg']);
                $data = $data+$bank_img;
            }else{
                Api()->setApi('msg','请选择上传图标！')->ApiError();
            }
            $data['image'] = $this->getUrl($data['image']);//url处理
            // $data['user_id'] = session('user.admin_id');

            $rs = model('Banner')->add_banner($data);
            if($rs>0){
            	Api()->setApi('url',input('location'))->ApiSuccess($rs);
            }else{
            	Api()->setApi('msg',$rs)->setApi('url',0)->ApiError();
            }
		}else{
			// $banks = config('bank_config');
			return view();
		}
	}
	//编辑
	public function edit(){
		if(request()->isAjax()){
			$data = input();
			if(isset($data['uploadImg'])){//判断是否有图片
                $year = date('Y/m',time());
                $re  = Upload::uploadImg("banner/$year")->getInfo();
                $bank_img["image"] = "/banner/".$year."/".$re[0];
                unset($data['uploadImg']);
                $data = $data+$bank_img;
                $data['image'] = $this->getUrl($data['image']);//url处理
                if(!empty($data['image'])){
                    // unlink('./public/upload/'.$data['img']);
                    unset($data['image']);
                }
            }else{
            	if(empty($data['image'])){
            		Api()->setApi('msg','请选择上传图标！')->setApi('url',0)->ApiError();
            	}else{
            		unset($data['image']);unset($data['img']);
            	}
            }

            $rs = model('banner')->edit_banner($data);
            if($rs>0){
            	Api()->setApi('url',input('location'))->ApiSuccess($rs);
            }else{
            	Api()->setApi('msg',$rs)->setApi('url',0)->ApiError();
            }
		}else{
			$id = input('id');
			$banner = db('banner')->where("id = {$id}")->find();
			return view(['banner'=>$banner]);
		}
	}
	//删除
    public function del(){
	    if(request()->isAjax()){
	        $time = time();
	        $data = input();
	        $obj =$this->setStatus('banner',$time,$data['id'],'id','delete_time');
	        if(1 == $obj->code){
	            $obj->setApi('url',input('location'))->apiEcho();
	        }else{
	            $obj->setApi('url',0)->apiEcho();
	        }
	    }
    }
}