<?php

// 路由名称不要以斜线 / 开头

return [

  'index'   => [app\controller\IndexController::class, "index"],
  'news'    => [app\controller\IndexController::class, "news"],

  // 可以给控制器传入参数：

  'test'    => [app\controller\TestController::class, "test",  ["Jon"]],
  'test/a'  => [app\controller\TestController::class, "testa", ["Alice", 26]],

];
