<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ams_iweite_vods_category` (
`tid` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(300),
`sid` int(4),
`dateline` varchar(80),
`fdes` varchar(350),
`fpic` varchar(300),
`weid` int(4),
`isup` int(4),
PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ams_iweite_vods_guanggao` (
`tid` int(11) NOT NULL AUTO_INCREMENT,
`classid` int(4),
`title` varchar(300),
`fpic` varchar(300),
`flink` mediumtext,
`sid` int(4),
`dateline` varchar(80),
`weid` int(4),
PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ams_iweite_vods_jiekou` (
`tid` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(4),
`title` varchar(300),
`fdes` mediumtext,
`dateline` varchar(80),
PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ams_iweite_vods_setting` (
`tid` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(4),
`title` varchar(300),
`indexpagesize` int(4),
`pagesize` int(4),
`copyright` mediumtext,
`cnzz` varchar(300),
`share` mediumtext,
`guanzhu` mediumtext,
PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ams_iweite_vods_ziyuan` (
`tid` int(11) NOT NULL AUTO_INCREMENT,
`classid` int(4),
`title` varchar(300),
`fpic` varchar(300),
`fdes` varchar(300),
`isok` int(4),
`recommend` int(4),
`sid` int(4),
`dateline` varchar(80),
`views` int(11),
`guanlian` varchar(350),
`content` mediumtext,
`weid` int(4),
PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ams_iweite_vods_ziyuan_list` (
`tid` int(11) NOT NULL AUTO_INCREMENT,
`classid` int(4),
`content` mediumtext,
`dateline` varchar(80),
`weid` int(4),
PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
