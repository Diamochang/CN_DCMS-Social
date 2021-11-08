<?
//include_once 'sys/inc/mp3.php';
//include_once 'sys/inc/zip.php';
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/shif.php';
$show_all = true; // показ для всех
include_once 'sys/inc/user.php';
only_unreg();
$set['title'] = 'Регистрация';
include_once 'sys/inc/thead.php';
title();


if ($set['guest_select'] == '1') msg("只有授权用户才能访问该网站");
if ((!isset($_SESSION['refer']) || $_SESSION['refer'] == NULL)
	&& isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != NULL &&
	!preg_match('#mail\.php#', $_SERVER['HTTP_REFERER'])
)
	$_SESSION['refer'] = str_replace('&', '&amp;', preg_replace('#^http://[^/]*/#', '/', $_SERVER['HTTP_REFERER']));
if ($set['reg_select'] == 'close') {
	$err = '暂停登记';
	err();

	echo "<a href='/aut.php'>授权书</a><br />\n";
	include_once 'sys/inc/tfoot.php';
} elseif ($set['reg_select'] == 'open_mail' && isset($_GET['id']) && isset($_GET['activation']) && $_GET['activation'] != NULL) {
	if (dbresult(dbquery("SELECT COUNT(*) FROM `user` WHERE `id` = '" . intval($_GET['id']) . "' AND `activation` = '" . my_esc($_GET['activation']) . "'"), 0) == 1) {

		dbquery("UPDATE `user` SET `activation` = null WHERE `id` = '" . intval($_GET['id']) . "' LIMIT 1");
		$user = dbassoc(dbquery("SELECT * FROM `user` WHERE `id` = '" . intval($_GET['id']) . "' LIMIT 1"));
		dbquery("INSERT INTO `reg_mail` (`id_user`,`mail`) VALUES ('$user[id]','$user[ank_mail]')");
		msg('您的帐户已成功启动');

		$_SESSION['id_user'] = $user['id'];
		include_once 'sys/inc/tfoot.php';
	}
}

if (isset($_SESSION['step']) && $_SESSION['step'] == 1 && dbresult(dbquery("SELECT COUNT(*) FROM `user` WHERE `nick` = '" . $_SESSION['reg_nick'] . "'"), 0) == 0 && isset($_POST['pass1']) && $_POST['pass1'] != NULL && $_POST['pass2'] && $_POST['pass2'] != NULL) {

	if ($set['reg_select'] == 'open_mail') {
		if (!isset($_POST['ank_mail']) || $_POST['ank_mail'] == NULL) $err[] = 'Неоходимо ввести Email';
		elseif (!preg_match('#^[A-z0-9-\._]+@[A-z0-9]{2,}\.[A-z]{2,4}$#ui', $_POST['ank_mail'])) $err[] = 'Неверный формат Email';
		elseif (dbresult(dbquery("SELECT COUNT(*) FROM `reg_mail` WHERE `mail` = '" . my_esc($_POST['ank_mail']) . "'"), 0) != 0) {
			$err[] = "Пользователь с этим E-mail уже зарегистрирован";
		}
	}
	if (strlen2($_POST['pass1']) < 6) $err[] = '出于安全原因，密码不能短于6个字符';
	if (strlen2($_POST['pass1']) > 32) $err[] = '密码长度超过32个字符';
	if ($_POST['pass1'] != $_POST['pass2']) $err[] = '密码不匹配';
	if (!isset($_SESSION['captcha']) || !isset($_POST['chislo']) || $_SESSION['captcha'] != $_POST['chislo']) {
		$err[] = '验证号码无效';
	}

	if (!isset($err)) {
		if ($set['reg_select'] == 'open_mail') {
			$activation = md5(passgen());

			dbquery("INSERT INTO `user` (`nick`, `pass`, `date_reg`, `date_last`, `pol`, `activation`, `ank_mail`) values('" . $_SESSION['reg_nick'] . "', '" . shif($_POST['pass1']) . "', '$time', '$time', '" . intval($_POST['pol']) . "', '$activation', '" . my_esc($_POST['ank_mail']) . "')", $db);

			$id_reg = mysql_insert_id();
			$subject = "帐户激活";
			$regmail = "你好！ $_SESSION[reg_nick]<br />
			要激活您的帐户，请点击链接:<br />
<a href='http://$_SERVER[HTTP_HOST]/reg.php?id=$id_reg&amp;activation=$activation'>http://$_SERVER[HTTP_HOST]/reg.php?id=" . mysql_insert_id() . "&amp;activation=$activation</a><br />
如果帐户在24小时内未激活，它将被删除<br />
真诚的，网站管理<br />
";
			$adds = "From: \"password@$_SERVER[HTTP_HOST]\" <password@$_SERVER[HTTP_HOST]>\n";
			//$adds = "From: <$set[reg_mail]>\n";
			//$adds .= "X-sender: <$set[reg_mail]>\n";
			$adds .= "Content-Type: text/html; charset=utf-8\n";
			mail($_POST['ank_mail'], '=?utf-8?B?' . base64_encode($subject) . '?=', $regmail, $adds);
		} else
			dbquery("INSERT INTO `user` (`nick`, `pass`, `date_reg`, `date_last`, `pol`) values('" . $_SESSION['reg_nick'] . "', '" . shif($_POST['pass1']) . "', '$time', '$time', '" . intval($_POST['pol']) . "')", $db);

		$user = dbassoc(dbquery("SELECT * FROM `user` WHERE `nick` = '" . my_esc($_SESSION['reg_nick']) . "' AND `pass` = '" . shif($_POST['pass1']) . "' LIMIT 1"));

		/*
========================================
Создание настроек юзера 
========================================
*/

		dbquery("INSERT INTO `user_set` (`id_user`) VALUES ('$user[id]')");
		dbquery("INSERT INTO `discussions_set` (`id_user`) VALUES ('$user[id]')");
		dbquery("INSERT INTO `tape_set` (`id_user`) VALUES ('$user[id]')");
		dbquery("INSERT INTO `notification_set` (`id_user`) VALUES ('$user[id]')");


		if (isset($_SESSION['http_referer']))
			dbquery("INSERT INTO `user_ref` (`time`, `id_user`, `type_input`, `url`) VALUES ('$time', '$user[id]', 'reg', '" . my_esc($_SESSION['http_referer']) . "')");

		$_SESSION['id_user'] = $user['id'];
		setcookie('id_user', $user['id'], time() + 60 * 60 * 24 * 365);
		setcookie('pass', cookie_encrypt($_POST['pass1'], $user['id']), time() + 60 * 60 * 24 * 365);

		if ($set['reg_select'] == 'open_mail') {
			msg('您需要使用发送到电子邮件的链接激活您的帐户');
		} else {
			dbquery("update `user` set `wall` = '0' where `id` = '$user[id]' limit 1");
			header('Location: /umenu.php?login=' . htmlspecialchars($_POST['reg_nick']) . '&pass=' . htmlspecialchars($_POST['pass1']));
		}

		echo "如果您的浏览器不支持Cookie，您可以创建一个自动登录书签<br />\n";
		echo "<input type='text' value='http://$_SERVER[SERVER_NAME]/login.php?id=$user[id]&amp;pass=" . htmlspecialchars($_POST['pass1']) . "' /><br />\n";
		if ($set['reg_select'] == 'open_mail') unset($user);
		echo "<div class='foot'>\n";
		echo "&raquo;<a href='settings.php'>我的设置</a><br />\n";
		echo "&raquo;<a href='umenu.php'>我的菜单</a><br />\n";
		echo "</div>\n";
		include_once 'sys/inc/tfoot.php';
	}
} elseif (isset($_POST['nick']) && $_POST['nick'] != NULL) {
	if (dbresult(dbquery("SELECT COUNT(*) FROM `user` WHERE `nick` = '" . my_esc($_POST['nick']) . "'"), 0) == 0) {
		$nick = my_esc($_POST['nick']);
		if (!preg_match("#^([A-zА-я0-9\-\_\ ])+$#ui", $_POST['nick'])) $err[] = '昵称中有禁字';
		if (preg_match("#[a-z]+#ui", $_POST['nick']) && preg_match("#[а-я]+#ui", $_POST['nick'])) $err[] = '只允许使用俄文或英文字母字符а';
		if (preg_match("#(^\ )|(\ $)#ui", $_POST['nick'])) $err[] = '禁止在昵称的开头和结尾使用空格';
		if (strlen2($nick) < 3) $err[] = '短昵称';
		if (strlen2($nick) > 32) $err[] = '昵称长度超过32个字符';
	} else $err[] = '尼克 "' . stripcslashes(htmlspecialchars($_POST['nick'])) . '"已登记';
	if (!isset($err)) {
		$_SESSION['reg_nick'] = $nick;
		$_SESSION['step'] = 1;
		msg("尼克 \"$nick\" 可以成功注册");
	}
}

err();
if (isset($_SESSION['step']) && $_SESSION['step'] == 1) {

	echo "<form method='post' action='/reg.php?$passgen'>\n";
	echo "你的昵称[A-zА-я0-9 -_]:<br /><input type='text' name='nick' maxlength='32' value='$_SESSION[reg_nick]' /><br />\n";
	echo "<input type='submit' value='另一个' />\n";
	echo "</form><br />\n";
	echo "<form method='post' action='/reg.php?$passgen'>\n";
	echo "你的性别:<br /><select name='pol'><option value='1'>男</option><option value='0'>女</option></select><br />\n";

	if ($set['reg_select'] == 'open_mail') {
		echo "E-mail:<br /><input type='text' name='ank_mail' /><br />\n";
		echo "* 指定您的真实电子邮件地址。您将收到一个激活您的帐户的代码.<br />\n";
	}
	echo "输入密码（6-32个字符）:<br /><input type='password' name='pass1' maxlength='32' /><br />\n";
	echo "重复密码:<br /><input type='password' name='pass2' maxlength='32' /><br />\n";
	echo "<img src='/captcha.php?$passgen&amp;SESS=$sess' width='100' height='30' alt='核证号码' /><br />\n<input name='chislo' size='5' maxlength='5' value='' type='text' /><br/>\n";
	echo "通过注册，您自动同意 <a href='/rules.php'>规则</a> 网站<br />\n";

	echo "<input type='submit' value='继续' />\n";
	echo "</form><br />\n";
} else {
	echo "<form class='mess' method='post' action='/reg.php?$passgen'>\n";
	echo "选择昵称 [A-zА-я0-9 -_]:<br /><input type='text' name='nick' maxlength='32' /><br />\n";
	echo "通过注册，您自动同意 <a href='/rules.php'>规则</a> 网站<br />\n";
	echo "<input type='submit' value='继续' />\n";
	echo "</form><br />\n";
}

echo "<div class = 'foot'>已经注册？<br />&raquo;<a href='/aut.php'>授权书</a></div>
<div class = 'foot'>不记得密码？<br />&raquo;<a href='/pass.php'>恢复密码</a></div>\n";
include_once 'sys/inc/tfoot.php';
