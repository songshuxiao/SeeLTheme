<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

use Typecho\Widget\Helper\Layout;

/**
 * Seel Theme Functions
 * 塞尔主题 - 双主题切换博客主题
 *
 * @package SeeLTheme
 * @author Jessadmin
 * @version 1.20
 * @link https://github.com/FeiFan86/SeeLTheme
 * @description 一款现代化的 Typecho 博客主题，支持简洁化与玻璃态双主题自由切换。主题内置丰富的功能模块，包括暗黑模式、自定义页面模板、主题设置导入导出、响应式设计等。简洁化主题干净利落，玻璃态主题炫酷美观，完美适配各种设备，为您的博客打造独特的视觉体验。
 * @license MIT License
 */



/**
 * 主题信息
 */
function themeInfo() {
    $info = array(
        'name' => 'SeeLTheme',
        'version' => '1.20',
        'description' => '一款现代化的 Typecho 博客主题，支持简洁化与玻璃态双主题自由切换。主题内置丰富的功能模块，包括暗黑模式、自定义页面模板、主题设置导入导出、响应式设计等。简洁化主题干净利落，玻璃态主题炫酷美观，完美适配各种设备，为您的博客打造独特的视觉体验。',
        'author' => 'Jessadmin',
        'authorUrl' => 'https://github.com/FeiFan86/SeeLTheme',
        'themeUrl' => 'https://github.com/FeiFan86/SeeLTheme',
        'license' => 'MIT'
    );
    return $info;
}

/**
 * 注册页面模板
 */
function themePageConfig($layout, $widget) {
    $template = new Typecho_Widget_Helper_Form_Element_Select('template',
        array(
            '' => '默认模板',
            'about' => '关于页面',
            'archive' => '归档页面',
            'tags' => '标签页面'
        ),
        $widget->template,
        _t('自定义模板'),
        _t('如果您为此页面选择了一个自定义模板，系统将按照您选择的模板文件展现它。')
    );
    $layout->addItem($template);
}

if (!class_exists('Typecho_Widget_Helper_Form_Element_Custom')) {
    class Seel_Custom_Input extends Layout {
        private $_html;
        private $_attributes = array();
        
        public function __construct($html) {
            $this->_html = $html;
        }
        
        public function setAttribute(string $attributeName, $attributeValue): Layout {
            parent::setAttribute($attributeName, $attributeName);
            $this->_attributes[$attributeName] = $attributeValue;
            return $this;
        }
        
        public function getAttribute(string $attributeName): ?string {
            return isset($this->_attributes[$attributeName]) ? (string)$this->_attributes[$attributeName] : null;
        }
        
        public function __toString() {
            return $this->_html;
        }
    }
    
    class Typecho_Widget_Helper_Form_Element_Custom extends Typecho_Widget_Helper_Form_Element
    {
        private $_input;
        
        public function __construct($html)
        {
            $this->_input = new Seel_Custom_Input($html);
            parent::__construct('custom', NULL, NULL, '', '');
        }
        
        public function input(?string $name = null, ?array $options = null): ?Layout
        {
            return $this->_input;
        }
        
        public function inputValue($value): self
        {
            return $this;
        }
        
        public function render()
        {
            echo $this->_input;
        }
    }
}

// 主题初始化（兼容 Typecho 1.3.0 + PHP 8.4）
function themeInit($archive) {
    // PHP 8.4 兼容：屏蔽 Typecho 1.3.0 核心的隐式可空参数弃用警告
    // （核心部分文件如 Widget/Contents/Attachment/Edit.php 仍存在 string $x = null 写法）
    if (PHP_VERSION_ID >= 80400) {
        $currentLevel = error_reporting();
        $deprecatedMask = E_DEPRECATED | E_USER_DEPRECATED;
        if (($currentLevel & $deprecatedMask) === $deprecatedMask) {
            error_reporting($currentLevel & ~$deprecatedMask);
        }
    }
}

// 主题配置
function themeConfig($form) {
    // 显示主题信息
    $infoHtml = '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
    $infoHtml .= '<h2 style="margin: 0 0 10px 0; font-size: 24px;">🎨 SeeLTheme</h2>';
    $infoHtml .= '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">';
    $infoHtml .= '<div><strong style="opacity: 0.8;">版本：</strong><span style="font-weight: 600;">1.21</span></div>';
    $infoHtml .= '<div><strong style="opacity: 0.8;">作者：</strong><span style="font-weight: 600;">Jessadmin</span></div>';
    $infoHtml .= '<div><strong style="opacity: 0.8;">许可证：</strong><span>MIT License</span></div>';
    $infoHtml .= '</div>';
    $infoHtml .= '<p style="margin: 15px 0 0 0; font-size: 14px; opacity: 0.9;">一款现代化的 Typecho 博客主题，支持简洁化与玻璃态双主题自由切换。主题内置丰富的功能模块，包括暗黑模式、自定义页面模板、主题设置导入导出、响应式设计等。</p>';
    $infoHtml .= '</div>';

    echo $infoHtml;

    // 侧边栏导航菜单
    $navHtml = '<div class="seel-admin-nav">';
    $navHtml .= '<style>
        .seel-admin-nav {
            position: fixed !important;
            right: 20px !important;
            top: 100px !important;
            background: #fff !important;
            border: 1px solid #ddd !important;
            border-radius: 8px !important;
            padding: 15px !important;
            width: 150px !important;
            max-height: 70vh !important;
            overflow-y: auto !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
            z-index: 999999 !important;
        }
        .seel-admin-nav h3 {
            margin: 0 0 10px 0 !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            color: #333 !important;
            border-bottom: 1px solid #eee !important;
            padding-bottom: 8px !important;
        }
        .seel-admin-nav ul {
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .seel-admin-nav li {
            margin-bottom: 3px !important;
        }
        .seel-admin-nav a {
            display: block !important;
            padding: 6px 10px !important;
            color: #555 !important;
            text-decoration: none !important;
            font-size: 13px !important;
        }
        .seel-admin-nav a:hover {
            background: #f5f5f5 !important;
            color: #333 !important;
        }
        .anchor-point {
            display: block !important;
            height: 10px !important;
            margin-top: -10px !important;
            visibility: hidden !important;
        }
        @media (max-width: 1400px) {
            .seel-admin-nav {
                position: static !important;
                width: 100% !important;
                max-height: none !important;
                margin-bottom: 20px !important;
                right: auto !important;
                top: auto !important;
            }
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".seel-admin-nav a").forEach(function(anchor) {
                anchor.addEventListener("click", function(e) {
                    e.preventDefault();
                    var targetId = this.getAttribute("href").substring(1);
                    var targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        var scrollOptions = new Object();
                        scrollOptions.behavior = "smooth";
                        scrollOptions.block = "start";
                        targetElement.scrollIntoView(scrollOptions);
                    }
                });
            });
        });
    </script>';
    $navHtml .= '<h3>快速导航</h3>';
    $navHtml .= '<ul>';
    $navHtml .= '<li><a href="#site-info">站点信息</a></li>';
    $navHtml .= '<li><a href="#theme-settings">主题设置</a></li>';
    $navHtml .= '<li><a href="#announcement">公告栏</a></li>';
    $navHtml .= '<li><a href="#reading-progress">阅读进度</a></li>';
    $navHtml .= '<li><a href="#toc">文章目录</a></li>';
    $navHtml .= '<li><a href="#related-posts">相关文章</a></li>';
    $navHtml .= '<li><a href="#social-share">社交分享</a></li>';
    $navHtml .= '<li><a href="#copyright">版权声明</a></li>';
    $navHtml .= '<li><a href="#donate">打赏功能</a></li>';
    $navHtml .= '<li><a href="#back-to-top">返回顶部</a></li>';
    $navHtml .= '<li><a href="#sidebar-config">侧边栏配置</a></li>';
    $navHtml .= '<li><a href="#ads">广告位</a></li>';
    $navHtml .= '<li><a href="#slider-settings">轮播图设置</a></li>';
    $navHtml .= '<li><a href="#nav-settings">导航栏设置</a></li>';
    $navHtml .= '<li><a href="#social-media">社交媒体</a></li>';
    $navHtml .= '<li><a href="#analytics">统计代码</a></li>';
    $navHtml .= '<li><a href="#custom-code">自定义代码</a></li>';
    $navHtml .= '<li><a href="#backup-import">备份与导入</a></li>';
    $navHtml .= '</ul>';
    $navHtml .= '</div>';
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom($navHtml));

    // ========== 站点信息 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="site-info" class="anchor-point"></div>'));
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, NULL, _t('站点 Logo'), _t('Logo 图片的 URL 地址')));
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('faviconUrl', NULL, NULL, _t('站点图标 (Favicon)'), _t('Favicon 图标 URL，建议尺寸 32x32 像素，支持 .ico、.png 格式')));
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Text('siteDescription', NULL, _t('记录生活，分享技术'), _t('站点描述'), _t('用于显示在标题中')));

    // ========== 主题设置 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="theme-settings" class="anchor-point"></div>'));
    $defaultTheme = new Typecho_Widget_Helper_Form_Element_Select('defaultTheme',
        array('v12' => '简洁化主题', 'v7' => '玻璃态主题'),
        'v12',
        _t('默认主题'),
        _t('选择站点默认的主题样式')
    );
    $form->addInput($defaultTheme);

    $darkModeTime = new Typecho_Widget_Helper_Form_Element_Text('darkModeTime', NULL, '', _t('暗黑模式显示时间段'), _t('设置自动启用暗黑模式的时间段（格式：开始时间-结束时间，如 20:00-7:00 表示晚上8点到次日早上7点），留空则不自动启用暗黑模式'));
    $form->addInput($darkModeTime);

    // ========== 公告栏模块 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="announcement" class="anchor-point"></div>'));
    $enableAnnouncement = new Typecho_Widget_Helper_Form_Element_Radio('enableAnnouncement',
        array('1' => _t('启用'), '0' => _t('禁用')),
        '1',
        _t('公告栏'),
        _t('在页面顶部显示公告栏')
    );
    $form->addInput($enableAnnouncement);

    $announcementText = new Typecho_Widget_Helper_Form_Element_Textarea('announcementText',
        NULL,
        '欢迎来到我的博客！🎉',
        _t('公告内容'),
        _t('公告栏显示的文本内容，支持 HTML')
    );
    $form->addInput($announcementText);

    $announcementClose = new Typecho_Widget_Helper_Form_Element_Radio('announcementClose',
        array('1' => _t('可关闭'), '0' => _t('不可关闭')),
        '1',
        _t('允许关闭公告'),
        _t('用户是否可以关闭公告栏')
    );
    $form->addInput($announcementClose);

    // ========== 阅读进度条 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="reading-progress" class="anchor-point"></div>'));
    $enableProgress = new Typecho_Widget_Helper_Form_Element_Radio('enableProgress',
        array('1' => _t('启用'), '0' => _t('禁用')),
        '1',
        _t('阅读进度条'),
        _t('在页面顶部显示阅读进度')
    );
    $form->addInput($enableProgress);

    // ========== 文章目录 (TOC) ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="toc" class="anchor-point"></div>'));
    $enableToc = new Typecho_Widget_Helper_Form_Element_Radio('enableToc',
        array('1' => _t('启用'), '0' => _t('禁用')),
        '1',
        _t('文章目录'),
        _t('在文章页面显示目录导航')
    );
    $form->addInput($enableToc);





    // ========== 社交分享 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="social-share" class="anchor-point"></div>'));
    $enableShare = new Typecho_Widget_Helper_Form_Element_Radio('enableShare',
        array('1' => _t('启用'), '0' => _t('禁用')),
        '1',
        _t('社交分享'),
        _t('在文章页面显示分享按钮')
    );
    $form->addInput($enableShare);

    $sharePlatforms = new Typecho_Widget_Helper_Form_Element_Textarea('sharePlatforms',
        NULL,
        'weibo,qq,wechat,twitter,link',
        _t('分享平台'),
        _t('启用哪些分享平台：weibo(微博),qq(QQ),wechat(微信),twitter(Twitter),link(复制链接)，用逗号分隔')
    );
    $form->addInput($sharePlatforms);

    // ========== 版权声明 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="copyright" class="anchor-point"></div>'));
    $enableCopyright = new Typecho_Widget_Helper_Form_Element_Radio('enableCopyright',
        array('1' => _t('启用'), '0' => _t('禁用')),
        '1',
        _t('版权声明'),
        _t('在文章页面显示版权信息')
    );
    $form->addInput($enableCopyright);

    $copyrightText = new Typecho_Widget_Helper_Form_Element_Textarea('copyrightText',
        NULL,
        '本文为原创文章，未经作者许可禁止转载',
        _t('版权声明内容'),
        _t('自定义版权声明文本，支持 HTML')
    );
    $form->addInput($copyrightText);

    // ========== 打赏功能 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="donate" class="anchor-point"></div>'));
    $enableDonate = new Typecho_Widget_Helper_Form_Element_Radio('enableDonate',
        array('1' => _t('启用'), '0' => _t('禁用')),
        '0',
        _t('打赏功能'),
        _t('在文章底部显示打赏按钮和二维码')
    );
    $form->addInput($enableDonate);

    $donateWechat = new Typecho_Widget_Helper_Form_Element_Text('donateWechat', NULL, NULL, _t('微信打赏二维码'), _t('微信支付二维码图片 URL'));
    $form->addInput($donateWechat);

    $donateAlipay = new Typecho_Widget_Helper_Form_Element_Text('donateAlipay', NULL, NULL, _t('支付宝打赏二维码'), _t('支付宝二维码图片 URL'));
    $form->addInput($donateAlipay);

    // ========== 返回顶部按钮 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="back-to-top" class="anchor-point"></div>'));
    $enableBackToTop = new Typecho_Widget_Helper_Form_Element_Radio('enableBackToTop',
        array('1' => _t('启用'), '0' => _t('禁用')),
        '1',
        _t('返回顶部'),
        _t('在页面右下角显示返回顶部按钮')
    );
    $form->addInput($enableBackToTop);

    // ========== 热门文章数量配置 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="hot-posts" class="anchor-point"></div>'));
    $hotPostsCount = new Typecho_Widget_Helper_Form_Element_Select('hotPostsCount',
        array('3' => '3 篇', '5' => '5 篇', '8' => '8 篇', '10' => '10 篇'),
        '5',
        _t('热门文章数量'),
        _t('显示的热门文章数量（需要在侧边栏配置中启用热门文章组件）')
    );
    $form->addInput($hotPostsCount);

    // ========== 最新评论数量配置 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="latest-comments" class="anchor-point"></div>'));
    $latestCommentsCount = new Typecho_Widget_Helper_Form_Element_Select('latestCommentsCount',
        array('3' => '3 条', '5' => '5 条', '8' => '8 条'),
        '5',
        _t('最新评论数量'),
        _t('显示的最新评论数量（需要在侧边栏配置中启用最新评论组件）')
    );
    $form->addInput($latestCommentsCount);

    // ========== 标签云数量配置 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="tag-cloud" class="anchor-point"></div>'));
    $tagsCloudCount = new Typecho_Widget_Helper_Form_Element_Select('tagsCloudCount',
        array('10' => '10 个', '20' => '20 个', '30' => '30 个', '40' => '40 个', '50' => '50 个', '0' => '全部显示'),
        '30',
        _t('标签云数量'),
        _t('显示的标签数量（需要在侧边栏配置中启用标签云组件）')
    );
    $form->addInput($tagsCloudCount);

    // ========== 友情链接配置 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="friend-links" class="anchor-point"></div>'));
    $friendLinks = new Typecho_Widget_Helper_Form_Element_Textarea('friendLinks',
        NULL,
        'Example,https://example.com
Typecho,https://typecho.org',
        _t('友情链接列表'),
        _t('每行一个链接，格式：名称,URL。支持分组：分组名:名称,URL（需要在侧边栏配置中启用友情链接组件）')
    );
    $form->addInput($friendLinks);

    // ========== 侧边栏配置 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="sidebar-config" class="anchor-point"></div>'));
    $sidebarHomeWidgets = new Typecho_Widget_Helper_Form_Element_Checkbox(
        'sidebarHomeWidgets',
        array(
            'stats' => '网站统计',
            'search' => '搜索',
            'category' => '分类',
            'tags' => '标签云',
            'archive' => '文章归档',
            'hotPosts' => '热门文章',
            'latestComments' => '最新评论',
            'friendLinks' => '友情链接'
        ),
        array('stats', 'search', 'category', 'tags', 'archive', 'hotPosts', 'latestComments', 'friendLinks'),
        _t('首页侧边栏组件'),
        _t('选择首页侧边栏显示的组件（可多选）')
    );
    $form->addInput($sidebarHomeWidgets->multiMode());

    $sidebarOtherWidgets = new Typecho_Widget_Helper_Form_Element_Checkbox(
        'sidebarOtherWidgets',
        array(
            'stats' => '网站统计',
            'search' => '搜索',
            'category' => '分类',
            'tags' => '标签云',
            'archive' => '文章归档',
            'hotPosts' => '热门文章',
            'latestComments' => '最新评论',
            'friendLinks' => '友情链接'
        ),
        array('stats', 'search', 'category', 'tags', 'archive', 'hotPosts', 'latestComments', 'friendLinks'),
        _t('其它页面侧边栏组件'),
        _t('选择其它页面侧边栏显示的组件（可多选）')
    );
    $form->addInput($sidebarOtherWidgets->multiMode());


    // ========== 广告位 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="ads" class="anchor-point"></div>'));
    $enableSidebarAd = new Typecho_Widget_Helper_Form_Element_Radio('enableSidebarAd',
        array('1' => _t('启用'), '0' => _t('禁用')),
        '0',
        _t('侧边栏广告'),
        _t('在侧边栏显示广告')
    );
    $form->addInput($enableSidebarAd);

    $sidebarAdCode = new Typecho_Widget_Helper_Form_Element_Textarea('sidebarAdCode', NULL, NULL, _t('侧边栏广告代码'), _t('广告 HTML 代码'));
    $form->addInput($sidebarAdCode);

    $sidebarAdPosition = new Typecho_Widget_Helper_Form_Element_Select('sidebarAdPosition',
        array('top' => '侧边栏顶部', 'stats' => '网站统计上面', 'search' => '搜索上面', 'category' => '分类上面', 'tags' => '标签云上面', 'archive' => '文章归档上面', 'hotPosts' => '热门文章上面', 'latestComments' => '最新评论上面', 'friendLinks' => '友情链接上面', 'bottom' => '侧边栏底部'),
        'top',
        _t('侧边栏广告位置'),
        _t('选择广告位显示在哪个模块上面')
    );
    $form->addInput($sidebarAdPosition);

    $enableContentAd = new Typecho_Widget_Helper_Form_Element_Radio('enableContentAd',
        array('1' => _t('启用'), '0' => _t('禁用')),
        '0',
        _t('文章内广告'),
        _t('在文章内容中插入广告')
    );
    $form->addInput($enableContentAd);

    $contentAdCode = new Typecho_Widget_Helper_Form_Element_Textarea('contentAdCode', NULL, NULL, _t('文章内广告代码'), _t('广告 HTML 代码'));
    $form->addInput($contentAdCode);

    $contentAdPosition = new Typecho_Widget_Helper_Form_Element_Select('contentAdPosition',
        array('before' => '文章开头', 'middle' => '文章中部', 'after' => '文章结尾'),
        'middle',
        _t('文章广告位置'),
        _t('选择广告在文章中的插入位置')
    );
    $form->addInput($contentAdPosition);

    // ========== 轮播图设置 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="slider-settings" class="anchor-point"></div>'));
    $enableSlider = new Typecho_Widget_Helper_Form_Element_Radio('enableSlider',
        array('1' => _t('启用'), '0' => _t('禁用')),
        '1',
        _t('首页轮播图'),
        _t('在首页显示文章轮播图')
    );
    $form->addInput($enableSlider);

    $sliderPostIds = new Typecho_Widget_Helper_Form_Element_Textarea('sliderPostIds',
        NULL,
        '',
        _t('轮播文章ID'),
        _t('输入要展示在轮播图中的文章ID，每行一个。例如：\n1\n2\n3\n4\n5\n留空则自动显示热门文章')
    );
    $form->addInput($sliderPostIds);

    // ========== 导航栏设置 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="nav-settings" class="anchor-point"></div>'));
    $customNav = new Typecho_Widget_Helper_Form_Element_Textarea('customNav',
        NULL,
        '首页,/
关于,/about.html
归档,/archives.html',
        _t('自定义导航'),
        _t('每行一个导航项，格式：名称,URL。例如：首页,/\n关于,/about.html。留空则使用默认导航（首页、归档、分类、关于）')
    );
    $form->addInput($customNav);

    $navStyle = new Typecho_Widget_Helper_Form_Element_Select('navStyle',
        array(
            'default' => '经典平衡',
            'gradient' => '流动动感',
            'glassmorphism' => '现代简约'
        ),
        'default',
        _t('导航栏样式'),
        _t('选择导航栏的样式风格')
    );
    $form->addInput($navStyle);

    // ========== 社交媒体链接 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="social-media" class="anchor-point"></div>'));
    $socialGithub = new Typecho_Widget_Helper_Form_Element_Text('socialGithub', NULL, NULL, _t('GitHub'), _t('GitHub 个人主页链接'));
    $form->addInput($socialGithub);

    $socialWeibo = new Typecho_Widget_Helper_Form_Element_Text('socialWeibo', NULL, NULL, _t('微博'), _t('微博个人主页链接'));
    $form->addInput($socialWeibo);

    $socialWechat = new Typecho_Widget_Helper_Form_Element_Text('socialWechat', NULL, NULL, _t('微信'), _t('微信公众号/微信号'));
    $form->addInput($socialWechat);

    $socialWechatQr = new Typecho_Widget_Helper_Form_Element_Text('socialWechatQr', NULL, NULL, _t('微信二维码'), _t('微信二维码图片 URL（点击底部微信图标时显示）'));
    $form->addInput($socialWechatQr);

    $socialEmail = new Typecho_Widget_Helper_Form_Element_Text('socialEmail', NULL, NULL, _t('邮箱'), _t('联系邮箱地址'));
    $form->addInput($socialEmail);

    // ========== 网盘下载功能配置 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="netdisk" class="anchor-point"></div>'));
    $enableNetdisk = new Typecho_Widget_Helper_Form_Element_Radio('enableNetdisk',
        array('1' => _t('启用'), '0' => _t('禁用')),
        '0',
        _t('网盘下载功能'),
        _t('是否启用网盘下载功能，启用后可在文章中填写网盘信息')
    );
    $form->addInput($enableNetdisk);

    // ========== 统计代码 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="analytics" class="anchor-point"></div>'));
    $analyticsCode = new Typecho_Widget_Helper_Form_Element_Textarea('analyticsCode', NULL, NULL, _t('统计代码'), _t('Google Analytics 或其他统计代码'));
    $form->addInput($analyticsCode);

    // ========== 自定义代码 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="custom-code" class="anchor-point"></div>'));
    $customCss = new Typecho_Widget_Helper_Form_Element_Textarea('customCss', NULL, NULL, _t('自定义 CSS'), _t('自定义 CSS 代码，无需 &lt;style&gt; 标签'));
    $form->addInput($customCss);

    $customJs = new Typecho_Widget_Helper_Form_Element_Textarea('customJs', NULL, NULL, _t('自定义 JavaScript'), _t('自定义 JavaScript 代码，无需 &lt;script&gt; 标签'));
    $form->addInput($customJs);

    // ========== 备份与导入 ==========
    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom('<div id="backup-import" class="anchor-point"></div>'));

    // 构建export.php和import.php的URL
    $options = Typecho_Widget::widget('Widget_Options');
    $themeExportUrl = $options->themeUrl . '/export.php';
    $themeImportUrl = $options->themeUrl . '/import.php';

    $backupHtml = '';
    $backupHtml .= '<input type="hidden" id="themeImportUrl" value="' . htmlspecialchars($themeImportUrl) . '"/>';
    $backupHtml .= '<input type="hidden" id="themeUrlDebug" value="' . htmlspecialchars($options->themeUrl) . '"/>';
    $backupHtml .= '<input type="hidden" id="rootUrlDebug" value="' . htmlspecialchars($options->rootUrl) . '"/>';
    $backupHtml .= '<div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:24px;margin-bottom:24px;">';
    $backupHtml .= '<h3 style="margin-top:0;margin-bottom:16px;font-size:18px;font-weight:600;color:#111827;">';
    $backupHtml .= '<svg style="vertical-align:-2px;width:18px;height:18px;margin-right:8px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">';
    $backupHtml .= '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>';
    $backupHtml .= '<polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>';
    $backupHtml .= '<line x1="12" y1="22.08" x2="12" y2="12"></line>';
    $backupHtml .= '</svg>';
    $backupHtml .= '备份与导入';
    $backupHtml .= '</h3>';
    $backupHtml .= '<p style="color:#6b7280;font-size:14px;line-height:1.5;margin-bottom:20px;">';
    $backupHtml .= '备份主题设置到 JSON 文件，或从 JSON 文件导入主题设置。建议定期备份您的主题配置，以防设置丢失。';
    $backupHtml .= '</p>';
    $backupHtml .= '<div style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;">';
    $backupHtml .= '<a href="' . htmlspecialchars($themeExportUrl) . '" id="exportSettingsBtn" style="display:inline-flex;align-items:center;padding:10px 20px;background:#3b82f6;color:white;border:none;border-radius:6px;cursor:pointer;font-size:14px;font-weight:500;text-decoration:none;transition:all 0.2s;box-shadow:0 1px 2px rgba(0,0,0,0.05);">';
    $backupHtml .= '<svg style="margin-right:8px;width:16px;height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">';
    $backupHtml .= '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>';
    $backupHtml .= '<polyline points="7 10 12 15 17 10"></polyline>';
    $backupHtml .= '<line x1="12" y1="15" x2="12" y2="3"></line>';
    $backupHtml .= '</svg>';
    $backupHtml .= '导出设置';
    $backupHtml .= '</a>';
    $backupHtml .= '<button type="button" id="importSettingsBtn" style="display:inline-flex;align-items:center;padding:10px 20px;background:#10b981;color:white;border:none;border-radius:6px;cursor:pointer;font-size:14px;font-weight:500;transition:all 0.2s;box-shadow:0 1px 2px rgba(0,0,0,0.05);">';
    $backupHtml .= '<svg style="margin-right:8px;width:16px;height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">';
    $backupHtml .= '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>';
    $backupHtml .= '<polyline points="17 8 12 3 7 8"></polyline>';
    $backupHtml .= '<line x1="12" y1="3" x2="12" y2="15"></line>';
    $backupHtml .= '</svg>';
    $backupHtml .= '导入设置';
    $backupHtml .= '</button>';
    $backupHtml .= '<input type="file" id="importSettingsFile" accept=".json" style="display:none;"/>';
    $backupHtml .= '</div>';
    $backupHtml .= '<div style="margin-top:16px;padding:12px;background:#f9fafb;border-radius:6px;border-left:4px solid #3b82f6;">';
    $backupHtml .= '<p style="margin:0;font-size:13px;color:#4b5563;line-height:1.5;">';
    $backupHtml .= '<strong>提示：</strong> 导出的文件包含所有主题设置，包括站点信息、主题选项、广告代码等。导入时会覆盖当前设置，建议在操作前备份现有配置。';
    $backupHtml .= '</p>';
    $backupHtml .= '</div>';
    $backupHtml .= '<div id="importMessage" style="margin-top:16px;padding:12px;border-radius:6px;font-size:13px;display:none;"></div>';
    $backupHtml .= '</div>';

    // CSS
    $backupHtml .= '<style>';
    $backupHtml .= '#exportSettingsBtn:hover { background:#2563eb !important; transform:translateY(-1px); box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06) !important; }';
    $backupHtml .= '#importSettingsBtn:hover { background:#059669 !important; transform:translateY(-1px); box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06) !important; }';
    $backupHtml .= '#importSettingsBtn:disabled { opacity:0.6; cursor:not-allowed; }';
    $backupHtml .= '</style>';

    // JavaScript
    $backupHtml .= '<script>';
    $backupHtml .= 'document.addEventListener("DOMContentLoaded", function() {';
    $backupHtml .= 'var importBtn = document.getElementById("importSettingsBtn");';
    $backupHtml .= 'var importFile = document.getElementById("importSettingsFile");';
    $backupHtml .= 'var messageDiv = document.getElementById("importMessage");';
    $backupHtml .= 'function showMessage(text, type) {';
    $backupHtml .= 'if (!messageDiv) return;';
    $backupHtml .= 'messageDiv.textContent = text;';
    $backupHtml .= 'messageDiv.style.display = "block";';
    $backupHtml .= 'if (type === "success") {';
    $backupHtml .= 'messageDiv.style.background = "#d1fae5";';
    $backupHtml .= 'messageDiv.style.color = "#065f46";';
    $backupHtml .= 'messageDiv.style.border = "1px solid #a7f3d0";';
    $backupHtml .= '} else if (type === "error") {';
    $backupHtml .= 'messageDiv.style.background = "#fee2e2";';
    $backupHtml .= 'messageDiv.style.color = "#991b1b";';
    $backupHtml .= 'messageDiv.style.border = "1px solid #fecaca";';
    $backupHtml .= '} else {';
    $backupHtml .= 'messageDiv.style.background = "#dbeafe";';
    $backupHtml .= 'messageDiv.style.color = "#1e40af";';
    $backupHtml .= 'messageDiv.style.border = "1px solid #bfdbfe";';
    $backupHtml .= '}';
    $backupHtml .= 'setTimeout(function() {';
    $backupHtml .= 'messageDiv.style.display = "none";';
    $backupHtml .= '}, 5000);';
    $backupHtml .= '}';
    $backupHtml .= 'if (importBtn && importFile) {';
    $backupHtml .= 'importBtn.addEventListener("click", function() {';
    $backupHtml .= 'importFile.click();';
    $backupHtml .= '});';
    $backupHtml .= 'importFile.addEventListener("change", function(e) {';
    $backupHtml .= 'var file = e.target.files[0];';
    $backupHtml .= 'if (!file) return;';
    $backupHtml .= 'var fileExt = file.name.split(".").pop().toLowerCase();';
    $backupHtml .= 'if (fileExt !== "json") {';
    $backupHtml .= 'showMessage("请选择 JSON 格式的文件。", "error");';
    $backupHtml .= 'importFile.value = "";';
    $backupHtml .= 'return;';
    $backupHtml .= '}';
    $backupHtml .= 'if (file.size > 2 * 1024 * 1024) {';
    $backupHtml .= 'showMessage("文件大小不能超过 2MB。", "error");';
    $backupHtml .= 'importFile.value = "";';
    $backupHtml .= 'return;';
    $backupHtml .= '}';
    $backupHtml .= 'if (!confirm("确定要导入主题设置吗？此操作将覆盖当前的设置。")) {';
    $backupHtml .= 'importFile.value = "";';
    $backupHtml .= 'return;';
    $backupHtml .= '}';
    $backupHtml .= 'var formData = new FormData();';
    $backupHtml .= 'formData.append("settings_file", file);';
    $backupHtml .= 'formData.append("import_settings", "1");';
    $backupHtml .= 'importBtn.disabled = true;';
    $backupHtml .= 'importBtn.textContent = "导入中...";';
    $backupHtml .= 'showMessage("正在导入设置，请稍候...", "info");';
    $backupHtml .= 'var fetchOptions = new Object();';
    $backupHtml .= 'fetchOptions.method = "POST";';
    $backupHtml .= 'fetchOptions.body = formData;';
    $backupHtml .= 'var themeUrl = document.getElementById("themeImportUrl").value;';
    $backupHtml .= 'var themeUrlDebug = document.getElementById("themeUrlDebug").value;';
    $backupHtml .= 'var rootUrlDebug = document.getElementById("rootUrlDebug").value;';
    $backupHtml .= 'console.log("导入请求URL:", themeUrl);';
    $backupHtml .= 'console.log("主题URL:", themeUrlDebug);';
    $backupHtml .= 'console.log("根URL:", rootUrlDebug);';
    $backupHtml .= 'console.log("当前页面URL:", window.location.href);';
    $backupHtml .= 'fetch(themeUrl, fetchOptions)';
    $backupHtml .= '.then(function(response) {';
    $backupHtml .= 'console.log("响应状态:", response.status, response.statusText);';
    $backupHtml .= 'console.log("响应头:", response.headers);';
    $backupHtml .= 'if (!response.ok) {';
    $backupHtml .= 'return response.text().then(function(text) {';
    $backupHtml .= 'throw new Error("HTTP 错误：" + response.status + " " + text.substring(0, 200));';
    $backupHtml .= '});';
    $backupHtml .= '}';
    $backupHtml .= 'var contentType = response.headers.get("content-type");';
    $backupHtml .= 'console.log("Content-Type:", contentType);';
    $backupHtml .= 'if (contentType && contentType.indexOf("application/json") !== -1) {';
    $backupHtml .= 'return response.text().then(function(text) {';
    $backupHtml .= 'console.log("服务器返回的JSON文本:", text);';
    $backupHtml .= 'return JSON.parse(text);';
    $backupHtml .= '});';
    $backupHtml .= '} else {';
    $backupHtml .= 'return response.text().then(function(text) {';
    $backupHtml .= 'console.log("服务器返回的非JSON文本:", text);';
    $backupHtml .= 'if (text.indexOf("<!DOCTYPE") !== -1) {';
    $backupHtml .= 'throw new Error("服务器返回了 HTML 而不是 JSON，可能是因为路由问题。请联系管理员。");';
    $backupHtml .= '}';
    $backupHtml .= 'throw new Error("服务器返回错误：" + text.substring(0, 500));';
    $backupHtml .= '});';
    $backupHtml .= '}';
    $backupHtml .= '})';
    $backupHtml .= '.then(function(data) {';
    $backupHtml .= 'console.log("解析后的数据:", data);';
    $backupHtml .= 'if (data.success) {';
    $backupHtml .= 'showMessage(data.message, "success");';
    $backupHtml .= 'setTimeout(function() {';
    $backupHtml .= 'window.location.reload();';
    $backupHtml .= '}, 1500);';
    $backupHtml .= '} else {';
    $backupHtml .= 'showMessage(data.message || "导入失败", "error");';
    $backupHtml .= '}';
    $backupHtml .= '})';
    $backupHtml .= '.catch(function(error) {';
    $backupHtml .= 'console.error("导入错误:", error);';
    $backupHtml .= 'showMessage("导入失败：" + error.message, "error");';
    $backupHtml .= '})';
    $backupHtml .= '.finally(function() {';
    $backupHtml .= 'importBtn.disabled = false;';
    $backupHtml .= 'importBtn.textContent = "导入设置";';
    $backupHtml .= 'importFile.value = "";';
    $backupHtml .= '});';
    $backupHtml .= '});';
    $backupHtml .= '}';
    $backupHtml .= '});';
    $backupHtml .= '</script>';

    $form->addInput(new Typecho_Widget_Helper_Form_Element_Custom($backupHtml));
}

// 添加文章自定义字段
function themeFields($layout) {
    // 网盘下载信息(支持多个网盘)
    $netdiskInfo = new Typecho_Widget_Helper_Form_Element_Textarea('netdiskInfo', NULL, NULL,
        '网盘下载信息',
        '填写网盘下载信息，支持多个网盘。<br><br>网盘类型：<br>baidu - 百度网盘<br>aliyun - 阿里云盘<br>tencent - 腾讯微云<br>lanzou - 蓝奏云<br>kuake - 夸克网盘<br>uc - UC网盘<br>123pan - 123网盘<br>other - 其他<br><br>格式说明：<br>每个网盘一行，格式：网盘类型|下载链接|提取码(可选)|说明(可选)<br><br>有提取码和说明：<br>baidu|https://pan.baidu.com/s/xxx|abcd|备用链接<br><br>有提取码无说明：<br>aliyun|https://www.aliyundrive.com/s/xxx|xyz<br><br>有说明无提取码：<br>kuake|https://pan.quark.cn/s/yyy||高速下载<br><br>仅下载链接：<br>lanzou|https://xxx.lanzoui.com/xxx<br><br>注意：<br>- 竖线"|"是分隔符，不要随意添加<br>- 提取码和说明都是可选的，不需要可以留空<br>- 每个网盘单独一行，支持添加多个网盘'
    );
    $layout->addItem($netdiskInfo);
}

// 获取文章缩略图
function getThumbnail($widget, $width = 800, $height = 450) {
    // 优先使用自定义缩略图
    if ($widget->fields->thumbnail) {
        return $widget->fields->thumbnail;
    }

    // 从内容中提取第一张图片
    $content = $widget->content;
    $pattern = '/<img.*?src=[\'"](.*?)[\'"].*?>/i';
    preg_match($pattern, $content, $matches);

    if (isset($matches[1])) {
        return $matches[1];
    }

    // 使用 Unsplash 随机图片
    $randomImages = array(
        'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=' . $width,
        'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=' . $width,
        'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=' . $width,
        'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=' . $width,
        'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=' . $width,
        'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=' . $width,
    );
    return $randomImages[array_rand($randomImages)];
}

// 获取文章摘要
function getExcerpt($widget, $length = 120) {
    $content = strip_tags($widget->content);
    $content = preg_replace('/\s+/', ' ', $content);
    $content = trim($content);
    if (mb_strlen($content) > $length) {
        $content = mb_substr($content, 0, $length) . '...';
    }
    return $content;
}

// 计算阅读时间
function getReadingTime($content) {
    $wordCount = mb_strlen(strip_tags($content));
    $minutes = ceil($wordCount / 500);
    return $minutes . ' 分钟';
}

// 获取文章浏览量
function getViews($widget) {
    // 先尝试从 fields 中获取
    if (isset($widget->fields->views)) {
        return $widget->fields->views;
    }
    // 如果没有，从数据库的 views 字段获取
    $db = Typecho_Db::get();
    $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $widget->cid));
    if ($row && isset($row['views'])) {
        return $row['views'];
    }
    return '0';
}

// 获取相关文章

// 输出分页
function themePager($widget) {
    // 调试模式
    $debug = isset($_GET['debug']) && $_GET['debug'] === 'pagination';
    
    // 获取当前页码 - Typecho标准方法
    $page = $widget->getCurrentPage();
    
    // 获取每页文章数
    $pageSize = 10;
    if (isset($widget->options->pageSize)) {
        $pageSize = $widget->options->pageSize;
    } elseif (isset($widget->parameter->pageSize)) {
        $pageSize = $widget->parameter->pageSize;
    }
    
    // 获取文章总数
    $total = $widget->getTotal();
    
    // 计算总页数
    $totalPages = ceil($total / $pageSize);
    if ($totalPages < 1) {
        $totalPages = 1;
    }
    
    if ($debug) {
        echo '<div style="background:#f0f0f0;padding:10px;margin-bottom:10px;border:1px solid #ccc;">';
        echo '<strong>分页调试信息：</strong><br>';
        echo '当前页码: ' . $page . '<br>';
        echo '文章总数: ' . $total . '<br>';
        echo '每页显示: ' . $pageSize . '<br>';
        echo '总页数: ' . $totalPages . '<br>';
        echo 'pageLink方法: ' . (method_exists($widget, 'pageLink') ? '可用' : '不可用') . '<br>';
        echo 'permalink方法: ' . (method_exists($widget, 'permalink') ? '可用' : '不可用') . '<br>';
        
        // 输出示例URL
        if (method_exists($widget, 'pageLink')) {
            echo '示例链接：<br>';
            if ($page > 1) {
                echo '上一页: ' . htmlspecialchars($widget->pageLink($page - 1)) . '<br>';
            }
            echo '当前页: ' . htmlspecialchars($widget->pageLink($page)) . '<br>';
            if ($page < $totalPages) {
                echo '下一页: ' . htmlspecialchars($widget->pageLink($page + 1)) . '<br>';
            }
        }
        
        echo '</div>';
    }
    
    // 首页不显示分页，只使用无限滚动；分类页面显示分页和无限滚动
    if (!$widget->is('index')) {
        // 使用Typecho的标准pageNav方法进行分页
        if (method_exists($widget, 'pageNav')) {
            $widget->pageNav('&laquo;', '&raquo;', 3, '...', array(
                'wrapTag' => 'div',
                'wrapClass' => 'pagination',
                'itemTag' => '',
                'textTag' => 'span',
                'aClass' => '',
                'currentClass' => 'current',
                'prevClass' => '',
                'nextClass' => ''
            ));
        } else {
            // 降级方案：生成基本分页
            echo '<div class="pagination">';
            
            // 上一页
            if ($page > 1) {
                echo '<a href="' . htmlspecialchars($widget->pageLink($page - 1)) . '">&laquo; 上一页</a>';
            } else {
                echo '<span>&laquo; 上一页</span>';
            }
            
            // 数字页码（仅当有多页时显示）
            if ($totalPages > 1) {
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                
                if ($start > 1) {
                    // 第一页
                    echo '<a href="' . htmlspecialchars($widget->pageLink(1)) . '">1</a>';
                    if ($start > 2) {
                        echo '<span>...</span>';
                    }
                }
                
                for ($i = $start; $i <= $end; $i++) {
                    if ($i == $page) {
                        echo '<span class="current">' . $i . '</span>';
                    } else {
                        echo '<a href="' . htmlspecialchars($widget->pageLink($i)) . '">' . $i . '</a>';
                    }
                }
                
                if ($end < $totalPages) {
                    if ($end < $totalPages - 1) {
                        echo '<span>...</span>';
                    }
                    // 最后一页
                    echo '<a href="' . htmlspecialchars($widget->pageLink($totalPages)) . '">' . $totalPages . '</a>';
                }
            }
            
            // 下一页
            if ($page < $totalPages) {
                echo '<a href="' . htmlspecialchars($widget->pageLink($page + 1)) . '">下一页 &raquo;</a>';
            } else {
                echo '<span>下一页 &raquo;</span>';
            }
            
            echo '</div>';
        }
    }
    
    // 为自动加载功能添加数据属性（在首页和分类页面且有多页时）
    if (($widget->is('index') || $widget->is('category')) && $totalPages > 1) {
        // 根据页面类型设置不同的选择器
        if ($widget->is('index')) {
            $containerSelector = '.posts-list';
            $itemSelector = '.post-item';
        } else {
            $containerSelector = '.posts-grid';
            $itemSelector = '.post-card';
        }
        
        echo '<div id="auto-load-data" style="display:none;" 
            data-current-page="' . $page . '" 
            data-total-pages="' . $totalPages . '" 
            data-base-url="' . htmlspecialchars($widget->request->getRequestUrl()) . '"
            data-container-selector="' . htmlspecialchars($containerSelector) . '"
            data-item-selector="' . htmlspecialchars($itemSelector) . '"></div>';
    }
}

// 为首页和分类页面添加自动加载（无限滚动）功能
function themeAutoLoadScript() {
    // 只在首页或分类页面且非管理后台时添加
    $archive = Typecho_Widget::widget('Widget_Archive');
    if ((!$archive->is('index') && !$archive->is('category')) || Typecho_Widget::widget('Widget_Options')->adminUrl) {
        return;
    }
    
    $script = <<<'EOT'
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 检查是否支持自动加载
    const autoLoadData = document.getElementById('auto-load-data');
    if (!autoLoadData) {
        return;
    }
    
    let currentPage = parseInt(autoLoadData.dataset.currentPage);
    const totalPages = parseInt(autoLoadData.dataset.totalPages);
    const baseUrl = autoLoadData.dataset.baseUrl;
    const containerSelector = autoLoadData.dataset.containerSelector;
    const itemSelector = autoLoadData.dataset.itemSelector;
    
    console.log('自动加载: 初始化', { currentPage, totalPages, baseUrl, containerSelector, itemSelector });
    
    if (currentPage >= totalPages) {
        console.log('自动加载: 已经是最后一页，自动加载未启用');
        return;
    }
    
    let isLoading = false;
    let hasMore = true;
    
    // 加载更多文章
    function loadMorePosts() {
        if (isLoading) {
            console.log('自动加载: 正在加载中，跳过');
            return;
        }
        if (!hasMore) {
            console.log('自动加载: 没有更多内容');
            return;
        }
        
        const nextPage = currentPage + 1;
        isLoading = true;
        
        // 显示加载指示器
        const loader = document.createElement('div');
        loader.className = 'auto-load-loader';
        loader.innerHTML = '<div class="loading-spinner"></div><p>正在加载更多文章...</p>';
        loader.style.textAlign = 'center';
        loader.style.padding = '20px';
        loader.style.color = 'var(--text-secondary)';
        
        const pagination = document.querySelector('.pagination');
        const postsGrid = document.querySelector(containerSelector);
        
        if (pagination) {
            pagination.parentNode.insertBefore(loader, pagination);
        } else if (postsGrid) {
            postsGrid.appendChild(loader);
        } else {
            console.error('自动加载: 未找到' + containerSelector + '或.pagination元素');
            isLoading = false;
            return;
        }
        
        // 构建下一页URL - 使用Typecho的标准分页格式
        let nextPageUrl = baseUrl;
        if (nextPageUrl.indexOf('?') > -1) {
            // 如果已有查询参数，添加或替换page参数
            const urlObj = new URL(nextPageUrl, window.location.origin);
            urlObj.searchParams.set('page', nextPage);
            nextPageUrl = urlObj.toString();
        } else {
            // 如果没有查询参数，添加?page=
            nextPageUrl += '?page=' + nextPage;
        }
        
        console.log('自动加载: 请求下一页', nextPageUrl);
        
        // 获取下一页内容
        fetch(nextPageUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.text();
            })
            .then(html => {
                console.log('自动加载: 获取到HTML内容，长度', html.length);
                
                // 解析HTML，提取文章
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newPosts = doc.querySelectorAll(containerSelector + ' ' + itemSelector);
                const postsGrid = document.querySelector(containerSelector);
                
                if (!postsGrid) {
                    console.error('自动加载: 未找到' + containerSelector + '元素');
                    hasMore = false;
                    return;
                }
                
                if (newPosts.length === 0) {
                    // 如果没有新文章，可能已经是最后一页
                    console.log('自动加载: 未找到新文章，可能已是最后一页');
                    hasMore = false;
                } else {
                    console.log('自动加载: 找到', newPosts.length, '个新文章');
                    
                    // 添加新文章到当前页面
                    newPosts.forEach(post => {
                        postsGrid.appendChild(post.cloneNode(true));
                    });
                    
                    // 更新当前页码
                    currentPage = nextPage;
                    
                    // 更新自动加载数据
                    autoLoadData.dataset.currentPage = currentPage;
                    
                    // 更新分页（移除旧分页，添加新分页）
                    const newPagination = doc.querySelector('.pagination');
                    if (newPagination) {
                        const oldPagination = document.querySelector('.pagination');
                        if (oldPagination) {
                            oldPagination.parentNode.removeChild(oldPagination);
                        }
                        const mainContent = document.querySelector('.main-content');
                        if (mainContent) {
                            mainContent.appendChild(newPagination.cloneNode(true));
                        }
                    }
                }
                
                // 移除加载指示器
                if (loader.parentNode) {
                    loader.parentNode.removeChild(loader);
                }
                isLoading = false;
                
                // 如果已经是最后一页，显示提示
                if (currentPage >= totalPages) {
                    console.log('自动加载: 已达到最后一页');
                    const endMarker = document.createElement('div');
                    endMarker.className = 'auto-load-end';
                    endMarker.innerHTML = '<p style="text-align:center;color:var(--text-secondary);padding:20px;">已经加载所有文章</p>';
                    
                    const pagination = document.querySelector('.pagination');
                    if (pagination && pagination.parentNode) {
                        pagination.parentNode.insertBefore(endMarker, pagination);
                    } else if (postsGrid) {
                        postsGrid.appendChild(endMarker);
                    }
                    hasMore = false;
                }
            })
            .catch(error => {
                console.error('自动加载失败:', error);
                if (loader.parentNode) {
                    loader.innerHTML = '<p style="color:var(--error-color);padding:10px;text-align:center;">加载失败: ' + error.message + '</p>';
                    // 5秒后移除错误信息
                    setTimeout(() => {
                        if (loader.parentNode) {
                            loader.parentNode.removeChild(loader);
                        }
                    }, 5000);
                }
                isLoading = false;
                
                // 如果连续失败3次，禁用自动加载
                if (typeof window.seelAutoLoadFailCount === 'undefined') {
                    window.seelAutoLoadFailCount = 0;
                }
                window.seelAutoLoadFailCount++;
                
                if (window.seelAutoLoadFailCount >= 3) {
                    console.log('自动加载: 连续失败3次，禁用自动加载');
                    localStorage.setItem('seel_auto_load', 'false');
                    hasMore = false;
                    
                    // 显示禁用提示
                    const disableMsg = document.createElement('div');
                    disableMsg.className = 'auto-load-disable';
                    disableMsg.innerHTML = '<p style="color:var(--warning-color);padding:10px;text-align:center;border:1px solid var(--warning-color);border-radius:8px;">自动加载已禁用，请刷新页面</p>';
                    
                    const pagination = document.querySelector('.pagination');
                    if (pagination && pagination.parentNode) {
                        pagination.parentNode.insertBefore(disableMsg, pagination);
                    }
                }
            });
    }
    
    // 检查是否应该启用自动加载
    const enableAutoLoad = localStorage.getItem('seel_auto_load') !== 'false';
    
    if (enableAutoLoad) {
        console.log('自动加载: 已启用');
        
        // 添加自动加载按钮
        const autoLoadBtn = document.createElement('button');
        autoLoadBtn.className = 'auto-load-btn';
        autoLoadBtn.innerHTML = '加载更多文章';
        autoLoadBtn.style.display = 'block';
        autoLoadBtn.style.margin = '20px auto';
        autoLoadBtn.style.padding = '10px 20px';
        autoLoadBtn.style.backgroundColor = 'var(--accent-primary)';
        autoLoadBtn.style.color = 'white';
        autoLoadBtn.style.border = 'none';
        autoLoadBtn.style.borderRadius = '8px';
        autoLoadBtn.style.cursor = 'pointer';
        
        autoLoadBtn.addEventListener('click', function() {
            console.log('自动加载: 按钮点击');
            loadMorePosts();
        });
        
        const pagination = document.querySelector('.pagination');
        const postsGrid = document.querySelector(containerSelector);
        
        if (pagination && pagination.parentNode) {
            pagination.parentNode.insertBefore(autoLoadBtn, pagination);
        } else if (postsGrid) {
            postsGrid.appendChild(autoLoadBtn);
        } else {
            console.error('自动加载: 未找到插入按钮的位置');
        }
        
        // 自动滚动加载
        const scrollHandler = function() {
            const scrollPosition = window.scrollY + window.innerHeight;
            const pageHeight = document.documentElement.scrollHeight;
            const threshold = 100;
            
            // 滚动到底部100px内时加载
            if (pageHeight - scrollPosition < threshold) {
                console.log('自动加载: 滚动到底部，触发加载');
                loadMorePosts();
            }
        };
        
        window.addEventListener('scroll', scrollHandler);
        
        // 添加调试功能：通过URL参数启用详细日志
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('debug') === 'autoload') {
            console.log('自动加载: 调试模式已启用');
            window.seelAutoLoadDebug = true;
        }
    } else {
        console.log('自动加载: 已通过localStorage禁用');
    }
    
    // 添加CSS样式
    const style = document.createElement('style');
    style.textContent = `
        .loading-spinner {
            border: 3px solid var(--border-color);
            border-top: 3px solid var(--accent-primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .auto-load-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .auto-load-btn:active {
            transform: translateY(0);
        }

        .auto-load-disable {
            margin: 20px 0;
        }
    `;
    document.head.appendChild(style);
});
</script>
EOT;
    
    echo $script;
}

// 检查是否为移动设备
function isMobile() {
    return preg_match('/(android|iphone|ipad|ipod|mobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']));
}

// 获取热门文章（按浏览量排序）
function getHotPosts($limit = 5) {
    $db = Typecho_Db::get();
    $select = $db->select('cid', 'title', 'text', 'slug', 'created', 'modified', 'authorId', 'type', 'status', 'allowComment', 'allowPing', 'allowFeed', 'views')
        ->from('table.contents')
        ->where('type = ?', 'post')
        ->where('status = ?', 'publish')
        ->order('views', Typecho_Db::SORT_DESC)
        ->limit($limit);
    $posts = $db->fetchAll($select);
    return $posts;
}

// 获取轮播文章（根据用户指定的文章ID）
function getSliderPosts() {
    $options = Typecho_Widget::widget('Widget_Options');
    $sliderPostIds = $options->sliderPostIds;

    // 如果没有设置文章ID，则返回热门文章
    if (empty($sliderPostIds)) {
        return getHotPosts(5);
    }

    // 解析文章ID列表
    $postIds = array_filter(array_map('trim', explode("\n", $sliderPostIds)));
    if (empty($postIds)) {
        return getHotPosts(5);
    }

    // 根据文章ID查询文章
    $db = Typecho_Db::get();
    $posts = array();
    foreach ($postIds as $cid) {
        if (is_numeric($cid)) {
            $select = $db->select('cid', 'title', 'text', 'slug', 'created', 'modified', 'authorId', 'type', 'status', 'allowComment', 'allowPing', 'allowFeed')
                ->from('table.contents')
                ->where('type = ?', 'post')
                ->where('status = ?', 'publish')
                ->where('cid = ?', $cid)
                ->limit(1);
            $post = $db->fetchRow($select);
            if ($post) {
                $posts[] = $post;
            }
        }
    }

    // 如果没有找到文章，返回热门文章
    if (empty($posts)) {
        return getHotPosts(5);
    }

    return $posts;
}

// 获取最新评论
function getLatestComments($limit = 5) {
    $db = Typecho_Db::get();
    $select = $db->select('coid', 'author', 'mail', 'text', 'created', 'cid')
        ->from('table.comments')
        ->where('status = ?', 'approved')
        ->where('type = ?', 'comment')
        ->order('created', Typecho_Db::SORT_DESC)
        ->limit($limit);
    $comments = $db->fetchAll($select);
    return $comments;
}

// 根据 cid 获取文章数据（兼容 Typecho 1.3.0，替代已不存在的 load() 方法）
function getPostByCid($cid) {
    $db = Typecho_Db::get();
    $select = $db->select('cid', 'title', 'slug', 'created', 'modified', 'text',
        'authorId', 'type', 'status', 'allowComment', 'allowPing', 'allowFeed')
        ->from('table.contents')
        ->where('cid = ?', $cid)
        ->where('type = ?', 'post')
        ->limit(1);
    $post = $db->fetchRow($select);
    return $post ? $post : null;
}

// 获取评论总数
function getTotalComments() {
    $db = Typecho_Db::get();
    $count = $db->fetchObject($db->select(array('COUNT(coid)' => 'total'))
        ->from('table.comments')
        ->where('status = ?', 'approved')
        ->where('type = ?', 'comment'));
    return $count->total;
}

// 获取文章总数
function getTotalPosts() {
    $db = Typecho_Db::get();
    $count = $db->fetchObject($db->select(array('COUNT(cid)' => 'total'))
        ->from('table.contents')
        ->where('type = ?', 'post')
        ->where('status = ?', 'publish'));
    return $count->total;
}

// 获取分类总数
function getTotalCategories() {
    $db = Typecho_Db::get();
    $count = $db->fetchObject($db->select(array('COUNT(mid)' => 'total'))
        ->from('table.metas')
        ->where('type = ?', 'category'));
    return $count->total;
}

// 获取标签总数
function getTotalTags() {
    $db = Typecho_Db::get();
    $count = $db->fetchObject($db->select(array('COUNT(mid)' => 'total'))
        ->from('table.metas')
        ->where('type = ?', 'tag'));
    return $count->total;
}

// 获取当前分类下的文章数量
function getCategoryPostCount($mid) {
    $db = Typecho_Db::get();
    $query = $db->select('COUNT(table.contents.cid) AS total')
        ->from('table.contents')
        ->join('table.relationships', 'table.contents.cid = table.relationships.cid', Typecho_Db::INNER_JOIN)
        ->where('table.relationships.mid = ?', $mid)
        ->where('table.contents.type = ?', 'post')
        ->where('table.contents.status = ?', 'publish');
    $count = $db->fetchObject($query);
    return $count && isset($count->total) ? $count->total : 0;
}

// 获取日期归档
function getArchives() {
    $db = Typecho_Db::get();
    $select = $db->select('created', 'cid')
        ->from('table.contents')
        ->where('type = ?', 'post')
        ->where('status = ?', 'publish')
        ->order('created', Typecho_Db::SORT_DESC);
    $posts = $db->fetchAll($select);

    $archives = array();
    foreach ($posts as $post) {
        $year = date('Y', $post['created']);
        $month = date('m', $post['created']);
        $key = $year . '-' . $month;
        
        if (!isset($archives[$key])) {
            $archives[$key] = array(
                'year' => $year,
                'month' => $month,
                'count' => 0
            );
        }
        $archives[$key]['count']++;
    }
    
    return $archives;
}

// 获取 Gravatar 头像
function getGravatar($email, $size = 40) {
    $hash = md5(strtolower(trim($email)));
    return "https://www.gravatar.com/avatar/$hash?s=$size&d=mp";
}

// 解析友情链接（支持分组）
function parseFriendLinks($text) {
    $lines = explode("\n", trim($text));
    $groups = array();
    $ungrouped = array();

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        // 检查是否是分组
        if (preg_match('/^(.+?):(.+?),(.+)$/', $line, $matches)) {
            $groupName = trim($matches[1]);
            $name = trim($matches[2]);
            $url = trim($matches[3]);

            if (!isset($groups[$groupName])) {
                $groups[$groupName] = array();
            }
            $groups[$groupName][] = array('name' => $name, 'url' => $url);
        } else {
            // 普通链接
            $parts = explode(',', $line);
            if (count($parts) >= 2) {
                $ungrouped[] = array(
                    'name' => trim($parts[0]),
                    'url' => trim($parts[1])
                );
            }
        }
    }

    $result = array();
    if (!empty($groups)) {
        $result['groups'] = $groups;
    }
    if (!empty($ungrouped)) {
        $result['ungrouped'] = $ungrouped;
    }

    return $result;
}

// 解析自定义导航（仅支持一级导航）
function parseCustomNav($text) {
    $lines = explode("\n", trim($text));
    $navItems = array();

    foreach ($lines as $line) {
        // 去除前后空格和缩进
        $line = trim($line);
        if (empty($line)) continue;

        $parts = explode(',', $line);
        if (count($parts) >= 2) {
            $name = trim($parts[0]);
            $url = trim($parts[1]);

            // 支持图标格式：名称|图标,URL
            if (strpos($name, '|') !== false) {
                list($displayName, $icon) = explode('|', $name);
                $name = trim($displayName);
            } else {
                $icon = '';
            }

            $item = array(
                'name' => $name,
                'url' => $url,
                'icon' => $icon
            );

            $navItems[] = $item;
        }
    }

    return $navItems;
}

// 更新文章浏览量
function incrementViews($cid) {
    $db = Typecho_Db::get();

    try {
        // 检查 views 字段是否存在
        $row = $db->fetchRow($db->select()->from('table.contents')->where('cid = ?', $cid)->limit(1));

        if (!$row) {
            return;
        }

        // 如果 views 字段不存在，添加它
        if (!isset($row['views'])) {
            try {
                $db->query("ALTER TABLE " . $db->getPrefix() . "contents ADD COLUMN views INT(10) UNSIGNED NOT NULL DEFAULT '0'");
            } catch (Exception $e) {
                // 如果添加失败，可能是字段已存在或其他错误，忽略
            }
            $views = 0;
        } else {
            $views = intval($row['views']);
        }

        // 更新浏览量
        $db->query($db->update('table.contents')->rows(array('views' => $views + 1))->where('cid = ?', $cid));
    } catch (Exception $e) {
        // 忽略错误，避免影响页面正常显示
    }
}

// ========== 网站访问统计（真实数据）==========

// 记录访问
function recordVisit() {
    $db = Typecho_Db::get();

    if (isset($_SESSION['seel_visited']) && $_SESSION['seel_visited'] === date('Y-m-d')) {
        return;
    }

    $_SESSION['seel_visited'] = date('Y-m-d');

    $stats = getThemeStats();
    $totalViews = isset($stats['totalViews']) ? intval($stats['totalViews']) : 0;
    $todayViews = isset($stats['todayViews']) ? intval($stats['todayViews']) : 0;
    $lastDate = isset($stats['lastDate']) ? $stats['lastDate'] : '';
    $today = date('Y-m-d');

    if ($lastDate !== $today) {
        $todayViews = 0;
    }

    $totalViews++;
    $todayViews++;

    $data = array(
        'totalViews' => $totalViews,
        'todayViews' => $todayViews,
        'lastDate' => $today,
        'lastUpdate' => time()
    );

    updateThemeStats($data);
}

// 更新主题统计数据
function updateThemeStats($data) {
    $db = Typecho_Db::get();
    $value = json_encode($data);

    $exists = $db->fetchRow($db->select('value')->from('table.options')->where('name = ?', 'theme:seel_stats'));

    if ($exists) {
        $db->query($db->update('table.options')->rows(array('value' => $value))->where('name = ?', 'theme:seel_stats'));
    } else {
        $insertQuery = $db->insert('table.options')->rows(array('name' => 'theme:seel_stats', 'value' => $value, 'user' => 0));
        $db->query($insertQuery);
    }
}

// 获取统计数据
function getThemeStats() {
    $db = Typecho_Db::get();
    $result = $db->fetchRow($db->select('value')->from('table.options')->where('name = ?', 'theme:seel_stats'));

    if ($result) {
        $data = json_decode($result['value'], true);
        $today = date('Y-m-d');

        if (isset($data['lastDate']) && $data['lastDate'] !== $today) {
            $data['todayViews'] = 0;
            $data['lastDate'] = $today;
            updateThemeStats($data);
        }
        return $data;
    }

    return array(
        'totalViews' => 0,
        'todayViews' => 0,
        'lastDate' => date('Y-m-d'),
        'lastUpdate' => time()
    );
}

// 获取真实总访问量
function getRealTotalViews() {
    $stats = getThemeStats();
    return isset($stats['totalViews']) ? $stats['totalViews'] : 0;
}

// 获取真实今日访问
function getRealTodayViews() {
    $stats = getThemeStats();
    return isset($stats['todayViews']) ? $stats['todayViews'] : 0;
}

// 获取真实在线人数
function getRealOnlineUsers() {
    $db = Typecho_Db::get();
    $time = time() - 900;

    if (isset($_SESSION['seel_online_users'])) {
        $onlineUsers = $_SESSION['seel_online_users'];
        foreach ($onlineUsers as $ip => $lastTime) {
            if ($lastTime < $time) {
                unset($onlineUsers[$ip]);
            }
        }
        $_SESSION['seel_online_users'] = $onlineUsers;
        return count($onlineUsers);
    } else {
        $_SESSION['seel_online_users'] = array();
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $_SESSION['seel_online_users'][$ip] = time();

    return count($_SESSION['seel_online_users']);
}

// 页面加载时记录访问
if (!defined('SEEL_STATS_LOADED')) {
    define('SEEL_STATS_LOADED', true);
    if (session_status() === PHP_SESSION_NONE) {
        @session_start();
    }
    recordVisit();
}

// 获取评论头像
function getCommentAvatar($email, $author, $size = 50) {
    // 如果没有邮箱，使用名字首字母生成SVG头像
    if (empty($email)) {
        $firstChar = !empty($author) ? mb_strtoupper(mb_substr($author, 0, 1)) : '?';
        $colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe', '#43e97b', '#fa709a', '#fee140', '#fa709a'];
        $colorIndex = !empty($author) ? mb_strlen($author) % count($colors) : 0;
        $backgroundColor = $colors[$colorIndex];

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '">
            <rect width="' . $size . '" height="' . $size . '" fill="' . $backgroundColor . '"/>
            <text x="50%" y="50%" font-size="' . ($size * 0.5) . '" fill="white"
                font-family="Arial, sans-serif" font-weight="bold"
                text-anchor="middle" dominant-baseline="middle">' . htmlspecialchars($firstChar) . '</text>
        </svg>';

        return 'data:image/svg+xml,' . rawurlencode($svg);
    }

    // 使用国内Gravatar镜像
    $hash = md5(strtolower(trim($email)));
    return 'https://gravatar.loli.net/avatar/' . $hash . '?s=' . $size . '&d=mp';
}

// 渲染导航菜单项（仅一级）
function renderNavItems($items, $request) {
    $output = '';
    foreach ($items as $item) {
        $active = '';
        $currentUrl = rtrim($request->getRequestUrl(), '/');
        $itemUrl = rtrim($item['url'], '/');

        if ($currentUrl === $itemUrl || ($itemUrl === '/' && $request->is('index'))) {
            $active = 'active';
        }

        $output .= '<li>';
        $output .= '<a href="' . $item['url'] . '" class="' . $active . '">';
        if (!empty($item['icon'])) {
            $output .= '<span class="nav-icon">' . $item['icon'] . '</span>';
        }
        $output .= $item['name'];
        $output .= '</a>';
        $output .= '</li>';
    }
    return $output;
}