<?





include_once 'sys/inc/start.php';





include_once 'sys/inc/compress.php';





include_once 'sys/inc/sess.php';





include_once 'sys/inc/home.php';





include_once 'sys/inc/settings.php';





include_once 'sys/inc/db_connect.php';





include_once 'sys/inc/ipua.php';





include_once 'sys/inc/fnc.php';





include_once 'sys/inc/shif.php';





include_once 'sys/inc/user.php';











only_reg();





$set['title']='安全';





include_once 'sys/inc/thead.php';





title();





if (isset($_POST['save'])){











if (isset($_POST['pass']) && dbresult(dbquery("SELECT COUNT(*) FROM `user` WHERE `id` = $user[id] AND `pass` = '".shif($_POST['pass'])."' LIMIT 1"), 0)==1)





{





if (isset($_POST['pass1']) && isset($_POST['pass2']))





{





if ($_POST['pass1']==$_POST['pass2'])





{





if (strlen2($_POST['pass1'])<6)$err='出于安全原因，新密码不能短于6个字符';





if (strlen2($_POST['pass1'])>32)$err='密码长度超过32个字符';





}





else $err='新密码与确认不符';





}





else $err='输入新密码';





}





else $err='旧密码不正确';























if (!isset($err))





{





dbquery("UPDATE `user` SET `pass` = '".shif($_POST['pass1'])."' WHERE `id` = '$user[id]' LIMIT 1");





setcookie('pass', cookie_encrypt($_POST['pass1'],$user['id']), time()+60*60*24*365);





msg('Пароль успешно изменен');





}











}





err();





aut();

















echo "<form method='post' action='?$passgen'>\n";











echo "Старый пароль:<br />\n<input type='text' name='pass' value='' /><br />\n";





echo "Новый пароль:<br />\n<input type='password' name='pass1' value='' /><br />\n";





echo "Подтверждение:<br />\n<input type='password' name='pass2' value='' /><br />\n";





echo "<input type='submit' name='save' value='Изменить' />\n";





echo "</form>\n";

















include_once 'sys/inc/tfoot.php';





?>