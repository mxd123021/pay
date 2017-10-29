<?php
return array(
	"URL_MODEl"=>2,//设置路由地址模式为路径模式

	/* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Static',
        '__IMG__'    => __ROOT__ . '/Static/'. MODULE_NAME .'/images',
        '__CSS__'    => __ROOT__ . '/Static/'. MODULE_NAME .'/css',
        '__JS__'     => __ROOT__ . '/Static/'. MODULE_NAME .'/js',
    ),
);