<?php
//global $_W;//?这行代码不知道什么作用
$sql = "

drop table if exists " . tablename('iweite_vods_category') . " ;
drop table if exists " . tablename('iweite_vods_guanggao') . " ;
drop table if exists " . tablename('iweite_vods_jiekou') . " ;
drop table if exists " . tablename('iweite_vods_setting') . " ;
drop table if exists " . tablename('iweite_vods_ziyuan') . " ;
drop table if exists " . tablename('iweite_vods_ziyuan_list') . " ;
";
pdo_query($sql);



//drop table if exists " . tablename_p('sysset') . " ;