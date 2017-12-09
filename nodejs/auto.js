var schedule = require('node-schedule');
var jQuery  = require('jquery');
var $ = require('jquery')(require("jsdom").jsdom().defaultView);
var exec = require('child_process').exec;
var cmd = '"C:/Program Files/phpstudy/php/php-7.0.12-nts/php.exe" "D:/wwwroot/kwdcdttdcom/auto.php"';
console.log('咔哇贷判断逾期，计算罚息')
var rule1     = new schedule.RecurrenceRule(); 
var times1    = [1,6,11,16,21,26,31,36,41,46,51,56];  
rule1.second  = times1; 
// schedule.scheduleJob('1-59 * * * * *', function(){
//     exec(cmd, function(error, stdout, stderr) {
//     	console.log('咔哇贷判断逾期，计算罚息',stdout,stderr,error)
// 	});
// });
schedule.scheduleJob(rule1, function(){
    exec(cmd, function(error, stdout, stderr) {
    	console.log('咔哇贷判断逾期，计算罚息',stdout,stderr,error)
	});
	// console.log('绝对地址可能不对')
});


