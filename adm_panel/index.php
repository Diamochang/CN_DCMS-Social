<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/adm_check.php';
include_once '../sys/inc/user.php';
user_access('adm_panel_show',null,'/index.php?'.SID);
		   
if (isset($_SESSION['adm_auth']) && $_SESSION['adm_auth']>$time || isset($_SESSION['captcha']) && isset($_POST['chislo']) && $_SESSION['captcha']==$_POST['chislo'])
{
$_SESSION['adm_auth']=$time+600;

if (isset($_GET['go']) && $_GET['go']!=null)
{
header('Location: '.base64_decode($_GET['go']));exit;
}$set['title']='管理面板';
include_once '../sys/inc/thead.php';
title();
err();
aut();
echo "<div class='mess'>\n";
echo "<center><b>DCMS-Social v.$set[dcms_version]</b></center>\n";echo "</div>\n";
if (user_access('adm_info'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a target='_blank' href='http://dcms-social.ru'>支持论坛</a></div>\n";
if (user_access('adm_info'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='info.php'>一般资料</a></div>\n";
if (user_access('adm_statistic'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='statistic.php'>地盘统计数字</a></div>\n";
if (user_access('adm_show_adm'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='administration.php'>行政工作</a></div>\n";
if (user_access('adm_log_read'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='adm_log.php'>行政当局的行动</a></div>\n";
if (user_access('adm_menu'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='menu.php'>主菜单</a></div>\n";
if (user_access('adm_rekl'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='rekl.php'>广告</a></div>\n";
if (user_access('adm_news'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='/news/add.php'>新闻中心</a></div>\n";
if (user_access('adm_set_sys'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='settings_sys.php'>系统设置</a></div>\n";
if (user_access('adm_set_sys'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='settings_bbcode.php'>BBCode设置</a></div>\n";
if ($user['level'] > 3)echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='/user/gift/create.php'>礼物</a></div>\n";
if ($user['level'] > 3)echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='smiles.php'>表情符号</a></div>\n";
if (user_access('adm_set_forum'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='settings_forum.php'>论坛设置</a></div>\n";
if (user_access('adm_set_user'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='settings_user.php'>用户设置</a></div>\n";
if (user_access('adm_accesses'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='accesses.php'>用户组权限</a></div>\n";
if (user_access('adm_banlist'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='banlist.php'>禁止名单</a></div>\n";
if (user_access('adm_set_loads'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='settings_loads.php'>下载设置</a></div>\n";
if (user_access('adm_set_chat'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='settings_chat.php'>聊天设置</a></div>\n";

if (user_access('adm_set_foto'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='settings_foto.php'>照片库设置</a></div>\n";

if (user_access('adm_forum_sinc'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='forum_sinc.php'>论坛表的同步</a></div>\n";
if (user_access('adm_ref'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='referals.php'>转介服务</a></div>\n";
if (user_access('adm_ip_edit'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='opsos.php'>编辑IP运营商</a></div>\n";
if (user_access('adm_ban_ip'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='ban_ip.php'>禁止IP地址(范围)</a></div>\n";

if (user_access('adm_mysql'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='mysql.php'>MySQL查询</a></div>\n";
if (user_access('adm_mysql'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='tables.php'>填表</a></div>\n";
if (user_access('adm_themes'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='themes.php'>设计主题</a></div>\n";

$opdirbase=@opendir(H.'sys/add/admin');
while ($filebase=@readdir($opdirbase))
if (preg_match('#\.php$#i',$filebase))
include_once(H.'sys/add/admin/'.$filebase);
closedir($opdirbase);

}
else
{

$set['title']='防止自动更改';
include_once '../sys/inc/thead.php';
title();
err();
aut();
echo "<form method='post' action='?gen=$passgen&amp;".(isset($_GET['go'])?"go=$_GET[go]":null)."'>\n";

echo "<img src='/captcha.php?$passgen&amp;SESS=$sess' width='100' height='30' alt='核证号码' /><br />\n从图片中输入数字:<br //>\n<input name='chislo' size='5' maxlength='5' value='' type='text' /><br/>\n";
echo "<input type='submit' value='进一步' />\n";
echo "</form>\n";
}

include_once '../sys/inc/tfoot.php';
?>