<!DOCTYPE html>
<html>
    <head>
        {!! $theme->getSeo() !!}
    </head>
    {!! $theme->loadStylesheets() !!}
<body>
 {!! $theme->getHeader() !!}
 {!! $theme->getPageContent() !!}
 {!! $theme->getFooter() !!}
</body>
{!! $theme->loadScripts() !!}
</html>

