<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>TNC</title>
<!-- Stylesheets -->
<link href="{{ url('guest/css/bootstrap.css') }}" rel="stylesheet">
<link href="{{ url('guest/css/style.css') }}" rel="stylesheet">
<link href="{{ url('guest/css/responsive.css') }}" rel="stylesheet">

<!--Favicon-->
<link rel="shortcut icon" href="{{ url('guest/images/favicon.png') }}" type="image/x-icon">
<link rel="icon" href="{{ url('guest/images/favicon.png') }}" type="image/x-icon">
<!-- Responsive -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
<!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->
</head>

<body>
<div class="page-wrapper">
    
    <!-- Preloader -->
    <div class="preloader"></div>
    
    <!-- Main Header-->
    <header class="main-header">
    
        <!--Header Top-->
        <div class="header-top">
            <div class="auto-container">
                <div class="clearfix">
                    <div class="top-left">
                        <ul class="clearfix">
                            <!-- <li><a href="#"><span class="icon fa fa-phone"></span>+123-456-7890</a></li> -->
                            <li><a href="mailto:support@tnc.com.ph"><span class="icon fa fa-envelope"></span>support@tnc.com.ph</a></li>
                        </ul>
                    </div>
                    <div class="top-right clearfix">
                        <ul class="clearfix">
                            <li>
                                <div class="social-links">
                                    <span class="connect">Stay Connected:</span>
                                    <a href="https://www.facebook.com/TNCProTeamDota2"><span class="fa fa-facebook-f"></span></a>
                                    <a href="https://twitter.com/TNCPredator"><span class="fa fa-twitter"></span></a>
                                    <a href="https://www.youtube.com/channel/UCS1-hvxU11xtOxDfJmhDlEg"><span class="fa fa-youtube"></span></a>
                                    <a href="https://www.instagram.com/tncpredator/"><span class="fa fa-instagram"></span></a>
                                </div>
                            </li>
                        </ul>
                        <a href="{{ url('/contact') }}" class="stay-connect">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    
        <!--Header-Upper-->
        <div class="header-upper">
            <div class="auto-container clearfix">
                    
                <div class="pull-left logo-outer">
                    <div class="logo"><a href="{{ url('/') }}"><img src="{{ url('guest/images/logo.png') }}" alt="" title="" width="150px"></a></div>
                </div>
                
                <div class="pull-right upper-right clearfix">
                    
                    <div class="nav-outer clearfix">
                        <!-- Main Menu -->
                        <nav class="main-menu">
                            <div class="navbar-header">
                                <!-- Toggle Button -->      
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                </button>
                            </div>
                            
                            <div class="navbar-collapse collapse clearfix">
                                <ul class="navigation clearfix">
                                    <li><a href="{{ url('/') }}">Home</a></li>
                                    <li><a href="{{ url('/business-units') }}">Business Units</a></li>
                                    <li class="dropdown"><a href="#">Services</a>
                                        <ul>
                                            <li><a href="{{ url('/services') }}">All Services</a></li>
                                            <li><a href="{{ url('/services') }}">Cyber Cafe Venues</a></li>
                                            <li><a href="{{ url('/services') }}">Gaming and Esports Event Management</a></li>
                                            <li><a href="{{ url('/services') }}">Professional Team Management</a></li>
                                            <li><a href="{{ url('/services') }}">Food and Beverage Venture</a></li>
                                            <li><a href="{{ url('/services') }}">Web Development and Programming Services</a></li>
                                            <li><a href="{{ url('/services') }}">Full Marketing Services</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="{{ url('/contact') }}">Contact</a></li>
                                    <li><a href="{{ url('/about') }}">About Us</a></li>
                                </ul>
                            </div>
                        </nav>
                        
                        <!-- Main Menu End-->
                        <div class="outer-box">
                            <!--Search Box-->
                            <!-- <div class="search-box-outer">
                                <div class="dropdown">
                                    <button class="search-box-btn dropdown-toggle" type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-search"></span></button>
                                    <ul class="dropdown-menu pull-right search-panel" aria-labelledby="dropdownMenu3">
                                        <li class="panel-outer">
                                            <div class="form-container">
                                                <form method="post" action="blog.html">
                                                    <div class="form-group">
                                                        <input type="search" name="field-name" value="" placeholder="Search Here" required>
                                                        <button type="submit" class="search-btn"><span class="fa fa-search"></span></button>
                                                    </div>
                                                </form>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div> -->
                            
                        </div>
                    </div>
                    
                </div>
                    
            </div>
        </div>
        <!--End Header Upper-->
        
        <!--Sticky Header-->
        <div class="sticky-header">
            <div class="auto-container clearfix">
                <!--Logo-->
                <div class="logo pull-left">
                    <a href="index.html" class="img-responsive"><img src="{{ url('guest/images/logo-small.png') }}" alt="" title="" width="120px"></a>
                </div>
                
                <!--Right Col-->
                <div class="right-col pull-right">
                    <!-- Main Menu -->
                    <nav class="main-menu">
                        <div class="navbar-header">
                            <!-- Toggle Button -->      
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        
                        <div class="navbar-collapse collapse clearfix">
                            <ul class="navigation clearfix">
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="{{ url('/business-units') }}">Business Units</a></li>
                                <li class="dropdown"><a href="#">Services</a>
                                    <ul>
                                        <li><a href="{{ url('/services') }}">Cyber Cafe Venues</a></li>
                                        <li><a href="{{ url('/services') }}">Gaming and Esports Event Management</a></li>
                                        <li><a href="{{ url('/services') }}">Professional Team Management</a></li>
                                        <li><a href="{{ url('/services') }}">Food and Beverage Venture</a></li>
                                        <li><a href="{{ url('/services') }}">Web Development and Programming Services</a></li>
                                        <li><a href="{{ url('/services') }}">Full Marketing Services</a></li>
                                    </ul>
                                </li>

                                <li><a href="{{ url('contact') }}">Contact</a></li>
                                <li><a href="{{ url('about') }}">About Us</a></li>
                            </ul>
                        </div>
                    </nav><!-- Main Menu End-->
                </div>
                
            </div>
        </div>
        <!--End Sticky Header-->
    
    </header>
    <!--End Main Header -->