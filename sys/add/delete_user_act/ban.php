<?
dbquery("DELETE FROM `ban` WHERE `id_user` = '$ank[id]' OR `id_ban` = '$ank[id]'");
if (isset($_GET['all']) && count($collisions)>1)
{
for ($i=1;$i<count($collisions);$i++)
{
dbquery("DELETE FROM `ban` WHERE `id_user` = '$collisions[$i]' OR `id_ban` = '$collisions[$i]'");
}
}
?>