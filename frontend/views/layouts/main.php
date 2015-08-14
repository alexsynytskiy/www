<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\models\SiteBlock;
use common\models\Banner;
use common\models\TopTag;

use common\modules\user\models\User;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="google-site-verification" content="z06gXFIAf0gFtEMibI9t_vnvEmpvMC-icWTq7xX8Yyw"/>

    <?php $site_logo = 'http://' . $_SERVER['HTTP_HOST'] . '/images/main_logo.svg'; ?>
    <meta property="og:image" content="<?= $site_logo ?>"/>
    <meta itemprop="thumbnailUrl" content="<?= $site_logo ?>"/>
    <meta name="twitter:image" content="<?= $site_logo ?>"/>

    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->

    <link rel="shortcut icon" href="/images/favicon.png" type="image/png" />

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body>
<?php $this->beginBody() ?>

<!-- Preloadding animation START 
<div id="loading">
    <div id="loading-center">
        <div id="loading-center-absolute">
            <div class="object" id="object_one"></div>
            <div class="object" id="object_two"></div>
            <div class="object" id="object_three"></div>
        </div>
    </div>
</div>
Preloadding animation END -->

<div id="page">
    <div id="banner-wrapper">
        <div class="hide-area"></div>
        <?php
        $bannerBlock = SiteBlock::getBanner(Banner::REGION_WRAPPER);
        if($bannerBlock) {
            echo $this->render($bannerBlock['view'], isset($bannerBlock['data']) ? $bannerBlock['data'] : []);
        }
        ?>
    </div>
    <header>
        <div class="header-wrapper">
            <div class="header-top-part">

                <a href="/"><div class="logo"></div></a>

                <?php if(Yii::$app->user->can('admin')) { ?>
                    <a class="admin-page-link" href="/admin" target="_blank"></a>
                <?php } ?>

                <div class="navigation-bar">
                    <?php if(Yii::$app->user->isGuest) { ?>

                        <div class="navigation-block-right">
                            <div class="navigation-block top-block">
                                <a href="/user/register"><div class="registration">Регистрация</div></a>
                                <a href="/user/login"><div class="sign-in">Войти</div></a>
                            </div>

                            <div class="navigation-block bottom-block">
                                <a href="<?= Url::to('/forum-rules') ?>">
                                    <div class="rules">Правила форума</div>
                                    <div class="icon"></div>
                                </a>
                            </div>
                        </div>

                    <?php } else { ?>

                        <?php
                        $user = User::findOne(Yii::$app->user->id);
                        $avatar = $user->getAsset();
                        ?>
                        <div class="logged-in">
                            <div class="photo">
                                <a href="<?= Url::to(['/user/profile']) ?>"><img src="<?= $avatar->getFileUrl() ?>"></a>
                            </div>
                            <div class="main-functions">
                                <div class="name"><?= $user->getDisplayName() ?></div>
                                <a href="<?= Url::to(['/post/add']) ?>">
                                    <div class="create-post">
                                        Создать пост
                                        <div class="icon"></div>
                                    </div>

                                </a>
                                <a href="<?= Url::to(['/user/profile']) ?>"><div class="link-to-cabinet">Личный Кабинет</div></a>
                            </div>
                            <a href="<?= Url::to(['/user/logout']) ?>">
                                <div class="logout">
                                    <div class="icon"></div>
                                </div>
                            </a>
                        </div>

                    <?php } ?>

                    <div class="social-buttons">
                        <a target="_blank" href="https://www.youtube.com/channel/UCkNGCbO2tUI6y_6Ka_DKRDQ"><div class="button youtube"></div></a>
                        <a target="_blank" href="https://vk.com/dynamomaniacom"><div class="button vk"></div></a>
                        <a target="_blank" href="https://twitter.com/dynamomania_com"><div class="button twitter"></div></a>
                        <a target="_blank" href="https://www.facebook.com/dynamomaniacom"><div class="button fb"></div></a>
                        <a target="_blank" href="/rss.xml"><div class="button rss"></div></a>
                    </div>
                </div>

            </div>

            <div class="menu">
                <ul>
                    <!-- <a href="#"><li class="special-project">Спецпроект</li></a> -->
                    <a href="<?= Url::to(['/site/news']) ?>">
                        <li class="<?= Yii::$app->controller->action->id == 'news' ? 'current-page' : '' ?>">Новости</li>
                    </a>
                    <?php
                    $teamControllers = [
                        'team',
                        'player',
                        'coach',
                    ];
                    ?>
                    <a href="<?= Url::to(['/site/team', 'tab' => 'composition']) ?>">
                        <li class="<?= in_array(Yii::$app->controller->action->id, $teamControllers) ? 'current-page' : '' ?>">Команда</li>
                    </a>
                    <?php
                    $matchControllers = [
                        'matches',
                        'match-translation',
                        'match-protocol',
                        'match-report',
                        'match-news',
                        'match-videos',
                        'match-photos',
                    ];
                    ?>
                    <a href="<?= Url::to(['/site/matches']) ?>">
                        <li class="<?= in_array(Yii::$app->controller->action->id, $matchControllers) ? 'current-page' : '' ?>">Матчи</li>
                    </a>
                    <a href="<?= Url::to(['/site/transfers']) ?>">
                        <li class="<?= Yii::$app->controller->action->id == 'transfers' ? 'current-page' : '' ?>">Трансферы</li>
                    </a>
                    <a href="<?= Url::to(['/site/blogs']) ?>">
                        <li class="<?= Yii::$app->controller->action->id == 'blogs' ? 'current-page' : '' ?>">Блоги</li>
                    </a>
                    <a href="<?= Url::to(['/site/photos']) ?>">
                        <li class="<?= Yii::$app->controller->action->id == 'photos' ? 'current-page' : '' ?>">Фото</li>
                    </a>
                    <a href="<?= Url::to(['/site/videos']) ?>">
                        <li class="<?= Yii::$app->controller->action->id == 'videos' ? 'current-page' : '' ?>">Видео</li>
                    </a>
                </ul>

                <div class="search">
                    <form action="/search" method="get">
                        <input type="text" name="q" class="search-textarea" placeholder="Поиск">
                    </form>
                    <div class="search-icon"></div>
                </div>
            </div>

            <div class="top-banners-area">
                <?php
                $bannerBlock = SiteBlock::getBanner(Banner::REGION_TOP);
                if($bannerBlock) {
                    echo $this->render($bannerBlock['view'], isset($bannerBlock['data']) ? $bannerBlock['data'] : []);
                }
                ?>
            </div>

            <div class="breadcrumbs">
                <div class="header">Главное</div>
                <div class="arrow"></div>
                <?= TopTag::outTop8Links() ?>
            </div>

        </div>
    </header>

    <div id="wrapper">
        <?= $content ?>
    </div>

    <footer id="colophon" class="site-footer" role="contentinfo">
        <div class="footer-wrapper">

            <div class="bottom-banners-area">
                <?php
                $bannerBlock = SiteBlock::getBanner(Banner::REGION_BOTTOM);
                if($bannerBlock) {
                    echo $this->render($bannerBlock['view'], isset($bannerBlock['data']) ? $bannerBlock['data'] : []);
                }
                ?>
            </div>

            <div class="footer-bottom">
                <div class="block-top">
                    <a target="_blank" href="/rss.xml">
                        <div class="button rss"></div>
                        <div class="text">RSS</div>
                    </a>
                    <a href="<?= Url::to('/information') ?>">
                        <div class="button inform"></div>
                        <div class="text">Информация</div>
                    </a>
                    <a href="<?= Url::to('/contacts') ?>">
                        <div class="button contact"></div>
                        <div class="text">Контакты</div>
                    </a>

                    <div class="counters">
                        <!--bigmir)net TOP 100 Part 1-->
                        <script type="text/javascript" language="javascript"><!--
                            bmN=navigator,bmD=document,bmD.cookie='b=b',i=0,bs=[],bm={o:1,v:14224,s:14224,t:6,c:bmD.cookie?1:0,n:Math.round((Math.random()* 1000000)),w:0};
                            for(var f=self;f!=f.parent;f=f.parent)bm.w++;
                            try{if(bmN.plugins&&bmN.mimeTypes.length&&(x=bmN.plugins['Shockwave Flash']))bm.m=parseInt(x.description.replace(/([a-zA-Z]|\s)+/,''));
                            else for(var f=3;f<20;f++)if(eval('new ActiveXObject("ShockwaveFlash.ShockwaveFlash.'+f+'")'))bm.m=f}catch(e){;}
                            try{bm.y=bmN.javaEnabled()?1:0}catch(e){;}
                            try{bmS=screen;bm.v^=bm.d=bmS.colorDepth||bmS.pixelDepth;bm.v^=bm.r=bmS.width}catch(e){;}
                            r=bmD.referrer.replace(/^w+:\/\//,'');if(r&&r.split('/')[0]!=window.location.host){bm.f=escape(r).slice(0,400).slice(0,400);bm.v^=r.length}
                            bm.v^=window.location.href.length;for(var x in bm) if(/^[ovstcnwmydrf]$/.test(x)) bs[i++]=x+bm[x];
                            bmD.write('<sc'+'ript type="text/javascript" language="javascript" src="//c.bigmir.net/?'+bs.join('&')+'"></sc'+'ript>');
                            //-->
                        </script>
                        <noscript><img src="//c.bigmir.net/?v14224&s14224&t6" width="0" height="0" alt="" title="" border="0" /></noscript>
                        <!--bigmir)net TOP 100 Part 1-->
                    <!--bigmir)net TOP 100 Part 2-->
                    <script type="text/javascript" language="javascript"><!--
                        function BM_Draw(oBM_STAT){
                            document.write('<table cellpadding="0" cellspacing="0" border="0" style="display:inline;margin-right:4px;float:left;"><tr><td><div style="margin:0;padding:0;font-size:1px;width:88px;"><div style="background:url(\'http://i.bigmir.net/cnt/samples/diagonal/b60_top.gif\') no-repeat bottom;">&nbsp;</div><div style="font:10px Tahoma;background:url(\'http://i.bigmir.net/cnt/samples/diagonal/b60_center.gif\');"><div style="text-align:center;"><a href="http://www.bigmir.net/" target="_blank" style="color:#0000ab;text-decoration:none;font:10px Tahoma;">bigmir<span style="color:#ff0000;">)</span>net</a></div><div style="margin-top:3px;padding: 0px 6px 0px 6px;color:#426ed2;"><div style="float:left;font:10px Tahoma;">'+oBM_STAT.hosts+'</div><div style="float:right;font:10px Tahoma;">'+oBM_STAT.hits+'</div></div><br clear="all"/></div><div style="background:url(\'http://i.bigmir.net/cnt/samples/diagonal/b60_bottom.gif\') no-repeat top;">&nbsp;</div></div></td></tr></table>');
                        }
                        //-->
                    </script>

                    <script type="text/javascript" language="javascript" src="http://c.bigmir.net/?s14224&t0&l1&o1"></script>
                    <noscript>
                        <a href="http://www.bigmir.net/" target="_blank"><img src="http://c.bigmir.net/?v14224&s14224&t2&l1" width="88" height="31" alt="bigmir)net TOP 100" title="bigmir)net TOP 100" border="0" /></a>
                    </noscript>

                    <!--bigmir)net TOP 100 Part 2-->

                    <!--begin of Top100 logo-->
                    <a href="http://top100.rambler.ru/top100/"><img src="http://top100-images.rambler.ru/top100/banner-88x31-rambler-blue3.gif" alt="Rambler's Top100" width=88 height=31 border=0></a>
                    <!--end of Top100 logo -->

                    <!-- BEGIN OF PING CODE -->
                    <A HREF="http://www.topping.com.ua/" target="_top">
                        <font size="1" color="#FFFFFF">
                            <script>
                                //<!--
                                id='091201064715';
                                img='count30';
                                an=navigator.appName; d=document;  w=''; c='';
                                script='http://counter.topping.com.ua:80/cgi-bin/pinger.cgi';
                                function ping() {
                                    d.write("<img src=\""+script+"?id="+id+"&img="+img+"&w="+w+"&c="+c+"&ref="+escape(d.referrer)+"\" width=88 height=31 border=0 alt=\"Rated by PING\"></a>");
                                }
                                //-->
                            </script>
                            <script language="javascript1.2">
                                //<!--
                                s=screen;
                                w=s.width;
                                an!="Netscape"?c=s.colorDepth:c=s.pixelDepth
                                //-->
                            </script>
                            <script>
                                //<!--
                                ping()
                                //-->
                            </script>
                            <noscript>
                                <img src="http://counter.topping.com.ua:80/cgi-bin/ping.cgi?091201064715;count30" width=88 height=31 border=0 alt="Rated by PING"></a>;
                    </noscript>
                    </FONT>
                    <!-- END OF PING CODE -->

                    <!--LiveInternet counter--><script language="JavaScript"><!--
                        document.write('<a href="http://www.liveinternet.ru/click" '+
                            'target=_blank><img src="http://counter.yadro.ru/hit?t52.6;r'+
                            escape(document.referrer)+((typeof(screen)=='undefined')?'':
                            ';s'+screen.width+'*'+screen.height+'*'+(screen.colorDepth?
                                screen.colorDepth:screen.pixelDepth))+';u'+escape(document.URL)+
                            ';i'+escape('Жж'+document.title.substring(0,80))+';'+Math.random()+
                            '" title="liveinternet.ru: показано число просмотров и посетителей за 24 часа" '+
                            'border=0 width=88 height=31></a>')//--></script><!--/LiveInternet-->
                    </div>

                </div>
                <div class="text-bottom">
                    Copyright © 2001-2015 Dynamomania.com. При использовании материалов сайта гиперссылка на <a href=""><div class="link-to-main">www.dynamomania.com</div></a> обязательна.
                </div>
            </div>
        </div>

        <!-- head -->
        <script type='text/javascript' src='http://partner.googleadservices.com/gampad/google_service.js'></script>
        <script type='text/javascript'>
            GS_googleAddAdSenseService("ca-pub-0353612203213313");
            GS_googleEnableAllServices();
        </script>
        <script type='text/javascript'>
            GA_googleAddSlot("ca-pub-0353612203213313", "468_60_bottom");
        </script>
        <script type='text/javascript'>
            GA_googleFetchAds();
        </script>
        <!-- (C)2000-2010 Gemius SA - gemiusAudience / dynamomania.com / Glavnaja stranitsa sajta -->
        <script type="text/javascript">
            <!--//--><![CDATA[//><!--
            var pp_gemius_identifier = new String('B7Y1NCL5wX9IuEzl1qnxZ2aDDfxZJI9iCkyI46qixVn.w7');
            //--><!]]>
        </script>

        <script type='text/javascript'>
            var googletag = googletag || {};
            googletag.cmd = googletag.cmd || [];
            (function() {
                var gads = document.createElement('script');
                gads.async = true;
                gads.type = 'text/javascript';
                var useSSL = 'https:' == document.location.protocol;
                gads.src = (useSSL ? 'https:' : 'http:') +
                    '//www.googletagservices.com/tag/js/gpt.js';
                var node = document.getElementsByTagName('script')[0];
                node.parentNode.insertBefore(gads, node);
            })();
        </script>

        <script type='text/javascript'>
            var googletag = googletag || {};
            googletag.cmd = googletag.cmd || [];
            (function() {
                var gads = document.createElement('script');
                gads.async = true;
                gads.type = 'text/javascript';
                var useSSL = 'https:' == document.location.protocol;
                gads.src = (useSSL ? 'https:' : 'http:') +
                    '//www.googletagservices.com/tag/js/gpt.js';
                var node = document.getElementsByTagName('script')[0];
                node.parentNode.insertBefore(gads, node);
            })();
        </script>

        <script type='text/javascript'>
            var googletag = googletag || {};
            googletag.cmd = googletag.cmd || [];
            (function() {
                var gads = document.createElement('script');
                gads.async = true;
                gads.type = 'text/javascript';
                var useSSL = 'https:' == document.location.protocol;
                gads.src = (useSSL ? 'https:' : 'http:') +
                    '//www.googletagservices.com/tag/js/gpt.js';
                var node = document.getElementsByTagName('script')[0];
                node.parentNode.insertBefore(gads, node);
            })();
        </script>

        <script type='text/javascript'>
            (function() {
                var useSSL = 'https:' == document.location.protocol;
                var src = (useSSL ? 'https:' : 'http:') +
                    '//www.googletagservices.com/tag/js/gpt.js';
                document.write('<scr' + 'ipt src="' + src + '"></scr' + 'ipt>');
            })();
        </script>

        <script type='text/javascript'>
            (function () {
                var w = window, d = document;
                w.admixZArr = (w.admixZArr || []);
                w.admixerSmOptions = (w.admixerSmOptions || {});
                w.admixerSmOptions.showAdsOnLoad = true;
            })();
        </script>
        <script type="text/javascript" src="http://cdn.admixer.net/scriptlib/asm2.js?v=3"></script>

        <script type="text/javascript">
            (function(){
                var j=137290,f=false,b=document,c=b.documentElement,e=window;
                function g(){
                    var a="";
                    a+="rt="+(new Date).getTime()%1E7*100+Math.round(Math.random()*99);
                    a+=b.referrer?"&r="+escape(b.referrer):"";
                    return a
                }
                function h(){
                    var a=b.getElementsByTagName("head")[0];
                    if(a)return a;
                    for(a=c.firstChild;a&&a.nodeName.toLowerCase()=="#text";)
                        a=a.nextSibling;if(a&&a.nodeName.toLowerCase()!="#text")
                        return a;
                    a=b.createElement("head");c.appendChild(a);
                    return a
                }
                function i(){
                    var a=b.createElement("script");
                    a.setAttribute("type","text/javascript");
                    a.setAttribute("src","http://c.luxup.ru/t/lb"+j+".js?"+g());
                    typeof a!="undefined"&&h().appendChild(a)
                }
                function d(){
                    if(!f){f=true;i()}};
                if(b.addEventListener)b.addEventListener("DOMContentLoaded",d,false);
                else if(b.attachEvent){
                    c.doScroll&&e==e.top&&function(){
                        try{
                            c.doScroll("left")
                        }catch(a){
                            setTimeout(arguments.callee,0);
                            return
                        }d()}();
                    b.attachEvent("onreadystatechange",function(){
                        b.readyState==="complete"&&d()
                    })}
                else e.onload=d})();
        </script>

        <script type="text/javascript">
            var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
            document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
        </script>
        <script type="text/javascript">
            try {
                var pageTracker = _gat._getTracker("UA-10830874-1");
                pageTracker._trackPageview();
            } catch(err) {}</script>
        
        <div id="admixer_async_1699536607"></div>
        <script type="text/javascript">
            window.admixZArr = (window.admixZArr || []);
            window.admixZArr.push({ z: '36f685cd-9ce4-4940-86ae-a51d202c6bac', ph: 'admixer_async_1699536607', ts: {}});
        </script>

        <script type="text/javascript" src="http://mg.dt00.net/js/d/y/dynamomania.com.i4.js" charset="utf-8"></script>
        <script type="text/javascript" src="http://mg.dt00.net/js/d/y/dynamomania.com.i2.js" charset="utf-8"></script>
        <script type="text/javascript" src="http://mg.dt00.net/gjs/d/y/dynamomania.com.143.js" charset="utf-8"></script>
        <script type="text/javascript" src="http://mg.dt00.net/js/d/y/dynamomania.com.i3.js" charset="utf-8"></script>

        <!-- pered </body> -->
        <script type="text/javascript">
            var MarketGidDate = new Date();
            document.write('<scr'+'ipt type="text/javascript" '
                +'src="//jsn.marketgid.com/d/y/dynamomania.com.i4.js?t='+MarketGidDate.getYear()+MarketGidDate.getMonth()+MarketGidDate.getDay()+MarketGidDate.getHours() + '" charset="utf-8" ></scr'+'ipt>');
        </script>


        <!-- begin of Top100 code -->
        <script id="top100Counter" type="text/javascript" src="http://counter.rambler.ru/top100.jcn?266517"></script><noscript><img src="http://counter.rambler.ru/top100.cnt?266517" alt="" width="1" height="1" border="0"></noscript>
        <!-- end of Top100 code -->

        <script charset="windows-1251" type="text/javascript" src="http://www.directadvert.ru/show.cgi?adp=77053&div=DIV_DA_77053"></script>

        <!-- MMI CMeter -->
        <noscript>
            <img
                src="http://juke.mmi.bemobile.ua/bug/pic.gif?siteid=dynamomania.com"
                style="position:absolute;left:-1000px;"
                alt=""
                />
        </noscript>
        <script language="javascript">
            var tns_already;
            if ("undefined"==typeof(tns_already) || null==tns_already || 0==tns_already)
            {
                tns_already=1;
                var i=new Image();
                i.src="http://juke.mmi.bemobile.ua/bug/pic.gif?siteid=dynamomania.com&j=1&"+Math.random
                    ();

                (function(){
                    var p=document.getElementsByTagName('head')[0];
                    var s=document.createElement("script");
                    s.type="text/javascript";
                    s.src="http://source.mmi.bemobile.ua/id/id.js";
                    s.async = true;
                    s.onload=s.onreadystatechange=function(){
                        if(!("readyState" in s)||/loaded|complete/.test(s.readyState)){
                            s.onload=s.onreadystatechange=null;
                            var s2=document.createElement("script");
                            s2.type="text/javascript";
                            s2.src="http://source.mmi.bemobile.ua/cm/cmeter.js";
                            s2.async = true;
                            var p=document.getElementsByTagName('head')[0];
                            p.appendChild(s2);
                        }}
                    p.appendChild(s);
                })();
            };
        </script>
        <!-- /MMI CMeter -->

        <!-- before </body> -->
        <script type="text/javascript">
            var el = document.getElementById('CNM349');
            if (el) {
                if (document.getElementById('CNM349t').style.display == 'none') {
                    document.getElementById('CNM349t').style.display = '';
                    var dateNM = new Date();
                    var t = Math.floor(dateNM.getTime()/(1000*600));
                    var NMces=document.createElement('script');
                    NMces.type = 'text/javascript';
                    NMces.charset = 'UTF-8';
                    NMces.src='http://c.novostimira.biz/l/349?v='+t;
                    el.parentNode.appendChild(NMces);
                }
            }
        </script>

        <!-- before </body> -->
        <script type="text/javascript">
            var el = document.getElementById('CNM145');
            if (el) {
                if (document.getElementById('CNM145t').style.display == 'none') {
                    document.getElementById('CNM145t').style.display = '';
                    var dateNM = new Date();
                    var t = Math.floor(dateNM.getTime()/(1000*600));
                    var NMces=document.createElement('script');
                    NMces.type = 'text/javascript';
                    NMces.charset = 'UTF-8';
                    NMces.src='http://c.novostimira.biz/l/145?v='+t;
                    el.parentNode.appendChild(NMces);
                }
            }
        </script>

        <script type="text/javascript">
            var utarget_rand = Math.floor(Math.random()*10000);
            var utarget_ref = escape(document.referrer);
            var utarget_cookie = document.cookie.indexOf("u_d6d6bca0e1=")+1;
            var utarget_src="http://utarget.ru/jsclck/d6d6bca0e1/?ref="+utarget_ref+"&cookie="+ utarget_cookie +"&rand="+utarget_rand;
            document.write("<sc"+"ript type='text/javascript' src='"+utarget_src+"'></scr"+"ipt>");
        </script>

        <script type="text/javascript">
            if (document.getElementById('RTBDIV_8708')) {
                var RtbSystemDate = new Date();
                document.write('<scr'+'ipt type="text/javascript" async '
                    +'src="http://code.rtbsystem.com/8708.js?t='+RtbSystemDate.getYear()+RtbSystemDate.getMonth()
                    +RtbSystemDate.getDay()+RtbSystemDate.getHours() + '" charset="utf-8" ></scr'+'ipt>');
            }
        </script>

        <script type="text/javascript">
            (function() {
                var kdm = document.createElement('script'); kdm.type = "text/javascript"; kdm.async = true;
                kdm.src = "http://vogosita.com/b9782271ff0ffdcf5bc612d4134f52ce.js";
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(kdm, s);
            })();
        </script>

        <!-- hstpnetwork ads /c0UvTdmkLhcls+DychKwugWgBP5YvIPy+hnNs5CTqs=-->
        <script type="text/javascript">
            (function (document, window) {
                var c = document.createElement("script");
                c.type = "text/javascript";
                c.async = !0;
                c.src = "http://contentjs.com/scripts/e89282ddd7d405bb7bfe2b8684a68260.min.js?20150512=" + Math.floor((new Date).getTime() / 1E3);
                var a = !1;
                try {
                    a = parent.document.getElementsByTagName("script")[0] || document.getElementsByTagName("script")[0];
                } catch (e) {
                    a = !1;
                }
                a || ( a = document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]);
                a.parentNode.insertBefore(c, a);
            })(document, window);
        </script>
        <!-- end hstpnetwork ads -->

        <!-- GoodADVERT (SMART VIDEO in text) for http://dynamomania.com.ua -->
        <script type="text/javascript"><!--
            var _ga1_channel='10928';
            (function() {
                var s = document.createElement('script'); s.type = 'text/javascript'; s.charset = 'utf-8'; s.async = true; s.src = 'http://files.goodadvert.ru/ga_1.js';
                var o = document.getElementsByTagName('script')[0]; o.parentNode.insertBefore(s, o);
            })();
            // -->
        </script>
        <!-- GoodADVERT -->
    </footer>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
