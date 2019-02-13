<?php
// 应用行为扩展定义文件, 按照执行顺序排列
return [
  'app_init'     => [ // 应用初始化
    //'app\\behavior\\Oa',
  ],
  'app_begin'    => [ // 应用开始
    //'app\\behavior\\Oa',
  ],
  'module_init'  => [ // 模块初始化
    //'app\\behavior\\Oa',
  ],
  'action_begin' => [ // 操作开始执行
    //'app\\behavior\\Oa',
  ],
  'view_filter'  => [ // 视图内容过滤
    //'app\\behavior\\Oa',
  ],
  'app_end'      => [ // 应用结束
    //'app\\behavior\\Oa',
  ],
  'response_send' => [  // 响应发送标签位（V5.0.10+）
    //'app\\behavior\\Oa',
  ],
  'response_end'  => [  // 输出结束标签位（V5.0.1+）
    //'app\\behavior\\Oa',
  ],
  'log_write'    => [ // 日志写入
    //'app\\behavior\\Oa',
  ],
];
