<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja"><!-- InstanceBegin template="/Templates/default.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-style-type" content="text/css; charset=utf-8" />
<meta content="先端翼を有する小口径柱状地盤補強" name="description" />
<meta content="SSW-Pile工法,地盤補強,パイル,地盤改良,杭,支持杭" name="keywords" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>SSW-Pile Small-Size Wing-Pile</title>
<!-- InstanceEndEditable -->
<link type="text/css" href="/css/import.css" rel="stylesheet" media="all" />
<!-- InstanceBeginEditable name="head" -->
<link type="text/css" href="/css/page_private_office.css" rel="stylesheet" media="all" />
{{ Asset::styles() }}
<!-- InstanceEndEditable -->
<!-- InstanceParam name="top_navi" type="boolean" value="false" -->
</head>

<body>
<div id="wrapper">
<div id="header">
<h1 id="description">先端翼を有する小口径柱状地盤補強</h1>
<p id="header-logo"><a href="/index.html"><img src="/images/logo.png" width="180" height="62" alt="SSW-Pile Small-Size Wing-Pile" /></a></p>
<p id="header-tel"><img src="/images/header_tel.png" width="200" height="47" alt="052-877-8281:お気軽にお問い合せください" /></p>
<p id="header-email"><a href="/contact/index.html"><img src="/images/header_email.png" width="47" height="47" alt="email" /></a></p>
<!-- /#header --></div>
<ul id="globalnavi" class="clearfix">
<li class="home"><a href="/index.html"><img src="/images/navi_home.jpg" width="60" height="57" alt="" /></a></li>
<li><a href="/about/index.html"><img src="/images/ico_navi.png" width="10" height="10" alt="" class="ico" />SSW-Pile工法について</a></li>
<li><a href="/society/index.html"><img src="/images/ico_navi.png" width="10" height="10" alt="" class="ico" />SSW-Pile工法協会について</a></li>
<li><a href="/case/index.html"><img src="/images/ico_navi.png" width="10" height="10" alt="" class="ico" />施工実績</a></li>
<li><a href="/catalog/index.html"><img src="/images/ico_navi.png" width="10" height="10" alt="" class="ico" />カタログダウンロード</a></li>
<!-- /globalnavi --></ul>
<div id="container"><!-- InstanceBeginEditable name="main" -->
  <div id="maincolumn" class="clearfix">
    <img src="/images/private/page_logo2.png" style="margin-left: 10px; margin-top: -20px;"/>
    <div class="section clearfix">
      <div class="private">
        <div class="sidebar">
          <p class="link_header">会員管理</p>
            <p class="link"><a href="{{ URL::to('office/member_register.html') }}">会員登録</a></p>
            <p class="link"><a href="{{ URL::to('office/member_change_search.html') }}">会員情報閲覧/変更</a></p>
          <br>
          <p class="link_header">先端パーツ受注管理</p>
            <p class="link"><a href="{{ URL::to('office/order_search.html') }}">受注情報閲覧/変更</a></p>
            <p class="link"><a href="{{ URL::to('office/parts_price_search.html') }}">パーツ代金精算</a></p>
          <br>
          <p class="link_header">物件管理</p>
            <p class="link"><a href="{{ URL::to('office/thing_register.html') }}">物件登録</a></p>
            <p class="link"><a href="{{ URL::to('office/thing_search.html') }}">物件情報閲覧/変更</a></p>
          <br>
          <p class="link_header">請求管理</p>
            <p class="link"><a href="{{ URL::to('office/request_search.html') }}">請求対象検索</a></p>
          <br>
          <p class="link_header">集計処理</p>
            <p class="link"><a href="{{ URL::to('office/aggregate_search.html') }}">集計</a></p>
            <p class="link"><a href="{{ URL::to('office/performance_search.html') }}">帳票出力</a></p>
          <br>
          <p class="link_header">協会情報</p>
            <p class="link"><a href="{{ URL::to('office/society_register.html') }}">協会基本情報</a></p>
          <br>
          <p class="link_header">システム設定</p>
            <p class="link"><a href="{{ URL::to('office/system_unit.html') }}">単価設定</a></p>
            <p class="link"><a href="{{ URL::to('office/system_tax.html') }}">消費税設定</a></p>
            <p class="link"><a href="{{ URL::to('office/system_material.html') }}">材種設定</a></p>
            <p class="link"><a href="{{ URL::to('office/system_engineer.html') }}">施工管理技術者設定</a></p>
            <p class="link"><a href="{{ URL::to('office/system_architect.html') }}">設計担当者設定</a></p>
          <br>
          <p class="link"><a href="{{ URL::to('office/news.html') }}">事務局からのお知らせ</a></p>
          <br>
          <div class="top_logout">
            <p class="link2"><img class="logout" src="/images/private/logout.png"/><a href="{{ URL::to('logout.html') }}">ログアウト</a></p>
          </div>
        </div>

        <div class="private_body">
@yield('title')
          <img src="/images/private/line.png"/>
          <div id="notification_area">
            <p class="notification success"></p>
            <p class="notification info"></p>
            <p class="notification warn"></p>
            <p class="notification error"></p>
          </div>
@yield('main')
          <!-- /#private_body -->
        </div>
        <!-- /#private -->
      </div>
      <p class="mb30"><img src="/images/private/footer.png" width="980" height="15" alt="" /></p>
      <!-- /section -->
    </div>
    <p id="page_top"><a href="javascript:scrollTop();" class="scroll">▲PageTOP</a></p>
    <!-- /#maincolumn -->
  </div>
<!-- InstanceEndEditable --><!-- /#container --></div>
<div id="footer">
<div id="footer_inner">
<ul id="footer-navi" class="clearfix">
<li><a href="/index.html">TOP</a></li>
<li><a href="/about/index.html">SSW-Pile工法について</a></li>
<li><a href="/society/index.html">SSW-Pile工法協会について</a></li>
<li><a href="/case/index.html">施行実績</a></li>
<li><a href="/catalog/index.html">カタログダウンロード</a></li>
<li><a href="/contact/index.html">お問い合わせ</a></li>
<li class="last"><a href="#">サイトマップ</a></li>
<!-- /#footer-navi --></ul>
<p id="footer-add">【SSW-Pile工法協会 事務局】　株式会社奈良重機工事<br />
  〒458-0023　愛知県名古屋市緑区鴻仏目1-115　｜　TEL：052-877-8281</p>
<p id="footer-banner"><a href="{{ URL::base() }}"><img src="/images/footer_bnr.jpg" width="254" height="53" alt="指定施工会社専用ページ" /></a></p>
<p id="copyright">Copyright (C) 2013 SSW-Pile. All Rights Reserved.</p>
<!-- /#footer_inner --></div>
<!-- /#footer --></div>
<!-- /#wrapper --></div>
{{ Asset::scripts() }}
</body>
<!-- InstanceEnd --></html>
