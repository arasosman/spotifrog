<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Spotify EE | @yield('title')</title>
    <meta name="description" content="{{ trans('global.description') }}">
    <meta name="keywords" content="{{ trans('global.description') }}">
    <meta property="og:title" content="spotify ee">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.spotify.ee/">
    <meta property="og:image" content="http://d2c87l0yth4zbw-2.global.ssl.fastly.net/i/_global/open-graph-default.png">
    <meta property="og:description" content="Spotify is all the music you’ll ever need.">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="/img/favicon.png" type="image/x-icon">
    <link rel="icon" href="/img/favicon.png" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/spot/images/touch-icon-144.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/spot/images/touch-icon-114.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/spot/images/touch-icon-72.png">
    <link rel="apple-touch-icon-precomposed" href="/spot/images/touch-icon-57.png">
    <link rel="stylesheet" type="text/css" href="/spot/css/app.css">


    <!-- Page level javascript -->
    @yield('page_level_css')
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>

<div id="content">
    @yield('content')
</div>

<footer class="container page-footer">
    <div class="page-footer--col">
        <ul class="nav nav-small">
            <li>
                <a href="#">Artists</a>
            </li>
            <li>
                <a href="#">About Ads</a>
            </li>
        </ul>
    </div>
    <div class="page-footer--col">
        <p>&copy; 2019 Spotify EE</p>
    </div>
</footer>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-2.1.4.min.js"><\/script>')</script>
<script src="/spot/js/main.min.js"></script>



<!-- Page level javascript -->
@yield('page_level_js')
<!-- Document Ready javascript -->
<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // add by uk to search case-insensitivity on client-side dataTable
        // This must be manually added to the desired search field
        $.extend($.expr[":"], {
            "containsI": function(elem, i, match, array) {
                return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });


        @yield('page_document_ready')
    });
</script>

</body>
</html>