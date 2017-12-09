<?php

return [
	// 默认输出类型
    'default_return_type'    => 'json',
    'overtime_downlogin'	 => 3*24*60*60,
    'app_pay_get_time'  	 => 300,
    'do_data_time'           => 24*60*60, //处理临时数据缓存时间 3小时

    'kwd_invest_fee'		 => 20,	

    'ispay'                  => true, //是否开启支付模式

    //短信模板
    'kwd_app'				 => [
    	'msg_bankcard'		 => '尊敬的x，您的投资权限已开通，祝您投资愉快！平台作为纯撮合平台，不保证本金及收益、不负责催收。请务必自行评估风险，理性投资。',
    	'msg_invest'		 => '尊敬的x,您有一笔投资收益为*元，已转存到财富余额中，借款人为@,请注意查收。',
    ],

    'kwd_app_pays'       => [ //测试
        'merCert'       => 'Ys6z7H93z9h3kQll7tv02SUsjWDcVsatanaPuE4NMbfGLLDOoaAhN7hN9eUxzx45wGT3Ch8De1XwPvRNF0n7GqrnbWRmnlVbxZEs7n6og5229XUveYq9sENyEP5CEsLr',
        //MD5证书 
        'merchantID'    => '1184980025',//测试 商户交易号  1184980025 正式 2030000026
        //正式 2030000018 交易账户名：深圳市咔哇网络科技有限公司 交易账号：2030000026 交易账户名：深圳市咔哇网络科技有限公司-存管
        'postUrl'       => 'http://180.168.26.114:20010/p2p-deposit/gateway.htm',//表单提交地址 
        // 测试 http://180.168.26.114:20010/p2p-deposit/gateway.htm
        's2SUrl'        => 'https://newmer.ips.com.cn/pfas-mfes/', //后台通知地址
        'webUrl'        => 'http://kawadai.net/home/api/index/act/ipsResponse',//页面返回地址
        // 'reg_webUrl'    => 'http://kawadai.net/home/Article/regReturn',//页面返回地址
        // 'addCash_webUrl'    => 'http://kawadai.net/home/Article/addcash',//页面返回地址
        'bankcard'      => '1103',//结算账号
        'shopName'      => '深圳市咔哇网络科技有限公司', //
        'key'           => 'r0uScmDuH5FLO37AJV2FN72J',
        'vi'            => '1eX24DCe',
        // 'realname'      => '深圳市咔哇网络科技有限公司',
        'ipsAcctNo'     => '100000175792',
        'userName'      => '102499336'.rand(10,99).'@qq.com',
        'realName'      => '李玉刚',
        'mobileNo'      => '13510254650',//手机号码
        'identNo'       => '421281198801204957',//身份证号码
        'login_url'     => 'https://UFunds.ips.com.cn/p2p-deposit/login.html',
        'order_id'      => '203000'.rand(1000,9999),
    ],

    'kwd_app_pay'       => [ //正式
        'merCert'       => 'dL9lRAWF3JtD8EELMzqQm4Oq7kWCPfgb2dWP1rdMO0xT0JMEDe6pAc0sRm5EMhZbyHQYXtZLP0ymf6Z1mvcwQLbGsDq5qurOHalf7m6WbjQ6ZsPx8OqfgA2r0DC11JbZ',
        //MD5证书 正式环境 
        'merchantID'    => '2030000026',//测试 商户交易号  1184980025 正式 2030000026
        'postUrl'       => 'https://UFunds.ips.com.cn/p2p-deposit/gateway.htm',//表单提交地址 
        's2SUrl'        => 'https://newmer.ips.com.cn/pfas-mfes/login.do', //后台通知地址
        'webUrl'        => 'http://kawadai.net/home/api/index/act/ipsResponse',//页面返回地址  http://www.liesunw.com
        // 'reg_webUrl'    => 'http://kwd.baogt.com/home/Article/regReturn',//页面返回地址
        // 'bankcard'      => '755935698110601',//结算账号
        'merchantNo'      => '203000', //商户号
        'key'           => 'A9yJ5LphJuFXFW0kwf8Xp7zT',
        'vi'            => 'w90PPdEF',
        
        'userName'      => '1024993364@qq.com',//登录用户名
        'realName'      => '王刚刚',
        'mobileNo'      => '13510254650',//手机号码
        'identNo'       => '610429199108260410',//身份证号码
        'bankcard'      => '6228480128318476776',//银行卡号
        'ipsAcctNo'     => '100006938169',
        'pwd'           => '2JdJpDs9',
        'login_url'     => 'https://UFunds.ips.com.cn/p2p-deposit/login.html',
        'admin_url'     => 'https://newmer.ips.com.cn/pfas-mfes/',
        'order_id'      => '203000'.rand(1,9).substr(time(),0,-4),
    ],
    'test_sms_url'      => 'http://180.168.26.114:20010/p2p-deposit/test/queryValide.html',//测试环境验证码获取地址

    'wopay_api_key'                         => 'RLOGS5SL85J56T525L36TEPPQ0VM2AQO',//代付 秘钥
    'wopay_api_payforanother_request_url'   => 'http://mertest.unicompayment.com/issuegw/servlet/SingleIssueServlet.htm',//代付
    'wopay_api_payforplantform_request_url' => 'http://mertest.unicompayment.com/WithhGw_XT/servlet/signAndSinglePay',//代收
    'wopay_api_response_url'                => 'http://kwd.baogt.com/home/api/index/act/woResponse', //回调地址


    'kwd_wo'    =>[
        'signAcc'        => '6222021001116245706', // 沃账户 6222021001116245706 13911138409
        'signName'       => '徐双双', //用户名 张平 徐双双
        'identityInfo'   => '411521199204106024', //身份证号码 411521199204106024 320101198706140092
        'topay_key'      => 'Q310LQ04O8Q64FOCUNB0GDHN4IJUQRQB',//代收秘钥 
        'topay_merNo'    => '301101910008366',  //代收商户号

        //代付参数
        'payfor_merNo'   => '301100710007122', //代付商户号
        
    ],

    //通用支付平台 快捷代扣 测试环境
    'comm_pay'      => [
        'postUrl'           => 'https://218.17.35.123:6443/gateway/nonbatch',
        'version'           => '1.0.0-IEST',
        'cardNo'            => '755935698110601',
        'productNo'         => '2BA00AAJ',
        'merchantId'        => '2017113000092222',
        'notifyUrl'         => 'http://kwd.baogt.com/home/api/index/act/commresurl',
        'merchantBindPhoneNo'=> '17603008582'
    ],  

     //通用支付平台 快捷代扣 正式环境
    'comm_pays'      => [
        'postUrl'           => 'https://merchant.kftpay.com.cn:8443/gateway/nonbatch',
        'version'           => '1.0.0-PRD',
        'cardNo'            => '',
        'productNo'         => '',
        'merchantId'        => '',
        'notifyUrl'         => 'http://kwd.baogt.com/home/api/index/act/commresurl',
        'merchantBindPhoneNo'=> '17603008582'
    ],  
];
