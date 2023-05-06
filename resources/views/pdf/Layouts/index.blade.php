<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Scripts -->
	{{-- @vite(['resources/css/pdf.css','resources/js/app.js']) --}}
	<link rel="stylesheet" href="{{ asset('static/css/stylesheet.css') }}">
	<link rel="stylesheet" href="{{ asset('static/css/pdf.css') }}">
	{{-- <style>
		@font-face {
			font-family: 'Product Sans';
			src: url({{ storage_path('fonts/ProductSans/ProductSans-BoldItalic.woff2') }}) format('woff2'),
				url({{ storage_path('fonts/ProdcutSans/ProductSans-BoldItalic.woff') }}) format('woff'),
				url({{ storage_path('fonts/ProdcutSans/ProductSans-BoldItalic.ttf') }}) format('truetype');
			font-weight: bold;
			font-style: italic;
		}

		@font-face {
			font-family: 'Product Sans';
			src: url({{ storage_path('fonts/ProductSans/ProductSans-Regular.woff2') }}) format('woff2'),
				url({{ storage_path('fonts/ProductSans/ProductSans-Regular.woff') }}) format('woff'),
				url({{ storage_path('fonts/ProductSans/ProductSans-Regular.ttf') }}) format('truetype');
			font-weight: normal;
			font-style: normal;
		}

		@font-face {
			font-family: 'Product Sans';
			src: url({{ storage_path('ProductSans-Italic.woff2') }}) format('woff2'),
				url({{ storage_path('ProductSans-Italic.woff') }}) format('woff'),
				url({{ storage_path('ProductSans-Italic.ttf') }}) format('truetype');
			font-weight: normal;
			font-style: italic;
		}

		@font-face {
			font-family: 'Product Sans';
			src: url({{ storage_path('ProductSans-Bold.woff2') }}) format('woff2'),
				url({{ storage_path('ProductSans-Bold.woff') }}) format('woff'),
				url({{ storage_path('ProductSans-Bold.ttf') }}) format('truetype');
			font-weight: bold;
			font-style: normal;
		}

		*,
		html,
		body {
			font-family: 'Product Sans';
		}
	</style> --}}
</head>

<body>
	@yield('content')
</body>

</html>
