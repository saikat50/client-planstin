<!DOCTYPE html>
<html lang="{{\App::getLocale()}}" class="no-js">
<head prefix="{{\App\Utils\SocialMarkup::makeRegisteredPrefixes()}}">

    @include($includes->head)
    @yield('head')

</head>
<body class="body-login bg-gray-3">
    <div id="container">    
	<div id="content">
	        @yield('content')
	</div>
        @include($includes->footer)
    </div>

    <pre class="hidden session-token">
        <?php echo 'level = ' . session('user.level') . "\n"; ?>
        <?php $token = session('token'); print_r($token ? $token->toArray() : 'no token'); ?>
        <?php print_r(session('salesforce.resources')); ?>
	</pre>
</body>
</html>

