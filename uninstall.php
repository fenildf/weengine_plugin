<?php
//global $_W;//?这行代码不知道什么作用
$sql = "

drop table if exists " . tablename('stone_doc_plugin') . " ;
drop table if exists " . tablename('stone_doc_designer') . " ;
drop table if exists " . tablename('stone_doc_designer_menu') . " ;
drop table if exists " . tablename('stone_doc_category') . " ;
drop table if exists " . tablename_p('sysset') . " ;
";
pdo_query($sql);

// drop table if exists " . tablename('ewei_shop_adv') . " ;
// drop table if exists " . tablename('ewei_shop_carrier') . " ;

// drop table if exists " . tablename('ewei_shop_commission_apply') . " ;
// drop table if exists " . tablename('ewei_shop_commission_clickcount') . " ;
// drop table if exists " . tablename('ewei_shop_commission_level') . " ;
// drop table if exists " . tablename('ewei_shop_commission_log') . " ;
// drop table if exists " . tablename('ewei_shop_commission_shop') . " ;
// drop table if exists " . tablename('ewei_shop_designer') . " ;
// drop table if exists " . tablename('ewei_shop_dispatch') . " ;
// drop table if exists " . tablename('ewei_shop_express') . " ;
// drop table if exists " . tablename('ewei_shop_feedback') . " ;
// drop table if exists " . tablename('ewei_shop_goods') . "; 
// drop table if exists " . tablename('ewei_shop_goods_comment') . " ;
// drop table if exists " . tablename('ewei_shop_goods_option') . " ;
// drop table if exists " . tablename('ewei_shop_goods_param') . " ;
// drop table if exists " . tablename('ewei_shop_goods_spec') . " ;
// drop table if exists " . tablename('ewei_shop_goods_spec_item') . " ;
// drop table if exists " . tablename('ewei_shop_member') . " ;
// drop table if exists " . tablename('ewei_shop_member_address') . " ;
// drop table if exists " . tablename('ewei_shop_member_cart') . " ;
// drop table if exists " . tablename('ewei_shop_member_favorite') . " ;
// drop table if exists " . tablename('ewei_shop_member_group') . " ;
// drop table if exists " . tablename('ewei_shop_member_history') . " ;
// drop table if exists " . tablename('ewei_shop_member_level') . " ;
// drop table if exists " . tablename('ewei_shop_member_log') . " ;
// drop table if exists " . tablename('ewei_shop_member_message_template') . " ;
// drop table if exists " . tablename('ewei_shop_notice') . " ;
// drop table if exists " . tablename('ewei_shop_order') . " ;
// drop table if exists " . tablename('ewei_shop_order_comment') . " ;
// drop table if exists " . tablename('ewei_shop_order_goods') . " ;
// drop table if exists " . tablename('ewei_shop_order_refund') . " ;
// drop table if exists " . tablename('ewei_shop_perm_log') . " ;
// drop table if exists " . tablename('ewei_shop_perm_plugin') . "; 
// drop table if exists " . tablename('ewei_shop_perm_role') . " ;
// drop table if exists " . tablename('ewei_shop_perm_user') . " ;
// drop table if exists " . tablename('ewei_shop_plugin') . " ;
// drop table if exists " . tablename('ewei_shop_poster') . " ;
// drop table if exists " . tablename('ewei_shop_poster_log') . " ;
// drop table if exists " . tablename('ewei_shop_poster_qr') . " ;
// drop table if exists " . tablename('ewei_shop_poster_scan') . " ;
// drop table if exists " . tablename('ewei_shop_saler') . " ;
// drop table if exists " . tablename('ewei_shop_store') . " ;

// drop table if exists " . tablename('ewei_shop_creditshop_adv') . " ;
// drop table if exists " . tablename('ewei_shop_creditshop_category') . " ;
// drop table if exists " . tablename('ewei_shop_creditshop_goods') . " ;
// drop table if exists " . tablename('ewei_shop_creditshop_log') . " ;
// drop table if exists " . tablename('ewei_shop_virtual_category') . " ;
// drop table if exists " . tablename('ewei_shop_virtual_data') . " ;
// drop table if exists " . tablename('ewei_shop_virtual_type') . " ;
// 