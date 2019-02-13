<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

// define('USER_AGENT', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
define('USER_AGENT', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36');

function udate($format = 'u', $utimestamp = null) {
  if (is_null($utimestamp)) {
    $utimestamp = microtime(true);
  }
  $timestamp = floor($utimestamp);
  $milliseconds = round(($utimestamp - $timestamp) * 1000000);
  return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
}
/**
 *  @brief : 随机浮点数
 *  @param : [in] $min : 最大值
 *  @param : [in] $max : 最小值
 *  @return: float
 */
function random_float($min = 0, $max = 1) {
  return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}
/**
 *  @brief : 随机ip
 *  @return: string
 *  @detail: 
 */
function random_ip(){
  $a = intval(random_float(1, 255));
  $b = intval(random_float(0, 255));
  $c = intval(random_float(0, 255));
  $d = intval(random_float(0, 255));
  return "{$a}.{$b}.{$c}.{$d}";
}
/**
 *  @brief : 随机字串
 *  @return:
 *  @detail:
 *  
 *  1 =>  使用阿拉伯数字
 *  2 =>  使用标点符号
 *  4 =>  使用大写字母
 *  8 =>  使用小写字母
 */
function random_key($len=16, $flag=13){
  $str_Num  = '0123456789';
  $str_Sign = '~!@#$%^&*()_+=-`":;<>,./?[]{}';
  $str_Up   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $str_Low  = 'abcdefghijklmnopqrstuvwxyz';
  $str      = '';
  $len      = $len<=0? 16 : $len;

  if((1&$flag)==1){
    $str.=$str_Num;  // 使用阿拉伯数字
  }
  if((2&$flag)==2){
    $str.=$str_Sign; // 使用标点符号
  }
  if((4&$flag)==4){
    $str.=$str_Up;   // 使用大写字母
  }
  if((8&$flag)==8){
    $str.=$str_Low;  // 使用小写字母
  }
  if(strlen($str)==0){
    $str = $str_Num.$str_Up.$str_Low;
  }
  $str = str_shuffle($str);
  $ecx = strlen($str);
  $eax = '';
  for($i=0; $i<$len; $i++){
    $eax.= substr($str, mt_rand(0, $ecx-1), 1);
  }
  return $eax;
}
/**
 *  @brief : 日志和回显 参数跟 printf 一样
 *  @return:
 *  @detail:
 *    cclog('Listening on %s:%d', $this->address, $this->port);
 *    cclog('Server Started');
 */
function cclog(/*string $format [, mixed $args [, mixed $... ]]*/){
  $args = func_get_args();
  $num = func_num_args();
  if($num>=2){
    $format = array_shift($args);
    $str2 = vsprintf($format, $args);
  }else if($num==1){
    $str2 = $args[0];
  }
  printf("[%s] %s\n", YmdHis(), $str2);
  \think\Log::write($str2, \think\Log::LOG);
}
// 从字串 $src 中提取 特征码为$begin 到 特征码为$end 之间的字串, $include 表示是否包含特征码返回
function str_extract($src, $begin, $end, $include = false){
  $i = strpos($src, $begin);
  if($i!==false){
    $j = strpos($src, $end, $i);
    if($j!==false){
      if($include){
        return substr($src, $i, $j-$i+strlen($end));
      }else{
        $a = strlen($begin);
        return substr($src, $i+$a, $j-$i-$a);
      }
    }
  }
  return '';
}
/**
 *  @brief : curl精简封装
 *  @return: array
 *  @detail: 
 */
function curl($opts=[]){
  $ch = curl_init();
  $opts = $opts + [
    \CURLOPT_ENCODING       => 'gzip, deflate',
    \CURLOPT_HEADER         => true,
    \CURLOPT_RETURNTRANSFER => true,
    \CURLOPT_USERAGENT      => \USER_AGENT,
  ];
  curl_setopt_array($ch, $opts);
  $strm = curl_exec($ch);
  $info = curl_getinfo($ch);
  $info['curl_errno'] = curl_errno($ch);
  $info['curl_error'] = curl_error($ch);
  $headers = [];
  $cookies = [];
  $info['body'] = '';
  if($info['header_size']>0){
    $eax = explode("\r\n", substr($strm, 0, $info['header_size']));
    array_pop($eax);
    array_pop($eax);
    array_shift($eax);
    foreach($eax as $v){
      $a = explode(': ', $v, 2);
      if(stripos($a[0], 'Set-Cookie')===false){
        $headers[$a[0]] = empty($a[1]) ? '' : $a[1];
      }else{
        // "Set-Cookie: ASPSESSIONIDSWDCBQCT=MHHEEMKAMFNLLLHADLBCJCFH; secure; path=/"
        $a = explode('; ', $a[1]);
        foreach($a as $v){
          $v = explode('=', $v, 2);
          if(count($v)==2){
            $cookies[$v[0]] = $v[1];
          }
        }
      }
    }
    $info['body'] = substr($strm, $info['header_size']);
  }
  $info['headers'] = $headers;
  $info['cookies'] = $cookies;
  curl_close($ch);
  return $info;
}
/**
+----------------------------------------------------------------------------------------
* 以16进制逐字节打印出来
+----------------------------------------------------------------------------------------
*/
function print_hex($val, $prefix='0x', $print=true){
  $ret = '';
  $eax = unpack('H*', $val);
  if(count($eax)==1 && array_key_exists(1, $eax)){
    $eax = strtoupper($eax[1]);
    $eax = chunk_split($eax, 2, ',');
    $ret = rtrim($eax, ',');
    if(!empty($prefix)){
      $ret = explode(',', $ret);
      foreach($ret as $k=>&$v){
        $v = '0x'.$v;
      }
      $ret = implode(',', $ret);
    }
  }
  if($print){
    $ret = explode(',', $ret);
    $ret = array_chunk($ret, 8);
    $b = array();

    foreach($ret as $a){
      $b[] = implode(',', $a);
    }
    $ret = implode(",\n", $b);
    echo($ret."\n");
  }else{
    return $ret;
  }
}
/**
 * 生成UUID 单机使用
 * @access public
 * @return string
 */
function uuid() {
  $charid = md5(uniqid(mt_rand(), true));
  $hyphen = chr(45);// "-"
  $uuid = chr(123)// "{"
         .substr($charid, 0, 8).$hyphen
         .substr($charid, 8, 4).$hyphen
         .substr($charid,12, 4).$hyphen
         .substr($charid,16, 4).$hyphen
         .substr($charid,20,12)
         .chr(125);// "}"
  return $uuid;
}
/**
 * 生成Guid主键
 * @return Boolean
 */
function key_gen() {
  return str_replace('-', '', substr(uuid(),1,-1));
}
  /**
   *  @brief :获取网络日期时间
   *  @return: int
   *  @detail: 还有个作用是检查与外网通不通
   */
function net_time(){
  $sec = 0;
  $headers = get_headers('https://www.baidu.com/', 1);
  if(!empty($headers)){
    $sec = $headers['Date'];  // 'Sun, 18 Dec 2016 11:29:25 GMT'
    $sec = strtotime($sec);   // 1482060565
  }
  return $sec;
}

// 返回"2017-6-27 16:01:12", 用于模型 $_auto
function YmdHis($timestamp = 0) {
  return date('Y-m-d H:i:s', $timestamp > 0 ? $timestamp : time());
}
/**
 *  @brief  : 将'Y-m-d H:i:s'或'Y-m-d'格式的时间转换成`Unix`时间戳
 *  @param [in] $YmdHis : 'Y-m-d H:i:s'或'Y-m-d'格式的时间
 *  @return : 失败返回0, 成功返回正整数
 *  @details: 因为`strtotime`有bug!
 *  '2017-06-23 03:03:18'  => 1498186998
 */
function YmdHis2Unix($YmdHis) {
  $eax = sscanf($YmdHis, '%04d-%02d-%02d %02d:%02d:%02d', $Y, $m, $d, $H, $i, $s);
  if ($eax === 6) { // 'Y-m-d H:i:s'
    $eax = mktime($H, $i, $s, $m, $d, $Y);
  } else if ($eax === 3) { // 'Y-m-d'
    $eax = mktime(0, 0, 0, $m, $d, $Y);
  } else {
    $eax = 0;
  }
  return $eax;
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
  // 创建Tree
  $tree = array();
  if(is_array($list)) {
    // 创建基于主键的数组引用
    $refer = array();
    foreach ($list as $key => $data) {
      $refer[$data[$pk]] =& $list[$key];
    }
    foreach ($list as $key => $data) {
      // 判断是否存在parent
      $parentId =  $data[$pid];
      if ($root == $parentId) {
        $tree[] =& $list[$key];
      }else{
        if (isset($refer[$parentId])) {
          $parent =& $refer[$parentId];
          $parent[$child][] =& $list[$key];
        }
      }
    }
  }
  return $tree;
}
