<?
$set['title']='检查 CHMOD';
include_once 'inc/head.php'; // 设计主题的顶部
if(isset($_GET['chmod_ok'])){
@chmod(H.'install/',0777);
@chmod(H.'sys/avatar/',0777);
@chmod(H.'sys/dat/',0777);
@chmod(H.'sys/forum/files',0777);
@chmod(H.'sys/gallery/48/',0777);
@chmod(H.'sys/gallery/50/',0777);
@chmod(H.'sys/gallery/128/',0777);
@chmod(H.'sys/gallery/640/',0777);
@chmod(H.'sys/gallery/photo/',0777);
@chmod(H.'sys/inc/',0777);
@chmod(H.'sys/fnc/',0777);
@chmod(H.'files/down/',0777);
@chmod(H.'files/screens/14/',0777);
@chmod(H.'files/screens/48/',0777);
@chmod(H.'files/screens/128/',0777);
@chmod(H.'sys/update/',0777);
@chmod(H.'sys/tmp/',0777);
@chmod(H.'style/themes/',0777);
@chmod(H.'style/smiles/',0777);
@chmod(H.'sys/gift/',0777);
msg('成功获得权利！');
}
echo "<form method='post' action='?chmod_ok'>";
echo "<input type='submit' name='refresh' value='获得权利！' />";
echo "</form>";
include_once H.'sys/inc/chmod_test.php';
if (isset($err))
{
if (is_array($err))
{
foreach ($err as $key=>$value) {
echo "<div class='err'>$value</div>";
}
}
else
echo "<div class='err'>$err</div>";
}
elseif(isset($_GET['step']) && $_GET['step']=='3')
{
$_SESSION['install_step']++;
header("Location: index.php?$passgen&".SID);
exit;
}
echo "<hr />";
echo "<form method=\"get\" action=\"index.php\">";
echo "<input name='gen' value='$passgen' type='hidden' />";
echo "<input name=\"step\" value=\"".($_SESSION['install_step']+1)."\" type=\"hidden\" />";
echo "<input value=\"".(isset($err)?'尚未准备好安装':'继续')."\" type=\"submit\"".(isset($err)?' disabled="disabled"':null)." />";
echo "</form>";
echo "<hr />";
echo "<b>步骤: $_SESSION[install_step]</b>";
include_once 'inc/foot.php'; //设计主题的底部
?>