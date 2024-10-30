<?php

/**
 *
 * @return BlogBuzzTime
 */
function blogBuzzTime_Install()
{
    return Blogbuzztime::getInstance()->install();
}

/**
 *
 * @return BlogBuzzTime
 */
function blogBuzzTime_Uninstall()
{
    return Blogbuzztime::getInstance()->uninstall();
}

function blogBuzzTime_adminActions()
{
    return add_options_page("BlogBuzzTime", "BlogBuzzTime", 1, "BlogBuzzTime", "blogbuzztime_admin");
}

function blogBuzzTime_admin()
{
    if (!is_admin())
        throw new Exception('Forbidden', 403);

    include( Blogbuzztime::getInstance()->getPath() . '/views/admin_home.php');
}

function addBlogBuzzTimeJs()
{
    $blogbuzztime = BlogBuzzTime::getInstance();
    $return = '<script>';

    if ($blogbuzztime->getParameter('suffixUrl'))
        $return.='var _bbtSu = \'' . addcslashes($blogbuzztime->getParameter('suffixUrl'), '\'') . '\';';

    if ($blogbuzztime->getParameter('oneItem'))
        $return.= 'var _bbtSingle=\'' . addcslashes($blogbuzztime->getParameter('oneItem'), '\'') . '\';';

    if ($blogbuzztime->getParameter('manyItem'))
        $return.= 'var _bbtMany=\'' . addcslashes($blogbuzztime->getParameter('manyItem'), '\'') . '\';';

    if ($blogbuzztime->getParameter('nbItems'))
        $return.= 'var _bbtnbItems=\'' . addcslashes($blogbuzztime->getParameter('nbItems'), '\'') . '\';';

    if ($blogbuzztime->getParameter('minReaders'))
        $return.= 'var _bbtminReaders=\'' . addcslashes($blogbuzztime->getParameter('minReaders'), '\'') . '\';';

    if ($blogbuzztime->getParameter('lang') && $blogbuzztime->getParameter('lang') != '')
        $return.= 'var _bbtLang = \'' . $blogbuzztime->getParameter('lang') . '\';';
    else
        $return.= 'var _bbtLang = \'en\';';

    if ($blogbuzztime->getParameter('bbtTotalReaders'))
        $return.= 'var  _bbtTotalReaders = \'' . addcslashes($blogbuzztime->getParameter('bbtTotalReaders'), '\'') . '\';';

    if ($blogbuzztime->getParameter('bbtTotalReader'))
        $return.= 'var  _bbtTotalReader = \'' . addcslashes($blogbuzztime->getParameter('bbtTotalReader'), '\'') . '\';';

    $forbiddenUrls = $blogbuzztime->getParameter('forbidden_urls');
    if (!is_single() || isset($forbiddenUrls[$_SERVER['REQUEST_URI']]))
        $return.='var _bbtNotSendRead = true;';
    else
        $return.='var _bbtNotSendRead;';

    $return .= '(function(){var e=document.createElement("script");e.type="text/javascript";e.async=true;e.src="//assets.blogbuzztime.com/js/read.min.js";var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)})()';
    $return .= '</script>';
    echo $return;

    if ($blogbuzztime->getParameter('bbtFloatingBox'))
        blogbuztime_loadFloatingBox($blogbuzztime->getParameter('bbtFloatingBoxStyle'));
}

function blogbuztime_loadFloatingBox($styleColor = 'black')
{
    $blogbuzztime = BlogBuzzTime::getInstance();
    echo '
    <link rel="stylesheet" type="text/css" href="' . $blogbuzztime->getUrl() . 'css/blogbuzztime.css" />
    <div class="bbtfloatingBox bbtfloatingBox' . $styleColor . '">
        <div class="_bbt-rt-total-readers"></div>
        <div class="_bbt-rt-target"></div>
    </div>
    ';
}

function creditRoom_widget()
{
    return register_widget("CreditRoom_widget");
}

function blogbuzztime_widget()
{
    return register_widget("BlogBuzzTime_widget");
}

function blogbuzztime_widgetPicture()
{
    return register_widget("BlogBuzzTime_widgetPicture");
}

function blogbuzztime_widgetCounter()
{
    return register_widget("BlogBuzzTime_widgetCounter");
}

