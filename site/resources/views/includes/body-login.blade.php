<!DOCTYPE html>
<html lang="{{\App::getLocale()}}" class="no-js">
<head prefix="{{\App\Utils\SocialMarkup::makeRegisteredPrefixes()}}">

	@include($includes->head)
	@yield('head')
	
</head>
<body class="body-login">
	<div id="container">    
    
        @yield('content')
        
        @include($includes->footer)
	</div>
</body>
</html>

