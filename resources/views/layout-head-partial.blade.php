<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Fahrpark &raquo; @yield('page-title')</title>

  <!-- Bootstrap -->
  <link href="/css/bootstrap.min.css" rel="stylesheet">
  <link href="/css/fahrpark.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="/css/font-awesome.min.css" rel="stylesheet">

  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('/images/fahrpark.ico') }}" type="image/x-icon">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

  <style type="text/css">
    body {
      padding-top: 50px;
    }

    .content {
      padding: 40px 15px;
    }
  </style>
  @stack('head')
</head>