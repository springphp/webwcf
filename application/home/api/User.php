<?php
namespace app\home\api;
use app\home\api\BaseApi;
use think\Session;
use think\Validate;
use extend\Encrypt;
use extend\Upload;
use think\Db;
use think\File;
use think\Cache;
use think\Request;
use extend\IPsPay;

/**
* 用户接口
* @author iwater <[email address]>
* @version 2017/09/01 [description]
*/
class User extends BaseApi
{
	/*
     * 登录接口
     * @param  string  username(mobile/realname)
     * @param  string  password
     * @return [type] [description]
     */
	public function login()
    {
        $this->destory_login();//先清空登录记录
        // if(session('kwd_app_islogin')) Api()->setApi('msg','您已登录！')->ApiError(['login_status'=>1]);       
        $data = input();extract($data);
        $validate = new Validate([
            'username|用户名'    => 'require',
            'password|密码'       => 'require|length:6,18'
        ]);

        if (!$validate->check($data)) Api()->setApi('msg',$validate->getError())->ApiError();
        //验证登录名称类型
        $field = $this->getUserType($username);
        //查询数据库
        $userinfo = model('member')->find_member('',[$field=> $username]);
        
        //验证用户是否注册
        if ( $username != $userinfo['mobile'] ) {
            Api()->setApi('msg','用户未注册')->ApiError();
        }

        $old_password = Encrypt::authcode($userinfo['password'],'DECODE');//匹配密码
        if($password == $old_password){
            //存session
            session('kwd_app_islogin',$userinfo['user_id']);
            unset($userinfo['password']);
            session('kwd_app_user',$userinfo);
            //设置返回值
            if($userinfo['role'] == 1) {
                $is_invest_status =200; //如果是投资人，返回200
            }else{
                $is_invest_status =400;
            }
            // dump(session_id());
            $session_ids = md5('kwd_app_sign?id=333'.time()).session_id();
            $login_log = [
                'user_id'       => $userinfo['user_id'],
                'login_dev'     => Request::instance()->isMobile()?2:1,
                'session_id'    => session_id(),
                'last_login_ip' => get_client_ip(),
                'type'          => 1
            ];
            
            //避免重复记录
            $sid = model('loginlog')->column('session_id');
            if( !in_array( $login_log['session_id'],$sid ) ) {
                try{
                     model('loginlog')->add_log($login_log); //写入登录日志
                }catch (\Exception $e) {
                    Api()->setApi('msg',$e->getMessage())->ApiError();
                }
            }

            $response = [
                'user_id'=>$userinfo['user_id'],
                'realname'=>$userinfo['realname']?:$userinfo['mobile'],
                'mobile'=>$userinfo['mobile'],
                'is_invest_status'=>$is_invest_status,
                'uid'=> $session_ids.substr(md5('kwd?id=3&user=jack&sex=3'),0,8),
                'headstr'=> rand(0,9).substr(md5('kwd?id=3&user=jack'),0,18).time(),
                'login_time'=>date('Y-m-d')
            ];

            Api()->setApi('msg','登录成功')->ApiSuccess($response);
        }else{
            Api()->setApi('msg','用户名或密码错误')->ApiError();
        }
    }

    /*
     * 注册接口
     * @param  string  mobile
     * @param  string  code 验证码
     * @param  string  password
     * @param  string  realname 推荐人(可选参数)
     * @return [type] [description]
     */
    public function register(){
        $data = input();
        $validate = new Validate([
            'mobile|手机号码'    => 'require|unique:member|length:11',
            // 'realname'  => 'require',
            // 'code|验证码'  => 'require',
            'password|密码'  => 'require|length:6,18'
        ]);
        if (!$validate->check($data)) Api()->setApi('msg',$validate->getError())->ApiError();
        if (!preg_match('/(^(13\d|15[^4\D]|17[13678]|18\d)\d{8}|170[^346\D]\d{7})$/', $data['mobile'])) 
            Api()->setApi('msg','手机号格式错误')->ApiError();

        //处理验证码
        // $this->send(); 此处单独调发送短信验证码的接口了
        $this->checkCode(input('code'));
        //验证推荐人是否是会员
        $register_data = [
            'mobile'      => $data['mobile'],
            'password'    => Encrypt::authcode($data['password'],'ENCODE'), //加密,
        ];

        //推荐人处理 逻辑
        if(isValue($data,'realname')){
            $user_id = model('member')->where(['mobile'=>$data['realname']])->value('user_id');
            if( empty($user_id) ) Api()->setApi('msg','推荐人不合法')->ApiError();
            //推荐成功 记录数据
            // $this->add_user_income_info($user_id);
            //处理注册信息
            $register_data = array_merge($register_data,['pid'=>$user_id]);
        }

        $re = model('member')->add_member($register_data);
        if($re>0){
            //TODO 平台给邀请人10元钱 转账逻辑第三方处理
            Api()->setApi('msg','注册成功')->ApiSuccess($re);
        }else{
            Api()->setApi('msg','注册失败')->ApiError();
        }
    }

    //退出登录
    public function loginout(){
        //写入日志
        $sid = $this->getMd5Code();
        $log_id = model('loginlog')->where('session_id',$sid)->value('id');
        if( $log_id ) {
            $data = [
                'id'            => $log_id,
                'delete_time'   => time(),
                'type'          => 2
            ];
            try{
                model('loginlog')->edit_log($data);
            }catch (\Exception $e) {
                Api()->setApi('msg',$e->getMessage())->ApiError();
            }
        }
        //清楚session    
        session('kwd_app_islogin',null);
        session('kwd_app_user',null);
        // cookie('PHPSESSID',null);
        
        Api()->setApi('msg','退出登录成功')->ApiSuccess();
    }

    /*
     * 修改密码 接口
     * @param  string  mobile
     * @param  string  code 验证码
     * @param  string  password
     * @return [type] [description]
     */
    public function set_pwd(){
        $data = input();extract($data);
        $validate = new Validate([
            'mobile|手机号码'    => 'require',
            'password|密码'      => 'require|length:6,18'
        ]);
        if (!$validate->check($data)) Api()->setApi('msg',$validate->getError())->ApiError();
        //处理验证码
        // $this->send(); //此处单独调发送短信验证码的接口了sendsms
        $this->checkCode(input('code'));

        $userinfo = model('member')->getUser(['mobile'=>$mobile]);
        if(!$userinfo){
            Api()->setApi('msg','用户不存在')->ApiError();
        }

        //处理数据
        $data = ['user_id'=>$userinfo['user_id'],'password'=>Encrypt::authcode($password,'ENCODE')];
        $rs = model('member')->edit_member($data);
        if($rs>0){
            Api()->setApi('msg','修改成功')->ApiSuccess($rs);
        }else{
            Api()->setApi('msg',$rs)->ApiError();
        }
    }

    /**
     * 发送短信接口
     *@param   $[mobile] [手机号码]
     */
    public function send(){
        $tel = input('mobile');
        if(!$tel) Api()->setApi('msg','手机号不能为空')->ApiError();
        $configs = db('config')->where("config_mark = 'WEB_VERIFY'")->find();
        $update_time = $configs['update_time'];
        if(time()-$update_time>60 || !$configs['config_value']){
            $this->send_sms($tel,$this->set_code(6,'WEB_VERIFY'));//发送短信验证码
        }

    }

    /**
    * 是否验证
    * @param  [int] $user_id [用户Id]
    * @param  [int] $type [1:实名认证2：人际信息认证3：手机认证4：芝麻授信认证]
    * @return [str] status      [description]
    */
    public function check_center($data){
        $user_id = $this->get_userid( input('user_id','','trim') );
        if(!isValue($data,'type')) $data['type'] = 1;
        if($data['type'] ==1){
            $field = 'status';
        }elseif ($data['type'] ==2) {
           $field = 'relative_status';
        }elseif ($data['type'] ==3) {
            $field = 'mobile_status';
        }elseif ($data['type'] ==4) {
            $field = 'zhima_status';
        }
        $where['user_id'] = $user_id;
        $rs = model('member')->find_member($field,$where);
        if($rs[$field] ==2){
            $msg = '已认证';
        }else{
            $msg = '未认证';
        }
        Api()->setApi('msg',$msg)->ApiSuccess($rs);
    }

   /**
    * 认证中心
    * @param  [int] $user_id [用户Id]
    * @return [array] data      [description]
    */
    public function is_pass($data){
        $user_id = $this->get_userid( input('user_id','','trim') );
        $where['user_id'] = $user_id;
        $memberinfo = model('member')->find_member('*',$where);
        //人际信息
        $field = 'realname,relation,mobile';
        $order = 'create_time desc';
        $relative_data = model('related')->get_related_list($field,$where,$order); 
        
        //实名认证
        $idCard_data = [
            'Frontview_pic'     => Request::instance()->domain().'/public/upload/'.$memberinfo['idcard_z'],
            'bgview_pic'        => Request::instance()->domain().'/public/upload/'.$memberinfo['idcard_f'],
            'holdview_pic'      => Request::instance()->domain().'/public/upload/'.$memberinfo['idcard_s']
        ];

        //将https 转 http
        $idCard_data['Frontview_pic'] = $this->getHttp( $idCard_data['Frontview_pic'] );
        $idCard_data['bgview_pic'] = $this->getHttp( $idCard_data['bgview_pic'] );
        $idCard_data['holdview_pic'] = $this->getHttp( $idCard_data['holdview_pic'] );

        if(!$memberinfo['idcard_z']) $idCard_data['Frontview_pic'] = '';
        if(!$memberinfo['idcard_f']) $idCard_data['bgview_pic'] = '';
        if(!$memberinfo['idcard_s']) $idCard_data['holdview_pic'] = '';
        $renzheng_status = [
            'idCard_status'     => $memberinfo['status'],
            'relative_status'   => $memberinfo['relative_status'],
            'mobile_status'     => $memberinfo['mobile_status'],
            // 'zhima_status'      => $memberinfo['zhima_status']
        ];
        
        $status = in_array(1,$renzheng_status)?1:2;
        if($renzheng_status['idCard_status'] == 1) $idCard_data = [];
        if($renzheng_status['relative_status'] == 1) $relative_data = [];
        $check_data = [
            'status'=>$status,
            'renzheng_status'=>$renzheng_status,
            'renzheng_data'=> ['idCard_data'=>$idCard_data,'relative_data'=>$relative_data],
        ];
        Api()->setApi('msg','')->ApiSuccess($check_data);
    }

    //人际信息认证
    public function check_relation($data){
        unset($data['act']);
        $user_id = $this->get_userid( input('user_id','','trim') );
        if(empty($data['relative_data'])) Api()->setApi('msg','请输入合法参数')->ApiError();

        foreach ($data['relative_data'] as $key => &$value) {
            if(empty($value['realname']))  Api()->setApi('msg','姓名不能为空')->ApiError();
            if(empty($value['relation']))  Api()->setApi('msg','请选择与用户关系类型')->ApiError();
            if(empty($value['mobile']))    Api()->setApi('msg','手机号不能为空')->ApiError();
            $value['mobile'] =  preg_replace('# #', '', $value['mobile']);
            $value['user_id'] = $user_id;
        }
        $rs = model('related')->add_relations($data['relative_data']);
        if($rs>0){
            $relative_status = ['relative_status'=>2,'user_id'=>$user_id];
            model('member')->edit_member($relative_status);
            Api()->setApi('msg','添加成功')->ApiSuccess(['status'=>200]);
        }else{
            Api()->setApi('msg','认证失败')->ApiError();
        }
    }

    /**
     * 实名认证 file 测试
     * @param  [string] $imageName [description]
     * @param  string $path      [description]
     * @return [type]            [description]
     */
    public function check_users($filename='',$uploadPath='idcard')
    {
        $file = request()->file($filename);
        if(is_array($file)){
            foreach (array_keys($file) as $key => $value) {
                $saveName = Upload::UploadFileOne($value,$uploadPath);
                if(strpos($saveName,"\\")){
                    $saveName = str_replace("\\","/",$saveName);//处理\的路径问题
                }
                $imgName[$value] = $uploadPath.'/'.$saveName;
            }
        }
        $imgName['user_id'] = $this->get_userid( input('user_id','','trim') );
        $imgName['status'] = 2;
        $rs = model('member')->edit_member($imgName);
        if($rs>0){
            Api()->setApi('msg','实名认证成功')->ApiSuccess(['status'=>200]);
        }else{
             Api()->setApi('msg',$rs)->ApiError();
        }
    }

    /**
     * 实名认证 base64 多张上传
     * @param  [string] $imageName [description]
     * @param  string $path      [description]
     * @return [type]            [description]
     */
    public function check_user(){
        // Api()->setApi('msg','')->ApiError(input());
        $user_id = $this->get_userid( input('user_id','','trim') );

        // $saveName['realname'] = input('id_name','','trim');//真实姓名
        $saveName['sex'] = input('flag_sex','','trim');//性别
        if( $saveName['sex'] == '男') {
            $saveName['sex'] = 1;
        }else{
            $saveName['sex'] = 2;
        }
        $saveName['birthday'] = str_replace(".","-",input('date_birthday','','trim'));//生日
        $saveName['idcard'] = (string)input('id_no','','trim');//身份证号码
        $saveName['apply_time'] = time();//实名认证时间

        $addr = input('addr_card','','trim');//身份证地址
        if($addr){
            $name = substr($addr,'0','9');
            $saveName['province_id'] = db('city')->where('city_name','like',"$name%")->value('city_code');//籍贯id
            $saveName['address'] = $addr;//身份证地址
        }
        $be_idcard = input('be_idcard',0,'trim');
        if( $be_idcard >= '0.7' ){//通过认证
            $saveName['status'] = 2;
        }else{
            $saveName['status'] = 1;
        }
        
        if($saveName['status'] != 2){
            Api()->setApi('msg','实名认证不通过')->ApiError();
        }

        $url_frontcard = input('url_frontcard','','trim');//身份证正面照
        $url_backcard = input('url_backcard','','trim');//身份证反面照
        $url_photoliving = input('url_photoliving','','trim');//本人头像照
        $url_photoget = input('url_photoget','','trim');//身份证头像照

        $saveName["idcard_z"] = $this->get_idCard_imgurl( $url_frontcard );
        $saveName["idcard_f"] = $this->get_idCard_imgurl( $url_backcard );
        $saveName["idcard_s"] = $this->get_idCard_imgurl( $url_photoliving );
        $saveName['user_id']  = $user_id;

        $rs = model('member')->edit_member($saveName);
        if($rs>0){
            Api()->setApi('msg','实名认证通过')->ApiSuccess(['status'=>200]);
        }else{
            Api()->setApi('msg',$rs)->ApiError();
        }
    }

    /**
     * 保存实名认证图片
     * @param [type] $imgurl
     * @return string $path
     */
    public function get_idCard_imgurl($imgurl){
        if($imgurl){
            $year = date('Y/m/d/',time());
            $uploadPath = "idcard/$year";
            $up_dir = "./public/upload/$uploadPath/";//存放目录public/upload文件夹下
            if (!file_exists($up_dir)) {
                mkdir($up_dir, 0777, true);
            }
            $file = file_get_contents($imgurl);
            $new_file = $up_dir . md5(date('Ymd'.time()). rand(1000,9999)) . '.jpg';  //time()日期+时间戳作为图片名字
            if (file_put_contents($new_file,$file)) {//图片上传成功
                $img = str_replace($up_dir, $uploadPath, $new_file);//用作保存的图片路径和名字
                return $img;
            }
        }
        return false; 
    }

    /**
     * 筛选 投资
     * @param  [type]  $data [description]
     * @return boolean       [description]
     */
    public function is_check($data){
        $data = input();
        if(!isset($data['type'])) $data['type'] = 1;
        $data['user_id'] = $this->get_userid( input('user_id','','trim') );

        if($data['type'] == 1) {
            if(!isValue($data,'id')) Api()->setApi('msg','借款记录id不能为空')->ApiError();
            $checked = model('order')->where(['id'=>$data['id']])->value('checked');
            if($checked == 2){
                model('order')->edit_order(['id'=>$data['id'],'checked'=>1,'check_man'=>'','is_done'=>3,'status'=>0]);
            }else{
                model('order')->edit_order(['id'=>$data['id'],'checked'=>2,'check_man'=>$data['user_id'],'is_done'=>2,'status'=>1]);
            }
            
        }

        //type =2 刷新显示数据 
        $field = 'id as idstr,borrow_id as userid,term,money,fee,interest,invest_id,checked';
        $where = ['checked'=>2,'check_man'=>$data['user_id'],'is_done'=>['<>',1],'status'=>['in',[0,1]] ];
        $where['borrow_id'] = ['<>',$data['user_id']];
        $order = 'borroe_time desc'; //倒叙
        $info = model('order')->select_order($field,$where,$order); //显示已选中的数据
        if($info){
            foreach ($info as $key => &$value) {
                //新增total_income 用于放款列表显示
                $value['total_income'] = (int)$value['interest'];
                if(!empty($value['borrow_id'])){
                    $value['borrowid'] = $value['borrow_id'];
                    $value['borrow_id'] = db('member')->where("user_id = {$value['borrow_id']}")->value('realname');
                }
                if(isValue($value,'invest_id') && $value['invest_id']>0){
                    $value['touzi_status'] = 1; //已投资该借款
                    unset($value['invest_id']);
                }else{
                    $value['touzi_status'] = 2;
                }
                if(!empty($value['userid'])){
                    $user = db('member')->where("user_id = {$value['userid']}")->find();
                    $value['username'] = $user['realname'];
                    $value['headImg'] = Request::instance()->domain().'/public/upload/'.$user['idcard_s'];
                    $value['headImg'] = $this->getHttp( $value['headImg'] );
                    if($user['province_id'] && $user['city_id']){
                        $province_id = db('city')->where("city_code = {$user['province_id']}")->value('city_name');
                        $city_id = db('city')->where("city_code = {$user['city_id']}")->value('city_name');
                        $value['user_addr'] = $province_id.'-'.$city_id;
                    }else{
                        $value['user_addr'] = '';
                    }
                    unset($value['userid']);
                }
            }
        }
        Api()->setApi('msg','')->ApiSuccess(['borrow_info'=>$info]);
    }

    /**
     * 获取微信用户信息
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    // public function get_wxinfo($data){
    //     extract($data);
    //     if(!isValue($data,'wxname')) Api()->setApi('msg','微信授权名字不能为空')->ApiError(); 
    //     if(!isValue($data,'wxheadimg')) Api()->setApi('msg','微信授权头像不能为空')->ApiError(); 
    //     $data['user_id'] = $this->get_userid( input('user_id','','trim') );

    //     $member = ['user_id'=>$data['user_id'],'wxname'=>$wxname,'wxheadimg'=>$wxheadimg];
    //     $rs = model('member')->edit_member($member);
    //     if($rs >0 ){
    //         Api()->setApi('msg','微信授权成功')->ApiSuccess();
    //     }else{
    //         Api()->setApi('msg',$rs)->ApiError();
    //     }
    // }

    /**
     * 简单的 tp5上传-file
     */
    public function file_add_video($uploadPath = 'upload_video_file')
    {
        _log();
        $file = request()->file('file');

        if( $file ){
            $validate = ['size'=>1024*1024*20,'ext'=>'mp4']; //jpg,png,gif,  限制10M
            $path = ROOT_PATH . 'public/upload/'.$uploadPath;

            $info = $file->validate( $validate )->move( $path );
            if( $info ){
                $imgName['video'] =  $info->getSaveName();
                if(strpos($imgName['video'],"\\")){
                    $imgName['video'] = str_replace("\\","/",$imgName['video']);//处理\的路径问题
                }
                Api()->setApi('msg','上传成功')->ApiSuccess( $imgName );
            }else{
                Api()->setApi('msg',$file->getError())->ApiError();
            }
        }else{
            Api()->setApi('msg','上传文件不能为空')->ApiError();
        }
    }

    public function add_relative(){
        $realname = input('post.realname','','trim');
        $mobile = input('post.mobile','','trim');
        $mobile = preg_replace('# #', '', $mobile);
        $user_id = $this->get_userid( input('user_id',0,'trim') );
        if( empty($realname) || empty($mobile) ) {
            Api()->setApi('msg','用户名或手机号不能为空')->ApiError();
        }
        if( !checkMobile( $mobile ) ){
            Api()->setApi('msg','手机号不合法')->ApiError();
        }
        $data = [
            'user_id'   => $user_id,
            'realname'  => $realname,
            'mobile'    => $mobile
        ];
        $re = model('related')->add_relation($data);
        if( $re > 0 ){
            Api()->setApi('msg','添加成功')->ApiSuccess();
        }else{
            Api()->setApi('msg',$re)->ApiError();
        }
    }

    /**
     * 获取通讯录信息
     * @return [type] [description]
     */
    public function get_relative(){
        $field = 'realname,mobile';
        $where['user_id'] = $this->get_userid( input('user_id',0,'trim') );
        $order = 'create_time desc';
        $page = input('page',1,'trim');
        $row = input('row',10,'trim');

        $relationInfo = model('related')->get_related_list($field,$where,$order,$page,$row);
        if( isset( $relationInfo[0] )) {
            Api()->setApi('msg','')->ApiSuccess( $relationInfo );
        }else{
            Api()->setApi('msg','没有符合条件的数据')->ApiError(); 
        }
    }

    public function check_mobile(){
        $taskid = input('task_id','','trim');
        $user_id = $this->get_userid( input('user_id',0,'trim') );
        $memberinfo = [
            'user_id'   => $user_id,
            'taskid'    => $taskid,
            'mobile_status'     => 2
        ];
        $re = model('member')->edit_member( $memberinfo );
        if( $re > 0 ) {
            Api()->setApi('msg','手机认证成功!')->ApiSuccess();
        }else{
            Api()->setApi('msg',$re)->ApiError(); 
        }
    }

    /**
     * 获取金融通话记录次数
     * @return int 记录次数
     */
    public function get_bank_call_record($user_id){
        $user = model('Member')->where('user_id',$user_id)->find();
        $mobile = $user['mobile']?:'';
        $task_id = $user['taskid']?:'';
        $url = "https://api.51datakey.com/carrier/v3/mobiles/{$mobile}/mxreport?task_id={$task_id}";
        // $url = 'https://api.51datakey.com/carrier/v3/mobiles/13510254650/mxreport?task_id=e98a6f90-c2c1-11e7-a013-00163e13e22b';//测试账号
        $header = ['Content-Type: application/json; charset=utf-8',
                    'Authorization:token 25136488e83f4b38b534fac141e8ffd6'
                ];
        $res = curl_send_get($url,$header);
        if(count($res) == '22'){
            $phone_count = '';
            $bank_count = '';
            foreach ($res['behavior_check'] as $value) {
                if(in_array($value['check_point'],array('contact_loan','contact_bank','contact_credit_card'))){//金融、银行、信用卡通话记录
                    $str = explode('，',$value['evidence'])['0'];
                    $str1 = explode('；',$str);
                    $bank_dialing_num = (int)(substr($str1[0],'14'));
                    $bank_called_num = (int)(substr($str1[1],'6'));
                    $bank_count += $bank_dialing_num + $bank_called_num;
                }
                if($value['check_point'] == 'phone_call'){//所有通话记录
                    $str = explode('，',$value['evidence'])['0'];
                    $str1 = explode('；',$str);
                    $phone_dialing_num = (int)(substr($str1[1],'6'));
                    $phone_called_num = (int)(substr($str1[2],'6'));
                    $phone_count += $phone_dialing_num + $phone_called_num;
                }

            }
        }
            $result['bank_count'] = isset($bank_count)?$bank_count:0;
            $result['phone_count'] = isset($phone_count)?$phone_count:0;
            return $result;
    }



    public function testa($idcard='511502199306078858'){
        $bank_call_count = $this->get_bank_call_record('14');//用户通话记录

        $pubkey = "8fee186a-9241-4b1b-aa1e-256501fa875f";
        $product_code = "Y1001005";
        $secretkey = "7ff1596e-d8ae-4796-a3cf-75ac1c4d5454";

        $out_order_id = 'kwd'.rand(0,9).date('YmdHis').rand(10,99);
       
        $data['id_no'] = $idcard;
        $str = json_encode($data,JSON_UNESCAPED_UNICODE);
        $signature = md5($str."|".$secretkey);

        $url = 'https://api4.udcredit.com/dsp-front/4.1/dsp-front/default/pubkey/'.$pubkey.'/product_code/'.$product_code.'/out_order_id/'.$out_order_id.'/signature/'.$signature;
        $header = ['Content-Type: application/json; charset=utf-8'];
        $res = curl_send_post($url,$data,$header);
        if(isset($res['body'])){
            $result = json_decode($res,true);
            $loan_blacklist = $result['body']['graph_detail']['link_user_detail']['online_dishonest_count'];//网贷失信
            $court_blacklist = $result['body']['graph_detail']['link_user_detail']['court_dishonest_count'];//法院失信
            $loan_number = $result['body']['loan_detail']['actual_loan_platform_count'];//借款次数
        }
       
        $data_zx['loan_blacklist'] = isset($loan_blacklist)?$loan_blacklist:'0';
        $data_zx['court_blacklist'] = isset($court_blacklist)?$court_blacklist:'0';
        $data_zx['loan_number'] = isset($loan_number)?$loan_number:'0';
        $t_rating = Db('config')->where(['group'=>'rating'])->column('config_value','config_mark');//通话等级
        $j_rating = Db('config')->where(['group'=>'jrating'])->column('config_value','config_mark');//金融通话等级
        for($i=0;$i<3;$i++){
            $num = array('A','B','C');
            if($bank_call_count['phone_count'] < $t_rating['T_RATING_'.$num[$i].'_HIGH'] && $bank_call_count['phone_count'] >= $t_rating['T_RATING_'.$num[$i].'_LOSS']){
                $data_zx['phone_count'] = $num[$i];//通话等级
            }
            if($bank_call_count['phone_count'] > $t_rating['T_RATING_A_HIGH']){
                $data_zx['phone_count'] = 'A';//通话等级
            }

            if($bank_call_count['bank_count'] < $j_rating['JT_RATING_'.$num[$i].'_HIGH'] && $bank_call_count['bank_count'] >= $j_rating['JT_RATING_'.$num[$i].'_LOSS']){
                $data_zx['bank_phone_count'] = $num[$i];//金融通话等级
            }
            if($bank_call_count['bank_count'] > $j_rating['JT_RATING_A_HIGH']){
                $data_zx['bank_phone_count'] = 'A';//通话等级
            }

            $data_zx['t_rating_'.$num[$i]] = $t_rating['T_RATING_'.$num[$i].'_LOSS']."-".$t_rating['T_RATING_'.$num[$i].'_HIGH'];
            $data_zx['jt_rating_'.$num[$i]] = $j_rating['JT_RATING_'.$num[$i].'_LOSS']."-".$j_rating['JT_RATING_'.$num[$i].'_HIGH'];
        }


        if($data_zx['loan_blacklist'] != 0 || $data_zx['court_blacklist'] != 0){
            $data_zx['id_blacklist'] = '1';//身份证黑名单 1->y 0->n
        }else{
            $data_zx['id_blacklist'] = '0';//身份证黑名单 1->y 0->n
        }
        return $data_zx;//征信详情

    }

    /**
     * 获取banner图
     * @return [type] [description]
     */
   public function get_banners(){
        $banners = model('banner')->column('image');
        if( config('app_debug') == true ) {
            $url_header = Request::instance()->domain().'/kawadai/public/upload';
        }else{
            $url_header = Request::instance()->domain().'/public/upload'; 
        }
        foreach ($banners as $key => $value) {
            $value = $url_header.$value;
            
            $value = $this->getHttp($value);
            $data[]['banner_url'] = $value;
        }
        if( isset($banners[0]) ) {
            Api()->setApi('msg','操作成功')->ApiSuccess($data);
        }else{
            Api()->setApi('msg','操作失败')->ApiError();
        }
    }


    
   

}

