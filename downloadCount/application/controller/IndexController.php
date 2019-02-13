<?php
namespace app\controller;

class IndexController extends \think\Controller {
  
  public function indexAction(){
    
    $isMobile = $this->request->isMobile();
    $n = $this->request->param('n/d', 0); // 渠道号
    $n = $n > 0 ? $n : 0;
    $t = $this->request->param('t/d', 0); // 1=安卓;2=苹果;3=PC
    $t = in_array($t, [1,2,3]) ? $t : 3;
    $template = $isMobile ? 'wap' : 'pc';
    return $this->fetch("index_{$template}", [
      'isMobile'  => $isMobile,
      'n' => $n,
      't' => $t,
    ]);
  }
  
}
