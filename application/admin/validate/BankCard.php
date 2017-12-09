<?php
namespace app\admin\validate;
use think\Validate;

class BankCard extends Validate{
	 protected $rule = [
        ['account',       'require|length:2,30',              '帐号不能为空|帐号长度为2-30个字符'],
        ['bank_code',     'require',                    	  '开户银行不能为空'],
        ['bank_addr',     'require',                          '开户地址不能为空'],
        ['bankcard_num',  'require',                          '银行账号不能为空'],
        
        
    ];
    protected $scene = [
        'add'   =>  ['account','bank_code','bank_addr','bankcard_num'],
        'edit'	=>	['account','bank_code','bank_addr','bankcard_num'],
    ];    
}