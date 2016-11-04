<?php
$pre = 'iweite_vods_';
pdo_query("CREATE TABLE IF NOT EXISTS ".tablename($pre."category")." (
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

CREATE TABLE IF NOT EXISTS ".tablename($pre."guanggao")." (
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

CREATE TABLE IF NOT EXISTS ".tablename($pre."jiekou")." (
`tid` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(4),
`title` varchar(300),
`fdes` mediumtext,
`dateline` varchar(80),
PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS ".tablename($pre."setting")." (
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

CREATE TABLE IF NOT EXISTS ".tablename($pre."ziyuan")." (
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

CREATE TABLE IF NOT EXISTS ".tablename($pre."ziyuan_list")." (
`tid` int(11) NOT NULL AUTO_INCREMENT,
`classid` int(4),
`content` mediumtext,
`dateline` varchar(80),
`weid` int(4),
PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
