#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

zdTable('user')->gen(10);

/**

title=测试 userModel->resetPassword();
cid=1
pid=1

重设用户密码，返回重设后的加密后的密码 >> 73ccf67b17076bb67d200eb82f54647d
两次密码不相同的情况 >> 两次密码应该相同。

*/

$user = new userTest();
$normalUser = array();
$normalUser['account']          = 'admin';
$normalUser['password1']        = '367ef140036b0feb2f90d70d33255eea';
$normalUser['password2']        = '367ef140036b0feb2f90d70d33255eea';
$normalUser['passwordStrength'] = 1;

$weakPassword = $normalUser;
$weakPassword['passwordStrength'] = 0;

$differentPassword = $normalUser;
$differentPassword['password2'] = '!@#!@#asfasf';

$simplePassword = $normalUser;
$simplePassword['password1'] = 'e10adc3949ba59abbe56e057f20f883e';
$simplePassword['password2'] = 'e10adc3949ba59abbe56e057f20f883e';

r($user->resetPasswordTest($normalUser))        && p('password')    && e('73ccf67b17076bb67d200eb82f54647d');       //重设用户密码，返回重设后的加密后的密码
r($user->resetPasswordTest($differentPassword)) && p('password:0')  && e('两次密码应该相同。');                          //两次密码不相同的情况
