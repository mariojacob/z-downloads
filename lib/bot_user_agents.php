<?php

// Abbruch bei direktem Zugriff
if (!defined('ABSPATH')) {
    die;
}

define('ZDM__BOT_USER_AGENTS', [
    // 360Spider
    'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1; 360Spider(compatible; HaosouSpider; http://www.haosou.com/help/help_3_2.html)', // 360Spider
    'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0); 360Spider(compatible; HaosouSpider; http://www.haosou.com/help/help_3_2.html)', // 360Spider
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
    'Mozilla/5.0 (compatible; MSIE 9.0; AOL 9.7; AOLBuild 4343.19; Windows NT 6.1; WOW64; Trident/5.0; FunWebProducts)', // AOL
    'Mozilla/4.0 (compatible; MSIE 8.0; AOL 9.7; AOLBuild 4343.27; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', // AOL
    'Mozilla/4.0 (compatible; MSIE 8.0; AOL 9.7; AOLBuild 4343.21; Windows NT 5.1; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C; .NET4.0E)', // AOL
    'Mozilla/4.0 (compatible; MSIE 8.0; AOL 9.7; AOLBuild 4343.19; Windows NT 5.1; Trident/4.0; GTB7.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', // AOL
    'Mozilla/4.0 (compatible; MSIE 8.0; AOL 9.7; AOLBuild 4343.19; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C; .NET4.0E)', // AOL
    'Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.7; AOLBuild 4343.19; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C; .NET4.0E)', // AOL
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
    // Daum Bot
    'Mozilla/5.0 (compatible; Daum/4.1; +http://cs.daum.net/faq/15/4118.html?faqId=28966)', // Daum Bot
    'Mozilla/5.0 (Unknown; Linux x86_64) AppleWebKit/538.1 (KHTML, like Gecko) Safari/538.1 Daum/4.1', , // Daum Bot
    // DuckDuckGo
    'DuckDuckBot/1.0; (+http://duckduckgo.com/duckduckbot.html)', // DuckDuckGo
    'Mozilla/5.0 (compatible; DuckDuckGo-Favicons-Bot/1.0; +http://duckduckgo.com)', // DuckDuckGo Favicons Bot 1.0
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
    // Jobboerse
    'Mozilla/5.0 (X11; U; Linux Core i7-4980HQ; de; rv:32.0; compatible; Jobboerse.com; http://www.xn--jobbrse-d1a.com) Gecko/20100401 Firefox/24.0', // Jobboerse Crawler 24
    'Mozilla/5.0 (X11; U; Linux Core i7-4980HQ; de; rv:32.0; compatible; JobboerseBot; http://www.jobboerse.com/bot.htm) Gecko/20100101 Firefox/38.0', // Jobboerse Crawler 38
    'Mozilla/5.0 (X11; U; Linux Core i7-4980HQ; de; rv:32.0; compatible; JobboerseBot; https://www.jobboerse.com/bot.htm) Gecko/20100101 Firefox/38.0', // Jobboerse Crawler 38
    // LinkedIn
    'LinkedInBot/1.0 (compatible; Mozilla/5.0; Jakarta Commons-HttpClient/3.1 +http://www.linkedin.com)', // LinkedIn
    'LinkedInBot/1.0 (compatible; Mozilla/5.0; Jakarta Commons-HttpClient/4.3 +http://www.linkedin.com)', // LinkedIn
    'LinkedInBot/1.0 (compatible; Mozilla/5.0; Apache-HttpClient +http://www.linkedin.com)', // LinkedIn
    // Majestic
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.3; http://www.majestic12.co.uk/bot.php?+)', // Majestic-12 Distributed Search Bot 1.4
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)', // Majestic-12 Distributed Search Bot 1.4
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.7; http://mj12bot.com/)', // Majestic-12 Distributed Search Bot 1.4
    'Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)', // Majestic-12 Distributed Search Bot 1.4
    // MozacFetch
    'MozacFetch/57.0.7', // MozacFetch
    'MozacFetch/57.0.8', // MozacFetch
    // PetalBot
    'Mozilla/5.0 (compatible;PetalBot;+https://aspiegel.com/petalbot)',
    'Mozilla/5.0 (Linux; Android 7.0;) AppleWebKit/537.36 (KHTML, like Gecko) Mobile Safari/537.36 (compatible; PetalBot;+https://aspiegel.com/petalbot)',
    // Pinterest
    'Pinterest/0.2 (+http://www.pinterest.com/)', // Pinterest Bot 0.2
    'Mozilla/5.0 (compatible; Pinterestbot/1.0; +http://www.pinterest.com/bot.html)', // Pinterest Bot
    // SEMRush
    'Mozilla/5.0 (compatible; SemrushBot/0.98~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 0.98
    'Mozilla/5.0 (compatible; SemrushBot/1~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 1.0
    'Mozilla/5.0 (compatible; SemrushBot/1.1~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 1.1
    'Mozilla/5.0 (compatible; SemrushBot/1.2~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 1.2
    'Mozilla/5.0 (compatible; SemrushBot/2~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 2.0
    'Mozilla/5.0 (compatible; SemrushBot/3~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 3.0
    'Mozilla/5.0 (compatible; SemrushBot/6~bl; +http://www.semrush.com/bot.html)', // SEMRush Crawler 6.0
    // Sogou Spider
    'Sogou Pic Spider/3.0( http://www.sogou.com/docs/help/webmasters.htm#07)', // Sogou
    'Sogou head spider/3.0( http://www.sogou.com/docs/help/webmasters.htm#07)', // Sogou
    'Sogou web spider/4.0(+http://www.sogou.com/docs/help/webmasters.htm#07)', // Sogou
    'Sogou Orion spider/3.0( http://www.sogou.com/docs/help/webmasters.htm#07)', // Sogou
    'Sogou-Test-Spider/4.0 (compatible; MSIE 5.5; Windows 98)', // Sogou
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
    'Mozilla/5.0 (compatible; YandexFavicons/1.0; +http://yandex.com/bots)', // Yandex Favicon Bot 2
    'Mozilla/5.0 (compatible; YandexWebmaster/2.0; +http://yandex.com/bots)', // Yandex
    'Mozilla/5.0 (compatible; YandexPagechecker/1.0; +http://yandex.com/bots)', // Yandex
    'Mozilla/5.0 (compatible; YandexImageResizer/2.0; +http://yandex.com/bots)', // Yandex
    'Mozilla/5.0 (compatible; YaDirectFetcher/1.0; Dyatel; +http://yandex.com/bots)', // Yandex
    'Mozilla/5.0 (compatible; YandexCalendar/1.0; +http://yandex.com/bots)', // Yandex
    'Mozilla/5.0 (compatible; YandexSitelinks; Dyatel; +http://yandex.com/bots)', // Yandex
    'Mozilla/5.0 (compatible; YandexMetrika/3.0; +http://yandex.com/bots)', // Yandex
    'Mozilla/5.0 (compatible; YandexAntivirus/2.0; +http://yandex.com/bots)', // Yandex
    'Mozilla/5.0 (compatible; YandexVertis/3.0; +http://yandex.com/bots)', // Yandex
    'Mozilla/5.0 (compatible; YandexBot/3.0; MirrorDetector; +http://yandex.com/bots)', // Yandex
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
    'Mozilla/5.0 (compatible; Konqueror/3.5; Linux) KHTML/3.5.5 (like Gecko) (Exabot-Thumbnails)', // Exabot
    'Mozilla/5.0 (compatible; Exabot/3.0; +http://www.exabot.com/go/robot)', // ExaLead Crawler 3
    'Mozilla/5.0 (compatible; ExaleadCloudView/5;)', // Exalead CloudView Crawler 5
    'FDM 3.x', // FDM
    'Mozilla/5.0 (compatible; special_archiver/3.1.1 +http://www.archive.org/details/archive.org_bot)', // Internet Archiver Bot
    'Mozilla/5.0 (compatible; archive.org_bot +http://www.archive.org/details/archive.org_bot)', // Internet Archiver Bot
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
    'Slackbot-LinkExpanding 1.0 (+https://api.slack.com/robots)',// Slackbot Link Checker 1.0
    'Sogou web spider/4.0(+http://www.sogou.com/docs/help/webmasters.htm#07)', // Sogou "Search Dog" 4
    'Mozilla/5.0 (compatible; TTD-Content; +https://www.thetradedesk.com/general/ttd-content)', // The Trade Desk Content Scraper
    'Mozilla/5.0 (compatible; vebidoobot/1.0; +https://blog.vebidoo.de/vebidoobot/)', // vebidoobot
    'ZoominfoBot (zoominfobot at zoominfo dot com)', // Zoom Info Bot
    // temp
    'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.1.2 Safari/605.1.15',
]);