<?php
return array(
	"SHOW_PAGE_TRACE"=>false,//是否显示页面跟踪信息
	'URL_CASE_INSENSITIVE'=>true,// 默认false 表示URL区分大小写 true则表示不区分大小写
	'DB_PARAMS' => array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),//数据库字段列名按照原始的方式，默认强制列名是小写

	'SITE_URL' => 'www.test.com',//域名地址

	'DB_TYPE'  => 'mysql',// 数据库类型 
	'DB_HOST'  => '127.0.0.1',// 数据库服务器地址
	'DB_NAME'  => 'pay',// 数据库名称
	'DB_USER'  => 'root',// 数据库用户名
	'DB_PWD'  => 'ABCabc2017',// 数据库密码
	'DB_PREFIX'  => 'de_',// 保持默认
	'DB_CHARSET' => 'utf8',// 网站编码
	'DB_PORT'  => '3306',// 数据库端口

	'VAR_PAGE'=>'p',
	'PAGE_SIZE' => 20,//分页每页显示数据数量
    
        'PRINTER_CONFIG' => array('IP'=>'api.feieyun.cn', 'PORT'=>80, 'HOSTNAME'=>'/Api/Open/', 'USER'=>'2320937612@qq.com','UKEY'=>'NyNsAE7cpGSVSKEd'),//新版飞蛾打印机配置
        'PRINTER_CONFIG_OLD1' => array('IP'=>'api163.feieyun.com', 'PORT'=>80,'HOSTNAME'=>'/FeieServer'),//旧版本1飞蛾打印机配置
        'PRINTER_CONFIG_OLD2' => array('IP'=>'dzp.feieyun.com', 'PORT'=>80,'HOSTNAME'=>'/FeieServer'),//旧版本2飞蛾打印机配置
    
        'PRINTER_CONFIG_YLY' =>array('PARTNER'=>7445, 'APIKEY'=>'f4257c82bc744aa29cf05332decab32cd19aa697'),//易联云打印机配置
);