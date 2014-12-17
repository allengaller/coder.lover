<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
checkauth();
load()->model('activity');
$filter = array();
$coupons = activity_coupon_owned($_W['member']['uid'], $filter);
$tokens = activity_token_owned($_W['member']['uid'], $filter);

$setting = uni_setting($_W['uniacid'], array('creditnames', 'creditbehaviors', 'uc', 'payment'));
$behavior = $setting['creditbehaviors'];
$creditnames = $setting['creditnames'];
$credits = mc_credit_fetch($_W['member']['uid'], '*');

$setting = uni_setting($_W['uniacid'], array('passport'));
if (empty($setting['passport']['focusreg'])) {
	if ($_W['member']['email'] == md5($_W['openid']).'@we7.cc') {
		$forward = base64_encode($_SERVER['QUERY_STRING']);
		header("location: " . url('auth/register', array('forward' => $forward)), true);
		exit;
	}
}


$sql = 'SELECT `status` FROM ' . tablename('mc_card') . " WHERE `uniacid` = :uniacid";
$cardstatus = pdo_fetch($sql, array(':uniacid' => $_W['uniacid']));


