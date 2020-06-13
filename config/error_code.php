<?php

const INVALID_TOKEN = 10001;
const ACCOUNT_HAD_FREEZE = 10002;
const ACCOUNT_OR_PWD_ERROR = 10003;
const TOKEN_NOT_PROVIDED = 10004;
const TOKEN_BLACKLISTED = 10005;
const INCORRECT_IDENTIFY_INFORMATION = 10006;
const UNRESOLVED_TOKEN = 10007;
const TOKEN_HAS_EXPIRED = 10008;

const ERROR_CODE = 10400;
const SYSTEM_METHOD_NOT_EXISI= 10404;
const PERMISSION_DENIED= 10403;
const METHOD_NOT_ALLOWED= 10405;
const VALIDATION_ERROR = 10422;
const SYSTEM_ERROR= 10500;

return [
    10001 => '无效的 Token',
    10002 => '账号已被冻结',
    10003 => '账号或密码错误',
    10004 => '未提供令牌',
    10005 => '身份信息已失效，请重新登录',
    10006 => '身份信息不正确',
    10007 => '无法解析 Token',
    10008 => '无效的 Token',

    10400 => '没有数据',
    10403 => '没有权限',
    10404 => '路由不存在',
    10405 => '不允许的 Method',
    10422 => '参数验证错误',

    10500 => '系统内部错误'
];
