var Ubrowser = {
  versions: function () {
    var u = navigator.userAgent,
    app = navigator.appVersion;
    return { //移动终端浏览器版本信息
      trident: u.indexOf('Trident') > -1, //IE内核
      presto: u.indexOf('Presto') > -1, //opera内核
      webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
      gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
      mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/), //是否为移动终端
      ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
      android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
      iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, //是否为iPhone或者QQHD浏览器
      iPad: u.indexOf('iPad') > -1, //是否iPad
      webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
    };
  }
  (),
  language: (navigator.browserLanguage || navigator.language).toLowerCase()
};
//及时加载
(function () {
  var version = 0; //定义手机操作系统
  if (Ubrowser.versions.android) { //安卓统计地址
    version = 1;
  } else if (Ubrowser.versions.iPhone) { //ios统计地址
    version = 2;
  } else { //pc统计地址
    version = 3;
  }
  var n = GetQueryString('n');
  $('#num').html(n);
  var url = 'http://data.xxx.cn/Interface/tj/open.php?t=' + version + '&n=' + n; //点击统计,后台接口新增接收t
  var script = document.createElement('script');
  script.setAttribute('src', url);
  document.getElementsByTagName('head')[0].appendChild(script);
  console.log(url);
})();

function GetQueryString(name) {
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
  var r = window.location.search.substr(1).match(reg);
  if (r != null)
    return unescape(r[2]);
  return '';
}

function download2() {
  var n = GetQueryString('n')*1;
  var version = 0;
  var url = '';
  if (Ubrowser.versions.android) {
    version = 1;
  } else if (Ubrowser.versions.iPhone) {
    version = 2;
  } else {
    version = 3;
  }
  url = 'http://data.xxx.cn/Interface/tj/download.php?t=' + version + '&n=' + n; //下载连接统计
  console.log(url);
  var script = document.createElement('script');
  script.setAttribute('src', url);
  document.getElementsByTagName('head')[0].appendChild(script);
  window.location.href = "http://www.xxx.com/download_app.html"; // 跳转到下载页面
}