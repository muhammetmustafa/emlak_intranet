<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="tr" xmlns="http://www.w3.org/1999/xhtml" ng-app="{$uygulama_adi}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <base href="http://localhost/emlak_intranet/"/>
        <title>{$baslik}</title>
        {* CSS Stillerinin Eklenmesi *}
        {foreach $css as $href}
            <link rel="stylesheet" type="text/css" href="{$href}"/>
        {/foreach}
    </head>
    <body>

        {* Gövdenin Eklenmesi *}
        {include file="$sayfa.tpl"}

        {* JavaScript Dosyalarının Eklenmesi *}
        {foreach $js as $src}
            <script type="text/javascript" src="{$src}"></script>
        {/foreach}
    </body>
</html>