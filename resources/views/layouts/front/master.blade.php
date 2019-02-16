<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Spotifrog | @yield('title')</title>
    <meta name="description" content="{{ trans('global.description') }}">
    <meta name="keywords" content="{{ trans('global.description') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="/assets/fonts/font-awesome.css" rel="stylesheet" type="text/css">
    <link href="/assets/fonts/elegant-fonts.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900,400italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/zabuto_calendar.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/owl.carousel.css" type="text/css">

    <link rel="stylesheet" href="/assets/css/jquery.nouislider.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/style.css" type="text/css">


    <!-- Page level javascript -->
    @yield('page_level_css')
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>

<body class="nav-btn-only homepage">

<div class="page-wrapper">
    <header id="page-header">
        <nav>
            <div class="left">
                <a href="/" class="brand"><img src="/assets/img/logo.png" alt=""></a>
            </div>
            <!--end left-->
            <div class="right">
                <div class="primary-nav has-mega-menu">
                    <ul class="navigation">
                        <li class="active "><a href="/">Home</a></li>
                        <li><a href="/">Blog</a></li>
                        <li><a href="/">Contact</a></li>
                    </ul>
                    <!--end navigation-->
                </div>
                <!--end primary-nav-->
                <div class="secondary-nav">
                    <a href="#" data-modal-external-file="modal_sign_in.php" data-target="modal-sign-in">Sign In</a>
                    <a href="#" class="promoted" data-modal-external-file="modal_register.php" data-target="modal-register">Register</a>
                </div>
                <!--end secondary-nav-->
                <a href="#" class="btn btn-primary btn-small btn-rounded icon shadow add-listing" data-modal-external-file="modal_submit.php" data-target="modal-submit"><i class="fa fa-plus"></i><span>Add listing</span></a>
                <div class="nav-btn">
                    <i></i>
                    <i></i>
                    <i></i>
                </div>
                <!--end nav-btn-->
            </div>
            <!--end right-->
        </nav>
        <!--end nav-->
    </header>
    <!--end page-header-->
    {{--content area--}}
    @yield('content')

    <!--end page-content-->

    <footer id="page-footer">
        <div class="footer-wrapper">
            <div class="block">
                <div class="container">
                    <div class="vertical-aligned-elements">
                        <div class="element width-50">
                            <p data-toggle="modal" data-target="#myModal">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque aliquam at neque sit amet vestibulum. <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.</p>
                        </div>
                        <div class="element width-50 text-align-right">
                            <a href="#" class="circle-icon"><i class="social_twitter"></i></a>
                            <a href="#" class="circle-icon"><i class="social_facebook"></i></a>
                            <a href="#" class="circle-icon"><i class="social_youtube"></i></a>
                        </div>
                    </div>
                    <div class="background-wrapper">
                        <div class="bg-transfer opacity-50">
                            <img src="assets/img/footer-bg.png" alt="">
                        </div>
                    </div>
                    <!--end background-wrapper-->
                </div>
            </div>
            <div class="footer-navigation">
                <div class="container">
                    <div class="vertical-aligned-elements">
                        <div class="element width-50">(C) 2019 Your Company, All right reserved</div>
                        <div class="element width-50 text-align-right">
                            <a href="index.html">Home</a>
                            <a href="listing-grid-right-sidebar.html">Listings</a>
                            <a href="submit.html">Submit Item</a>
                            <a href="contact.html">Contact</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--end page-footer-->
</div>
@include('layouts.front.modal')
<!--end page-wrapper-->
<a href="#" class="to-top scroll" data-show-after-scroll="600"><i class="arrow_up"></i></a>

<script type="text/javascript" src="/assets/js/jquery-2.2.1.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyBEDfNcQRmKQEyulDN8nGWjLYPm8s4YB58&libraries=places"></script>
<script type="text/javascript" src="/assets/js/richmarker-compiled.js"></script>
<script type="text/javascript" src="/assets/js/markerclusterer_packed.js"></script>
<script type="text/javascript" src="/assets/js/infobox.js"></script>
<script type="text/javascript" src="/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery.fitvids.js"></script>
<script type="text/javascript" src="/assets/js/moment.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/assets/js/icheck.min.js"></script>
<script type="text/javascript" src="/assets/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery.nouislider.all.min.js"></script>
<script type="text/javascript" src="/assets/js/custom.js"></script>
<script type="text/javascript" src="/assets/js/maps.js"></script>


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
