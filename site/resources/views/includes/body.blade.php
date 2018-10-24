<!DOCTYPE html>
<html lang="{{\App::getLocale()}}" class="no-js">
<head prefix="{{\App\Utils\SocialMarkup::makeRegisteredPrefixes()}}">

	@include($includes->head)
	@yield('head')
	
</head>
<body>
	<div id="container">	
		@include($includes->header)

		@yield('content')

		@include($includes->footer)	
	</div>
</body>
</html>

