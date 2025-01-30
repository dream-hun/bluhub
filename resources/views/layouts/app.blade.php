<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Your reliable secure and hosting service provider</title>
    <!-- Preconnect to Google Fonts and Google Fonts Static -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Importing Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,500;0,600;0,700;1,400;1,800&display=swap"
        rel="stylesheet">
    <!-- all styles -->
    <link rel="preload stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" as="style">
    <!-- fontawesome css -->
    <link rel="preload stylesheet" href="{{ asset('assets/css/plugins/fontawesome.min.css') }}" as="style">
    <!-- Custom css -->
    <link rel="preload stylesheet" href="{{ asset('assets/css/style.css') }}" as="style">
</head>

<body>
    <x-header-component />
    {{ $slot }}
    <x-footer-component />
</body>

</html>
