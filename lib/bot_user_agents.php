<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

define('ZDM__BOT_USER_AGENTS', [
    // 360Spider
    'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1; 360Spider(compatible; HaosouSpider; http://www.haosou.com/help/help_3_2.html)',
    'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36 QIHU 360SE; 360Spider',
    'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; )  Firefox/1.5.0.11; 360Spider',
    'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.8.0.11)  Firefox/1.5.0.11; 360Spider',
    'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.8.0.11) Firefox/1.5.0.11 360Spider;',
    'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.8.0.11) Gecko/20070312 Firefox/1.5.0.11; 360Spider',
    'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0); 360Spider',
    'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0); 360Spider(compatible; HaosouSpider; http://www.haosou.com/help/help_3_2.html)',
    'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36; 360Spider',
    // Ahrefs
    'Mozilla/5.0 (compatible; AhrefsBot/4.0; +http://ahrefs.com/robot/)', // Ahrefs Backlink Research Bot 4
    'Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)', // Ahrefs Backlink Research Bot 5
    'Mozilla/5.0 (compatible; AhrefsBot/5.1; +http://ahrefs.com/robot/)', // Ahrefs Backlink Research Bot 5.1
    'Mozilla/5.0 (compatible; AhrefsBot/5.2; +http://ahrefs.com/robot/)', // Ahrefs Backlink Research Bot 5.2
    'Mozilla/5.0 (compatible; AhrefsBot/6.1; +http://ahrefs.com/robot/)', // Ahrefs Backlink Research Bot 6.1
    'Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)', // Ahrefs Backlink Research Bot 7.0
    // Alexa
    'ia_archiver', // Alexa Bot
    'ia_archiver-web.archive.org', // Alexa Bot
    'ia_archiver (+http://www.alexa.com/site/help/webmasters; crawler@alexa.com)', // Alexa crawler
    'Mozilla/5.0 (compatible; Alexabot/1.0; +http://www.alexa.com/help/certifyscan;)', // Alexa Certification Scanner 1.0
    // AOL
    'Mozilla/5.0 (compatible; MSIE 9.0; AOL 9.7; AOLBuild 4343.19; Windows NT 6.1; WOW64; Trident/5.0; FunWebProducts)',
    'Mozilla/4.0 (compatible; MSIE 8.0; AOL 9.7; AOLBuild 4343.27; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)',
    'Mozilla/4.0 (compatible; MSIE 8.0; AOL 9.7; AOLBuild 4343.21; Windows NT 5.1; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C; .NET4.0E)',
    'Mozilla/4.0 (compatible; MSIE 8.0; AOL 9.7; AOLBuild 4343.19; Windows NT 5.1; Trident/4.0; GTB7.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)',
    'Mozilla/4.0 (compatible; MSIE 8.0; AOL 9.7; AOLBuild 4343.19; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C; .NET4.0E)',
    'Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.7; AOLBuild 4343.19; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C; .NET4.0E)',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.2.5 (KHTML, like Gecko) Version/8.0.2 Safari/600.2.5 (Applebot/0.1)',
    // Applebot
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.2.5 (KHTML, like Gecko) Version/8.0.2 Safari/600.2.5 (Applebot/0.1; +http://www.apple.com/go/applebot)',
    'Mozilla/5.0 (compatible; Applebot/0.3; +http://www.apple.com/go/applebot)',
    'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25 (compatible; Applebot/0.3; +http://www.apple.com/go/applebot)',
    'Mozilla/5.0 (iPhone; CPU iPhone OS 8_1 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12B410 Safari/600.1.4 (Applebot/0.1; +http://www.apple.com/go/applebot)',
    // archive.org_bot
    'Mozilla/5.0 (compatible; heritrix/3.1.1-SNAPSHOT-20120116.200628 +http://www.archive.org/details/archive.org_bot)',
    'Mozilla/5.0 (compatible; archive.org_bot/heritrix-1.15.4 +http://www.archive.org)',
    'Mozilla/5.0 (compatible; heritrix/3.3.0-SNAPSHOT-20140702-2247 +http://archive.org/details/archive.org_bot)',
    'Mozilla/5.0 (compatible; archive.org_bot +http://www.archive.org/details/archive.org_bot)',
    'Mozilla/5.0 (compatible; archive.org_bot +http://archive.org/details/archive.org_bot)',
    'Mozilla/5.0 (compatible; special_archiver/3.1.1 +http://www.archive.org/details/archive.org_bot)',
    // Baidu
    'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)', // Baidu Spider 2.0 | Search Engine | Desktop
    'Baiduspider+(+http://www.baidu.com/search/spider_jp.html)', // Baidu Spider
    'Baiduspider+(+http://www.baidu.com/search/spider.htm)', // Baidu Spider
    // Bing
    'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)', // Bingbot 2.0 | Search Engine | Desktop
    'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534+ (KHTML, like Gecko) BingPreview/1.0b', // Bing Preview Snapshot Generator 1.0b | Search Engine | Mobile
    'Mozilla/5.0 (compatible; bingbot/2.0 +http://www.bing.com/bingbot.htm)', // Bingbot
    'Mozilla/5.0 (compatible; adidxbot/2.0; +http://www.bing.com/bingbot.htm)', // Bingbot
    'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53 (compatible; adidxbot/2.0;  http://www.bing.com/bingbot.htm)', // BingBot 2.0
    'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53 (compatible; adidxbot/2.0; +http://www.bing.com/bingbot.htm)', // BingBot 2.0
    'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53 (compatible; bingbot/2.0;  http://www.bing.com/bingbot.htm)', // BingBot 2.0
    'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)', // BingBot 2.0
    'Mozilla/5.0 (Windows Phone 8.1; ARM; Trident/7.0; Touch; rv:11.0; IEMobile/11.0; NOKIA; Lumia 530) like Gecko (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)', // Bingbot
    'Mozilla/5.0 (Windows Phone 8.1; ARM; Trident/7.0; Touch; rv:11.0; IEMobile/11.0; NOKIA; Lumia 530) like Gecko (compatible; adidxbot/2.0; +http://www.bing.com/bingbot.htm)', // Bingbot
    'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534+ (KHTML, like Gecko) BingPreview/1.0b', // Bingbot
    'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53 BingPreview/1.0b', // Bing Preview Snapshot Generator 1.0b
    'Mozilla/5.0 (Windows Phone 8.1; ARM; Trident/7.0; Touch; rv:11.0; IEMobile/11.0; NOKIA; Lumia 530) like Gecko BingPreview/1.0b', // Bingbot
    'msnbot/0.01 (+http://search.msn.com/msnbot.htm)', // MSN Bot 0.01
    'msnbot/0.3 (+http://search.msn.com/msnbot.htm)', // MSN Bot 0.3
    'msnbot/1.0 (+http://search.msn.com/msnbot.htm)', // MSN Bot 1.0
    'msnbot/1.1 (+http://search.msn.com/msnbot.htm)', // MSN Bot 1.1
    'msnbot/2.0b (+http://search.msn.com/msnbot.htm)', // MSN Bot 2.0b
    'msnbot-media/1.0 (+http://search.msn.com/msnbot.htm)', // MSN Media Bot 1.0
    'msnbot-media/1.1 (+http://search.msn.com/msnbot.htm)', // MSN Media Bot 1.1
    // Bytespider
    "Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.3754.1902 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.4454.1745 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.7597.1164 Mobile Safari/537.36; Bytespider;bytespider@bytedance.com",
    "Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2988.1545 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.4141.1682 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.3478.1649 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.5267.1259 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.7990.1979 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.2268.1523 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2576.1836 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.9681.1227 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.6023.1635 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.4944.1981 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.3613.1739 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.4022.1033 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.3248.1547 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.5527.1507 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (Linux; Android 8.0; Pixel 2 Build/OPD3.170816.012) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.5216.1326 Mobile Safari/537.36; Bytespider",
    "Mozilla/5.0 (Linux; Android 8.0; Pixel 2 Build/OPD3.170816.012) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.9038.1080 Mobile Safari/537.36; Bytespider",
    // coccoc
    'Mozilla/5.0 (compatible; coccoc/1.0; +http://help.coccoc.com/)',
    'Mozilla/5.0 (compatible; coccoc/1.0; +http://help.coccoc.com/searchengine)',
    'Mozilla/5.0 (compatible; coccocbot-image/1.0; +http://help.coccoc.com/searchengine)',
    'Mozilla/5.0 (compatible; coccocbot-web/1.0; +http://help.coccoc.com/searchengine)',
    'Mozilla/5.0 (compatible; image.coccoc/1.0; +http://help.coccoc.com/)',
    'Mozilla/5.0 (compatible; imagecoccoc/1.0; +http://help.coccoc.com/)',
    'Mozilla/5.0 (compatible; imagecoccoc/1.0; +http://help.coccoc.com/searchengine)',
    'coccoc',
    'coccoc/1.0 ()',
    'coccoc/1.0 (http://help.coccoc.com/)',
    'coccoc/1.0 (http://help.coccoc.vn/)',
    // Daum Bot
    'Mozilla/5.0 (compatible; Daum/4.1; +http://cs.daum.net/faq/15/4118.html?faqId=28966)', // Daum Bot
    'Mozilla/5.0 (Unknown; Linux x86_64) AppleWebKit/538.1 (KHTML, like Gecko) Safari/538.1 Daum/4.1', // Daum Bot
    // DuckDuckGo
    'DuckDuckBot/1.0; (+http://duckduckgo.com/duckduckbot.html)', // DuckDuckGo
    'Mozilla/5.0 (compatible; DuckDuckGo-Favicons-Bot/1.0; +http://duckduckgo.com)', // DuckDuckGo Favicons Bot 1.0
    // Exabot
    'Mozilla/5.0 (compatible; Konqueror/3.5; Linux) KHTML/3.5.5 (like Gecko) (Exabot-Thumbnails)', // Exabot
    'Mozilla/5.0 (compatible; Alexabot/1.0; +http://www.alexa.com/help/certifyscan; certifyscan@alexa.com)', // Exabot
    'Mozilla/5.0 (compatible; Exabot PyExalead/3.0; +http://www.exabot.com/go/robot)', // Exabot
    'Mozilla/5.0 (compatible; Exabot-Images/3.0; +http://www.exabot.com/go/robot)', // Exabot
    'Mozilla/5.0 (compatible; Exabot/3.0 (BiggerBetter); +http://www.exabot.com/go/robot)', // Exabot
    'Mozilla/5.0 (compatible; Exabot/3.0; +http://www.exabot.com/go/robot)', // Exabot
    'Mozilla/5.0 (compatible; Exabot/3.0;  http://www.exabot.com/go/robot)', // Exabot
    // Facebook
    'facebookexternalhit/1.0 (+http://www.facebook.com/externalhit_uatext.php)', // Facebook Bot 1.0 | Social Media Agent | Desktop bot
    'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)', // Facebook Bot 1.1 | Social Media Agent | Desktop bot
    'facebookexternalhit/1.1', // Facebook Bot 1.1 | Social Media Agent | Desktop bot
    // FAST-WebCrawler
    'FAST MetaWeb Crawler (helpdesk at fastsearch dot com)', // FAST MetaData Crawler
    'FAST-WebCrawler/3.6/FirstPage (atw-crawler at fast dot no;http://fast.no/support/crawler.asp)', // FAST-WebCrawler 3.6
    'FAST-WebCrawler/3.7 (atw-crawler at fast dot no; http://fast.no/support/crawler.asp)', // FAST-WebCrawler 3.7
    'FAST-WebCrawler/3.7/FirstPage (atw-crawler at fast dot no;http://fast.no/support/crawler.asp)', // FAST-WebCrawler 3.7
    'FAST-WebCrawler/3.8', // FAST-WebCrawler 3.8
    'FAST Enterprise Crawler 6 / Scirus scirus-crawler@fast.no; http://www.scirus.com/srsapp/contactus/', // FAST Enterprise Crawler 6
    'FAST Enterprise Crawler 6 used by Schibsted (webcrawl@schibstedsok.no)', // FAST Enterprise Crawler 6
    // findlink
    'findlinks/1.0 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/1.1.3-beta8 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/1.1.3-beta9 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/1.1.5-beta7 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/1.1.6-beta1 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/1.1.6-beta1 (+http://wortschatz.uni-leipzig.de/findlinks/; YaCy 0.1; yacy.net)',
    'findlinks/1.1.6-beta2 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/1.1.6-beta3 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/1.1.6-beta4 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/1.1.6-beta5 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/1.1.6-beta6 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/2.0 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/2.0.1 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/2.0.2 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/2.0.4 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/2.0.5 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/2.0.9 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/2.1 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/2.1.3 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/2.1.5 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/2.2 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/2.5 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    'findlinks/2.6 (+http://wortschatz.uni-leipzig.de/findlinks/)',
    // Google
    'Googlebot/2.1 (+http://www.googlebot.com/bot.html)', // Googlebot 2.1
    'Googlebot/2.1 (+http://www.google.com/bot.html)', // Googlebot 2.1
    'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Googlebot/2.1; +http://www.google.com/bot.html) Safari/537.36', // Googlebot 2.1 | Search Engine | Mobile
    'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Googlebot/2.1; +http://www.google.com/bot.html) Chrome/W.X.Y.Z Safari/537.36', // Googlebot 2.1 | Search Engine | Mobile
    'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', // Googlebot 2.1 | Search Engine | Desktop
    'Mozilla/5.0 (Windows NT 5.1; rv:11.0) Gecko Firefox/11.0 (via ggpht.com GoogleImageProxy)', //Google Image Proxy 11
    'Googlebot-Image/1.0', // Googlebot Image Crawler 1.0 | Images | n/a
    'Googlebot-News',
    'Googlebot-Video/1.0',
    'Mediapartners-Google', // Mediapartners-Google
    'Mozilla/5.0 (compatible; MSIE or Firefox mutant; not on Windows server;) Daumoa/4.0 (Following Mediapartners-Google)', // Mediapartners-Google
    '(compatible; Mediapartners-Google/2.1; +http://www.google.com/bot.html)', // Google Mobile Adsense
    'Mozilla/5.0 (iPhone; U; CPU iPhone OS 10_0 like Mac OS X; en-us) AppleWebKit/602.1.38 (KHTML, like Gecko) Version/10.0 Mobile/14A5297c Safari/602.1 (compatible; Mediapartners-Google/2.1; +http://www.google.com/bot.html)', // Mediapartners-Google
    'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Mediapartners-Google/2.1; +http://www.google.com/bot.html)', // Mediapartners-Google
    'Mozilla/5.0 (Linux; Android 5.0; SM-G920A) AppleWebKit (KHTML, like Gecko) Chrome Mobile Safari (compatible; AdsBot-Google-Mobile; +http://www.google.com/mobile/adsbot.html)', // Googlebot | Search Engine | Mobile
    'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.96 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', // Googlebot 2.1 | Search Engine | Mobile
    'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/W.X.Y.Z Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
    'Nokia6820/2.0 (4.83) Profile/MIDP-1.0 Configuration/CLDC-1.0 (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)', // Google Mobile
    'SAMSUNG-SGH-E250/1.0 Profile/MIDP-2.0 Configuration/CLDC-1.1 UP.Browser/6.2.3.3.c.1.101 (GUI) MMP/2.0 (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)', // Google Mobile
    'DoCoMo/2.0 N905i(c100;TB;W24H16) (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)', // Google Mobile
    'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)', // Googlebot Mobile 2.1
    'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', // Googlebot Mobile 2.1
    'Mozilla/5.0 (iPhone; CPU iPhone OS 8_3 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12F70 Safari/600.1.4 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', // Googlebot Mobile 2.1
    'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1 (compatible; AdsBot-Google-Mobile; +http://www.google.com/mobile/adsbot.html)', // AdsBot Google | Advertising Bot | Mobile
    'AdsBot-Google (+http://www.google.com/adsbot.html)', // Google AdsBot (PPC landing page quality)
    'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.75 Safari/537.36 Google Favicon', // Google Favicon Crawler
    'Feedfetcher-Google; (+http://www.google.com/feedfetcher.html; 3 subscribers; feed-id=17583705103843181935)', // Google Feedfetcher
    'AdsBot-Google-Mobile-Apps', // Google app crawler (fetch resources for mobile)
    // grub.org
    'Mozilla/4.0 (compatible; grub-client-0.3.0; Crawl your own stuff with http://grub.org)', // grub.org
    'Mozilla/4.0 (compatible; grub-client-1.0.4; Crawl your own stuff with http://grub.org)', // grub.org
    'Mozilla/4.0 (compatible; grub-client-1.0.5; Crawl your own stuff with http://grub.org)', // grub.org
    'Mozilla/4.0 (compatible; grub-client-1.0.6; Crawl your own stuff with http://grub.org)', // grub.org
    'Mozilla/4.0 (compatible; grub-client-1.0.7; Crawl your own stuff with http://grub.org)', // grub.org
    'Mozilla/4.0 (compatible; grub-client-1.1.1; Crawl your own stuff with http://grub.org)', // grub.org
    'Mozilla/4.0 (compatible; grub-client-1.2.1; Crawl your own stuff with http://grub.org)', // grub.org
    'Mozilla/4.0 (compatible; grub-client-1.3.1; Crawl your own stuff with http://grub.org)', // grub.org
    'Mozilla/4.0 (compatible; grub-client-1.3.7; Crawl your own stuff with http://grub.org)', // grub.org
    'Mozilla/4.0 (compatible; grub-client-1.4.3; Crawl your own stuff with http://grub.org)', // grub.org
    'Mozilla/4.0 (compatible; grub-client-1.5.3; Crawl your own stuff with http://grub.org)', // grub.org
    // heritrix
    'Mozilla/5.0 (compatible; heritrix/1.12.1 +http://www.webarchiv.cz)',
    'Mozilla/5.0 (compatible; heritrix/1.12.1b +http://netarkivet.dk/website/info.html)',
    'Mozilla/5.0 (compatible; heritrix/1.14.2 +http://rjpower.org)',
    'Mozilla/5.0 (compatible; heritrix/1.14.2 +http://www.webarchiv.cz)',
    'Mozilla/5.0 (compatible; heritrix/1.14.3 +http://archive.org)',
    'Mozilla/5.0 (compatible; heritrix/1.14.3 +http://www.accelobot.com)',
    'Mozilla/5.0 (compatible; heritrix/1.14.3 +http://www.webarchiv.cz)',
    'Mozilla/5.0 (compatible; heritrix/1.14.3.r6601 +http://www.buddybuzz.net/yptrino)',
    'Mozilla/5.0 (compatible; heritrix/1.14.4 +http://parsijoo.ir)',
    'Mozilla/5.0 (compatible; heritrix/1.14.4 +http://www.exif-search.com)',
    'Mozilla/5.0 (compatible; heritrix/2.0.2 +http://aihit.com)',
    'Mozilla/5.0 (compatible; heritrix/2.0.2 +http://seekda.com)',
    'Mozilla/5.0 (compatible; heritrix/3.0.0-SNAPSHOT-20091120.021634 +http://crawler.archive.org)',
    'Mozilla/5.0 (compatible; heritrix/3.1.0-RC1 +http://boston.lti.cs.cmu.edu/crawler_12/)',
    'Mozilla/5.0 (compatible; heritrix/3.1.1 +http://places.tomtom.com/crawlerinfo)',
    'Mozilla/5.0 (compatible; heritrix/3.1.1 +http://www.mixdata.com)',
    'Mozilla/5.0 (compatible; heritrix/3.1.1; UniLeipzigASV +http://corpora.informatik.uni-leipzig.de/crawler_faq.html)',
    'Mozilla/5.0 (compatible; heritrix/3.2.0 +http://www.crim.ca)',
    'Mozilla/5.0 (compatible; heritrix/3.2.0 +http://www.exif-search.com)',
    'Mozilla/5.0 (compatible; heritrix/3.2.0 +http://www.mixdata.com)',
    'Mozilla/5.0 (compatible; heritrix/3.3.0-SNAPSHOT-20160309-0050; UniLeipzigASV +http://corpora.informatik.uni-leipzig.de/crawler_faq.html)',
    'Mozilla/5.0 (compatible; sukibot_heritrix/3.1.1 +http://suki.ling.helsinki.fi/eng/webmasters.html)',
    // ichiro
    'DoCoMo/2.0 P900i(c100;TB;W24H11) (compatible; ichiro/mobile goo; +http://help.goo.ne.jp/help/article/1142/)',
    'DoCoMo/2.0 P900i(c100;TB;W24H11) (compatible; ichiro/mobile goo; +http://search.goo.ne.jp/option/use/sub4/sub4-1/)',
    'DoCoMo/2.0 P900i(c100;TB;W24H11) (compatible; ichiro/mobile goo;+http://search.goo.ne.jp/option/use/sub4/sub4-1/)',
    'DoCoMo/2.0 P900i(c100;TB;W24H11)(compatible; ichiro/mobile goo;+http://help.goo.ne.jp/door/crawler.html)',
    'DoCoMo/2.0 P901i(c100;TB;W24H11) (compatible; ichiro/mobile goo; +http://help.goo.ne.jp/door/crawler.html)',
    'KDDI-CA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0 (compatible; ichiro/mobile goo; +http://help.goo.ne.jp/help/article/1142/)',
    'KDDI-CA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0 (compatible; ichiro/mobile goo; +http://search.goo.ne.jp/option/use/sub4/sub4-1/)',
    'KDDI-CA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0 (compatible; ichiro/mobile goo;+http://search.goo.ne.jp/option/use/sub4/sub4-1/)',
    'ichiro/2.0 (http://help.goo.ne.jp/door/crawler.html)',
    'ichiro/2.0 (ichiro@nttr.co.jp)',
    'ichiro/3.0 (http://help.goo.ne.jp/door/crawler.html)',
    'ichiro/3.0 (http://help.goo.ne.jp/help/article/1142)',
    'ichiro/3.0 (http://search.goo.ne.jp/option/use/sub4/sub4-1/)',
    'ichiro/4.0 (http://help.goo.ne.jp/door/crawler.html)',
    'ichiro/5.0 (http://help.goo.ne.jp/door/crawler.html)',
    // Jobboerse
    'Mozilla/5.0 (X11; U; Linux Core i7-4980HQ; de; rv:32.0; compatible; Jobboerse.com; http://www.xn--jobbrse-d1a.com) Gecko/20100401 Firefox/24.0', // Jobboerse Crawler 24
    'Mozilla/5.0 (X11; U; Linux Core i7-4980HQ; de; rv:32.0; compatible; JobboerseBot; http://www.jobboerse.com/bot.htm) Gecko/20100101 Firefox/38.0', // Jobboerse Crawler 38
    'Mozilla/5.0 (X11; U; Linux Core i7-4980HQ; de; rv:32.0; compatible; JobboerseBot; https://www.jobboerse.com/bot.htm) Gecko/20100101 Firefox/38.0', // Jobboerse Crawler 38
    // LinkedIn
    'LinkedInBot/1.0 (compatible; Mozilla/5.0; Jakarta Commons-HttpClient/3.1 +http://www.linkedin.com)', // LinkedIn
    'LinkedInBot/1.0 (compatible; Mozilla/5.0; Jakarta Commons-HttpClient/4.3 +http://www.linkedin.com)', // LinkedIn
    'LinkedInBot/1.0 (compatible; Mozilla/5.0; Apache-HttpClient +http://www.linkedin.com)', // LinkedIn
    // MJ12bot
    'MJ12bot/v1.2.0 (http://majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.2.1; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.2.3; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.2.4; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.2.5; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.3.0; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.3.1; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.3.2; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.3.3; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.0; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.1; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.2; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.3; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.4 (domain ownership verifier); http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.4; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.6; http://mj12bot.com/)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.7; http://mj12bot.com/)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.7; http://www.majestic12.co.uk/bot.php?+)',
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)',
    // MozacFetch
    'MozacFetch/57.0.7', // MozacFetch
    'MozacFetch/57.0.8', // MozacFetch
    // PetalBot
    'Mozilla/5.0 (compatible;PetalBot;+https://aspiegel.com/petalbot)',
    'Mozilla/5.0 (compatible;PetalBot; +https://webmaster.petalsearch.com/site/petalbot)',
    'Mozilla/5.0 (Linux; Android 7.0;) AppleWebKit/537.36 (KHTML, like Gecko) Mobile Safari/537.36 (compatible; PetalBot;+https://aspiegel.com/petalbot)',
    'Mozilla/5.0 (Linux; Android 7.0;) AppleWebKit/537.36 (KHTML, like Gecko) Mobile Safari/537.36 (compatible; PetalBot;+https://webmaster.petalsearch.com/site/petalbot)',
    // Pinterest
    'Pinterest/0.2 (+http://www.pinterest.com/)', // Pinterest Bot 0.2
    'Mozilla/5.0 (compatible; Pinterestbot/1.0; +http://www.pinterest.com/bot.html)', // Pinterest Bot
    // rogerbot
    'Mozilla/5.0 (compatible; rogerBot/1.0; UrlCrawler; http://www.seomoz.org/dp/rogerbot)',
    'rogerbot/1.0 (http://moz.com/help/pro/what-is-rogerbot-, rogerbot-crawler+partager@moz.com)',
    'rogerbot/1.0 (http://moz.com/help/pro/what-is-rogerbot-, rogerbot-crawler+shiny@moz.com)',
    'rogerbot/1.0 (http://moz.com/help/pro/what-is-rogerbot-, rogerbot-wherecat@moz.com',
    'rogerbot/1.0 (http://moz.com/help/pro/what-is-rogerbot-, rogerbot-wherecat@moz.com)',
    'rogerbot/1.0 (http://www.moz.com/dp/rogerbot, rogerbot-crawler@moz.com)',
    'rogerbot/1.0 (http://www.seomoz.org/dp/rogerbot, rogerbot-crawler+shiny@seomoz.org)',
    'rogerbot/1.0 (http://www.seomoz.org/dp/rogerbot, rogerbot-crawler@seomoz.org)',
    'rogerbot/1.0 (http://www.seomoz.org/dp/rogerbot, rogerbot-wherecat@moz.com)',
    'rogerbot/1.1 (http://moz.com/help/guides/search-overview/crawl-diagnostics#more-help, rogerbot-crawler+pr2-crawler-05@moz.com)',
    'rogerbot/1.1 (http://moz.com/help/guides/search-overview/crawl-diagnostics#more-help, rogerbot-crawler+pr4-crawler-11@moz.com)',
    'rogerbot/1.1 (http://moz.com/help/guides/search-overview/crawl-diagnostics#more-help, rogerbot-crawler+pr4-crawler-15@moz.com)',
    'rogerbot/1.2 (http://moz.com/help/pro/what-is-rogerbot-, rogerbot-crawler+phaser-testing-crawler-01@moz.com)',
    // SEMRush
    'Mozilla/5.0 (compatible; SemrushBot-BA; +http://www.semrush.com/bot.html)',
    'Mozilla/5.0 (compatible; SemrushBot-SA/0.97; +http://www.semrush.com/bot.html)', // SEMRush Crawler 0.97
    'Mozilla/5.0 (compatible; SemrushBot-SI/0.97; +http://www.semrush.com/bot.html)', // SEMRush Crawler 0.97
    'Mozilla/5.0 (compatible; SemrushBot/0.98~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 0.98
    'Mozilla/5.0 (compatible; SemrushBot/1~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 1.0
    'Mozilla/5.0 (compatible; SemrushBot/1.1~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 1.1
    'Mozilla/5.0 (compatible; SemrushBot/1.2~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 1.2
    'Mozilla/5.0 (compatible; SemrushBot/2~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 2.0
    'Mozilla/5.0 (compatible; SemrushBot/3~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 3.0
    'Mozilla/5.0 (compatible; SemrushBot/6~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 6.0
    // Sogou Spider
    'Sogou Pic Spider/3.0( http://www.sogou.com/docs/help/webmasters.htm#07)',
    'Sogou head spider/3.0( http://www.sogou.com/docs/help/webmasters.htm#07)',
    'Sogou web spider/4.0(+http://www.sogou.com/docs/help/webmasters.htm#07)',
    'Sogou Orion spider/3.0( http://www.sogou.com/docs/help/webmasters.htm#07)',
    'Sogou-Test-Spider/4.0 (compatible; MSIE 5.5; Windows 98)',
    // spbot
    'Mozilla/5.0 (compatible; spbot/1.0; +http://www.seoprofiler.com/bot/ )',
    'Mozilla/5.0 (compatible; spbot/1.1; +http://www.seoprofiler.com/bot/ )',
    'Mozilla/5.0 (compatible; spbot/1.2; +http://www.seoprofiler.com/bot/ )',
    'Mozilla/5.0 (compatible; spbot/2.0.1; +http://www.seoprofiler.com/bot/ )',
    'Mozilla/5.0 (compatible; spbot/2.0.2; +http://www.seoprofiler.com/bot/ )',
    'Mozilla/5.0 (compatible; spbot/2.0.3; +http://www.seoprofiler.com/bot/ )',
    'Mozilla/5.0 (compatible; spbot/2.0.4; +http://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/2.0; +http://www.seoprofiler.com/bot/ )',
    'Mozilla/5.0 (compatible; spbot/2.1; +http://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/3.0; +http://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/3.1; +http://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/4.0.1; +http://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/4.0.2; +http://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/4.0.3; +http://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/4.0.4; +http://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/4.0.5; +http://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/4.0.6; +http://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/4.0.7; +http://OpenLinkProfiler.org/bot )',
    'Mozilla/5.0 (compatible; spbot/4.0.7; +https://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/4.0.8; +http://OpenLinkProfiler.org/bot )',
    'Mozilla/5.0 (compatible; spbot/4.0.9; +http://OpenLinkProfiler.org/bot )',
    'Mozilla/5.0 (compatible; spbot/4.0; +http://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/4.0a; +http://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/4.0b; +http://www.seoprofiler.com/bot )',
    'Mozilla/5.0 (compatible; spbot/4.1.0; +http://OpenLinkProfiler.org/bot )',
    'Mozilla/5.0 (compatible; spbot/4.2.0; +http://OpenLinkProfiler.org/bot )',
    'Mozilla/5.0 (compatible; spbot/4.3.0; +http://OpenLinkProfiler.org/bot )',
    'Mozilla/5.0 (compatible; spbot/4.4.0; +http://OpenLinkProfiler.org/bot )',
    'Mozilla/5.0 (compatible; spbot/4.4.1; +http://OpenLinkProfiler.org/bot )',
    'Mozilla/5.0 (compatible; spbot/4.4.2; +http://OpenLinkProfiler.org/bot )',
    'Mozilla/5.0 (compatible; spbot/5.0.1; +http://OpenLinkProfiler.org/bot )',
    'Mozilla/5.0 (compatible; spbot/5.0.2; +http://OpenLinkProfiler.org/bot )',
    'Mozilla/5.0 (compatible; spbot/5.0.3; +http://OpenLinkProfiler.org/bot )',
    'Mozilla/5.0 (compatible; spbot/5.0; +http://OpenLinkProfiler.org/bot )',
    // Teoma
    'Mozilla/2.0 (compatible; Ask Jeeves/Teoma; +http://sp.ask.com/docs/about/tech_crawling.html)', // Ask Jeeves Crawler
    'Mozilla/2.0 (compatible; Ask Jeeves/Teoma; +http://about.ask.com/en/docs/about/webmasters.shtml)', // Ask Jeeves Crawler
    'Mozilla/2.0 (compatible; Ask Jeeves/Teoma)', // Ask Jeeves Crawler
    'Mozilla/5.0 (compatible; Ask Jeeves/Teoma; +http://about.ask.com/en/docs/about/webmasters.shtml)', // Ask Jeeves Crawler
    // Turnitin
    'Turnitin (https://bit.ly/2UvnfoQ)', // Turnitin
    'TurnitinBot (https://turnitin.com/robot/crawlerinfo.html)', // TurnitinBot
    // Twitter
    'Twitterbot/1.0', // TwitterBot 1.0'
    // Voila
    'Mozilla/4.0 (compatible; MSIE 5.0; Windows 95) VoilaBot BETA 1.2 (http://www.voila.com/)', // VoilaBot Beta 1.2
    'Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.8.1) VoilaBot BETA 1.2 (support.voilabot@orange-ftgroup.com)', // VoilaBot Beta 1.2
    'Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.8.1) VoilaBot BETA 1.2 (http://www.voila.com/)', // VoilaBot Beta 1.2
    // WhatsApp
    'WhatsApp',
    'WhatsApp/0.3.4479 N',
    'WhatsApp/0.3.4679 N',
    'WhatsApp/0.3.4941 N',
    'WhatsApp/2.12.15/i',
    'WhatsApp/2.12.16/i',
    'WhatsApp/2.12.17/i',
    'WhatsApp/2.12.449 A',
    'WhatsApp/2.12.453 A',
    'WhatsApp/2.12.510 A',
    'WhatsApp/2.12.540 A',
    'WhatsApp/2.12.548 A',
    'WhatsApp/2.12.555 A',
    'WhatsApp/2.12.556 A',
    'WhatsApp/2.16.1/i',
    'WhatsApp/2.16.13 A',
    'WhatsApp/2.16.2/i',
    'WhatsApp/2.16.42 A',
    'WhatsApp/2.16.57 A',
    'WhatsApp/2.19.92 i',
    'WhatsApp/2.19.175 A',
    'WhatsApp/2.19.244 A',
    'WhatsApp/2.19.258 A',
    'WhatsApp/2.19.308 A',
    'WhatsApp/2.19.330 A',
    // yacybot
    'yacybot (/global; amd64 FreeBSD 10.3-RELEASE; java 1.8.0_77; GMT/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 FreeBSD 10.3-RELEASE-p7; java 1.7.0_95; GMT/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 FreeBSD 9.2-RELEASE-p10; java 1.7.0_65; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 2.6.32-042stab093.4; java 1.7.0_65; Etc/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 2.6.32-042stab094.8; java 1.7.0_79; America/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 2.6.32-042stab108.8; java 1.7.0_91; America/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 2.6.32-042stab111.11; java 1.7.0_79; Europe/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 2.6.32-042stab116.1; java 1.7.0_79; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 2.6.32-573.3.1.el6.x86_64; java 1.7.0_85; Europe/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 3.10.0-229.4.2.el7.x86_64; java 1.7.0_79; Europe/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 3.10.0-229.4.2.el7.x86_64; java 1.8.0_45; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.10.0-229.7.2.el7.x86_64; java 1.8.0_45; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.10.0-327.22.2.el7.x86_64; java 1.7.0_101; Etc/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.11.10-21-desktop; java 1.7.0_51; America/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.12.1; java 1.7.0_65; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.13.0-042stab093.4; java 1.7.0_79; Europe/de) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.13.0-042stab093.4; java 1.7.0_79; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.13.0-45-generic; java 1.7.0_75; Europe/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 3.13.0-61-generic; java 1.7.0_79; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.13.0-74-generic; java 1.7.0_91; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.13.0-83-generic; java 1.7.0_95; Europe/de) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.13.0-83-generic; java 1.7.0_95; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.13.0-85-generic; java 1.7.0_101; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.13.0-85-generic; java 1.7.0_95; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.13.0-88-generic; java 1.7.0_101; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.14-0.bpo.1-amd64; java 1.7.0_55; Europe/de) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.14.32-xxxx-grs-ipv6-64; java 1.7.0_75; Europe/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 3.14.32-xxxx-grs-ipv6-64; java 1.8.0_111; Europe/de) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.16.0-4-amd64; java 1.7.0_111; Europe/de) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.16.0-4-amd64; java 1.7.0_75; America/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 3.16.0-4-amd64; java 1.7.0_75; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.16.0-4-amd64; java 1.7.0_75; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.16.0-4-amd64; java 1.7.0_79; Europe/de) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.16.0-4-amd64; java 1.7.0_79; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.16.0-4-amd64; java 1.7.0_91; Europe/de) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.16.0-4-amd64; java 1.7.0_95; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.16.0-4-amd64; java 1.8.0_111; Europe/en) http://yacy.net/bot.html',
    'yacybot (/global; amd64 Linux 3.16-0.bpo.2-amd64; java 1.7.0_65; Europe/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 3.19.0-15-generic; java 1.8.0_45-internal; Europe/de) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 3.2.0-4-amd64; java 1.7.0_65; Europe/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 3.2.0-4-amd64; java 1.7.0_67; Europe/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 4.4.0-57-generic; java 9-internal; Europe/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Windows 8.1 6.3; java 1.7.0_55; Europe/de) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Windows 8 6.2; java 1.7.0_55; Europe/de) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 5.2.8-Jinsol; java 12.0.2; Europe/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 5.2.9-Jinsol; java 12.0.2; Europe/en) http://yacy.net/bot.html',
    'yacybot (-global; amd64 Linux 5.2.11-Jinsol; java 12.0.2; Europe/en) http://yacy.net/bot.html',
    // Yahoo
    'Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)', // Yahoo! Slurp Web Crawler Bot | Search Engine | Desktop
    'Mozilla/5.0 (compatible; Yahoo! Slurp/3.0; http://help.yahoo.com/help/us/ysearch/slurp)', // Yahoo! Slurp Web Crawler Bot 3
    'Mozilla/5.0 (compatible; Yahoo! Slurp China; http://misc.yahoo.com.cn/help.html)', // Yahoo!
    'Yahoo-MMCrawler/3.x (mms dash mmcrawler dash support at yahoo dash inc dot com)', // Yahoo! Slurp Web Crawler Bot
    // Yandex
    'Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)', // Yandex Search Bot 3
    'Mozilla/5.0 (iPhone; CPU iPhone OS 8_1 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12B411 Safari/600.1.4 (compatible; YandexBot/3.0; +http://yandex.com/bots)', // Yandex Mobile
    'Mozilla/5.0 (iPhone; CPU iPhone OS 8_1 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12B411 Safari/600.1.4 (compatible; YandexMobileBot/3.0; +http://yandex.com/bots)', // Yandex Mobile
    'Mozilla/5.0 (compatible; YandexAccessibilityBot/3.0; +http://yandex.com/bots)', // Yandex Accessibility Bot 3
    'Mozilla/5.0 (compatible; YandexDirectDyn/1.0; +http://yandex.com/bots', // Yandex
    'Mozilla/5.0 (compatible; YandexImages/3.0; +http://yandex.com/bots)', // Yandex Image Bot 3
    'Mozilla/5.0 (compatible; YandexVideo/3.0; +http://yandex.com/bots)', // Yandex Video Bot 3
    'Mozilla/5.0 (compatible; YandexMedia/3.0; +http://yandex.com/bots)', // Yandex Media Bot 3
    'Mozilla/5.0 (compatible; YandexBlogs/0.99; robot; +http://yandex.com/bots)', // Yandex Blog Bot 0.99
    'Mozilla/5.0 (compatible; YandexFavicons/1.0; +http://yandex.com/bots)', // Yandex Favicon Bot 1
    'Mozilla/5.0 (compatible; YandexWebmaster/2.0; +http://yandex.com/bots)', // Yandex Webmaster 2
    'Mozilla/5.0 (compatible; YandexPagechecker/1.0; +http://yandex.com/bots)', // Yandex Pagechecker 1
    'Mozilla/5.0 (compatible; YandexImageResizer/2.0; +http://yandex.com/bots)',
    'Mozilla/5.0 (compatible; YaDirectFetcher/1.0; Dyatel; +http://yandex.com/bots)',
    'Mozilla/5.0 (compatible; YandexCalendar/1.0; +http://yandex.com/bots)', // Yandex Calendar 1
    'Mozilla/5.0 (compatible; YandexSitelinks; Dyatel; +http://yandex.com/bots)', // Yandex Sitelinks
    'Mozilla/5.0 (compatible; YandexMetrika/3.0; +http://yandex.com/bots)', // Yandex Metrika 3
    'Mozilla/5.0 (compatible; YandexAntivirus/2.0; +http://yandex.com/bots)', // Yandex Antivirus 2
    'Mozilla/5.0 (compatible; YandexVertis/3.0; +http://yandex.com/bots)', // Yandex Vertis 3
    'Mozilla/5.0 (compatible; YandexBot/3.0; MirrorDetector; +http://yandex.com/bots)', // Yandex Bot 3
    // other
    'Mozilla/5.0 (compatible; Adsbot/3.1)', // Adsbot
    'Mozilla/5.0 (compatible; adscanner/)', // AdScanner Crawler
    'Mozilla/5.0 (compatible; Barkrowler/0.9; +https://babbar.tech/crawler)', // Barkrowler 0.9
    'Mozilla/5.0 (compatible; BecomeBot/3.0; +http://www.become.com/site_owners.html)', // Become.com Crawler 3
    'Mozilla/5.0 (compatible; ScoutJet; +http://www.scoutjet.com/)', // Blekko Scoutjet Crawler
    'Mozilla/5.0 (compatible; BLEXBot/1.0; +http://webmeup-crawler.com/)', // BLEXBot Crawler 1.0
    'BUbiNG (+http://law.di.unimi.it/BUbiNG.html)', // BUbiNG Crawler
    'BUbiNG (+http://law.di.unimi.it/BUbiNG.html#wc)', // BUbiNG Crawler
    'CCBot/2.0 (https://commoncrawl.org/faq/)', // CCBot
    'Mozilla/5.0 (compatible; Charlotte/1.1; http://www.searchme.com/support/)', // Charlotte 1.1
    'CheckMarkNetwork/1.0 (+http://www.checkmarknetwork.com/spider.html)', // CheckMark Network Crawler 1.0
    'DomainCrawler/3.0 (info@domaincrawler.com; http://www.domaincrawler.com/example.com)', // Domain Crawler 3
    'Mozilla/5.0 (compatible; DotBot/1.1; http://www.dotnetdotcom.org/, crawler@dotnetdotcom.org)', // DotNetDotComDotOrg Crawler 1.1
    'Mozilla/5.0 (compatible; ExaleadCloudView/5;)', // Exalead CloudView Crawler 5
    'FDM 3.x', // FDM
    'Mozilla/5.0 (compatible; memoryBot/1.21.24 +http://internetmemory.org/en/)', // Internet Memory Crawler 1.21
    'Jyxobot/1', // Jyxo.cz Crawler 1.0
    'Linguee Bot (http://www.linguee.com/bot; bot@linguee.com)', // Linguee Bot
    'Mozilla/5.0 (compatible; Linux x86_64; Mail.RU_Bot/Fast/2.0; +http://go.mail.ru/help/robots)', // Mail.ru Crawler
    'Mozilla/5.0 (compatible; MegaIndex.ru/2.0; +http://megaindex.com/crawler)', // MegaIndex Crawler 2.0
    'netEstate NE Crawler (+http://www.website-datenbank.de/)', // netEstate NE Crawler
    'Mozilla/5.0 (compatible; DotBot/1.1; http://www.opensiteexplorer.org/dotbot, help@moz.com)', // OpenSiteExplorer Crawler
    'Mozilla/5.0 (compatible; openstat.ru/Bot)', // Openstat.com Crawler
    'phpcrawl', // PHPCrawl
    'psbot/0.1 (+http://www.picsearch.com/bot.html)', // Picsearch Crawler 0.1
    'pimeyes.com crawler', // PimEyes
    'Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)', // Proximic | Search Engine | Desktop
    'Mozilla/5.0 (compatible; Qwantify/2.4w; +https://www.qwant.com/)/2.4w', // Qwantify Search Crawler 2.4w
    'Screaming Frog SEO Spider/6.2', // Screaming Frog Crawler 6.2
    'Seekbot/1.0 (http://www.seekbot.net/bot.html) RobotsTxtFetcher/1.2', // Seekbot
    'Mozilla/5.0 (compatible; Seekport Crawler; http://seekport.com/)', // Seekport Crawler
    'Mozilla/5.0 (compatible; SEOkicks-Robot; +http://www.seokicks.de/robot.html)', // SEOkicks Crawler
    'Mozilla/5.0 (compatible; seoscanners.net/1; +spider@seoscanners.net)', // SEO Scanners Crawler Bot 1.0
    'Mozilla/5.0 (compatible; SEOkicks; +https://www.seokicks.de/robot.html)', //SEOkicks Crawler
    'serpstatbot/1.0 (advanced backlink tracking bot; http://serpstatbot.com/; abuse@serpstatbot.com)', // serpstatbot
    'Mozilla/5.0 (compatible; SearchmetricsBot; https://www.searchmetrics.com/en/searchmetrics-bot/)', // searchmetrics
    'Mozilla/5.0 (compatible; SeznamBot/3.2; +http://napoveda.seznam.cz/en/seznambot-intro/)', // SeznamBot Crawler 3.2
    'Mozilla/5.0 (compatible; SISTRIX Crawler; http://crawler.sistrix.net/)', // Sistrix Crawler
    'Slackbot-LinkExpanding 1.0 (+https://api.slack.com/robots)', // Slackbot Link Checker 1.0
    'Mozilla/5.0 (compatible; TTD-Content; +https://www.thetradedesk.com/general/ttd-content)', // The Trade Desk Content Scraper
    'Mozilla/5.0 (compatible; vebidoobot/1.0; +https://blog.vebidoo.de/vebidoobot/)', // vebidoobot
    'ZoominfoBot (zoominfobot at zoominfo dot com)', // Zoom Info Bot
]);
