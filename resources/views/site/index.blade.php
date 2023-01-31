<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
@if (Request::is('/'))
    <title>TNC &dash; Home</title>
@elseif(Request::is('business-units'))
    <title>TNC &dash; Business Units</title>
@elseif(Request::is('services'))
    <title>TNC &dash; Services</title>
@elseif(Request::is('contact'))
    <title>TNC &dash; Contact</title>
@elseif(Request::is('about'))
    <title>TNC &dash; About Us</title>
@endif
<!-- Stylesheets -->
<link href="{{ url('guest/css/bootstrap.css') }}" rel="stylesheet">
<link href="{{ url('guest/plugins/revolution/css/settings.css') }}" rel="stylesheet" type="text/css"><!-- REVOLUTION SETTINGS STYLES -->
<link href="{{ url('guest/plugins/revolution/css/layers.css') }}" rel="stylesheet" type="text/css"><!-- REVOLUTION LAYERS STYLES -->
<link href="{{ url('guest/plugins/revolution/css/navigation.css') }}" rel="stylesheet" type="text/css"><!-- REVOLUTION NAVIGATION STYLES -->
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
                                            <li><a href="{{ url('/services/*') }}">All Services</a></li>
                                            <li><a href="{{ url('/services/1') }}">Cyber Cafe Venues</a></li>
                                            <li><a href="{{ url('/services/2') }}">Gaming and Esports Event Management</a></li>
                                            <li><a href="{{ url('/services/3') }}">Professional Team Management</a></li>
                                            <li><a href="{{ url('/services/4') }}">Food and Beverage Venture</a></li>
                                            <li><a href="{{ url('/services/5') }}">Web Development and Programming Services</a></li>
                                            <li><a href="{{ url('/services/6') }}">Full Marketing Services</a></li>
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
                    <a href="{{ url('/') }}" class="img-responsive"><img src="{{ url('guest/images/logo-small.png') }}" alt="" title="" width="120px"></a>
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
                                        <li><a href="{{ url('/services/*') }}">All Services</a></li>
                                        <li><a href="{{ url('/services/1') }}">Cyber Cafe Venues</a></li>
                                        <li><a href="{{ url('/services/2') }}">Gaming and Esports Event Management</a></li>
                                        <li><a href="{{ url('/services/3') }}">Professional Team Management</a></li>
                                        <li><a href="{{ url('/services/4') }}">Food and Beverage Venture</a></li>
                                        <li><a href="{{ url('/services/5') }}">Web Development and Programming Services</a></li>
                                        <li><a href="{{ url('/services/6') }}">Full Marketing Services</a></li>
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
    
    <!--Main Slider-->
    <section class="main-slider">
        
        <div class="rev_slider_wrapper fullwidthbanner-container"  id="rev_slider_one_wrapper" data-source="gallery">
            <div class="rev_slider fullwidthabanner" id="rev_slider_one" data-version="5.4.1">
                <ul>
                    
                    <li data-description="Slide Description" data-easein="default" data-easeout="default" data-fsmasterspeed="1500" data-fsslotamount="7" data-fstransition="fade" data-hideafterloop="0" data-hideslideonmobile="off" data-index="rs-1687" data-masterspeed="default" data-param1="" data-param10="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-rotate="0" data-saveperformance="off" data-slotamount="default" data-thumb="{{ url('guest/images/main-slider/image-1.jpg') }}" data-title="Slide Title" data-transition="parallaxvertical">
                    <img alt="" class="rev-slidebg" data-bgfit="cover" data-bgparallax="10" data-bgposition="center center" data-bgrepeat="no-repeat" data-no-retina="" src="{{ url('guest/images/main-slider/image-1.jpg') }}"> 
                    
                    <div class="tp-caption" 
                    data-paddingbottom="[0,0,0,0]"
                    data-paddingleft="[0,0,0,0]"
                    data-paddingright="[0,0,0,0]"
                    data-paddingtop="[0,0,0,0]"
                    data-responsive_offset="on"
                    data-type="text"
                    data-height="none"
                    data-width="['650','650','550','420']"
                    data-whitespace="normal"
                    data-hoffset="['15','15','15','15']"
                    data-voffset="['-60','-100','-100','-85']"
                    data-x="['left','left','left','left']"
                    data-y="['middle','middle','middle','middle']"
                    data-textalign="['top','top','top','top']"
                    data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                    style="z-index: 7; white-space: nowrap;text-transform:left;">
                        <h2>TNC Group</h2>
                    </div>
                    
                    <div class="tp-caption" 
                    data-paddingbottom="[0,0,0,0]"
                    data-paddingleft="[0,0,0,0]"
                    data-paddingright="[0,0,0,0]"
                    data-paddingtop="[0,0,0,0]"
                    data-responsive_offset="on"
                    data-type="text"
                    data-height="none"
                    data-width="['550','550','550','420']"
                    data-whitespace="normal"
                    data-hoffset="['15','15','15','15']"
                    data-voffset="['80','35','25','10']"
                    data-x="['left','left','left','left']"
                    data-y="['middle','middle','middle','middle']"
                    data-textalign="['top','top','top','top']"
                    data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                    style="z-index: 7; white-space: nowrap;text-transform:left;">
                        <div class="text">As a leading pioneer, key driver, and innovator with over 15 years of experience across various sectors of the gaming and esports industry, TNC has a proven track record of successfully navigating challenges and pushing beyond boundaries to achieve success.</div>
                    </div>
                    
                    <div class="tp-caption tp-resizeme" 
                    data-paddingbottom="[0,0,0,0]"
                    data-paddingleft="[0,0,0,0]"
                    data-paddingright="[0,0,0,0]"
                    data-paddingtop="[0,0,0,0]"
                    data-responsive_offset="on"
                    data-type="text"
                    data-height="none"
                    data-width="['550','550','550','420']"
                    data-whitespace="normal"
                    data-hoffset="['15','15','15','15']"
                    data-voffset="['170','130','130','110']"
                    data-x="['left','left','left','left']"
                    data-y="['middle','middle','middle','middle']"
                    data-textalign="['top','top','top','top']"
                    data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                    style="z-index: 7; white-space: nowrap;text-transform:left;">
                        <!-- <div class="btns-box">
                            <a href="about.html" class="theme-btn btn-style-one">Read More</a> <a href="services.html" class="theme-btn btn-style-two">Our Services</a>
                        </div> -->
                    </div>
                    
                    </li>
                    
                    <!-- <li data-description="Slide Description" data-easein="default" data-easeout="default" data-fsmasterspeed="1500" data-fsslotamount="7" data-fstransition="fade" data-hideafterloop="0" data-hideslideonmobile="off" data-index="rs-1688" data-masterspeed="default" data-param1="" data-param10="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-rotate="0" data-saveperformance="off" data-slotamount="default" data-thumb="{{ url('guest/images/main-slider/image-2.jpg') }}" data-title="Slide Title" data-transition="parallaxvertical">
                    <img alt="" class="rev-slidebg" data-bgfit="cover" data-bgparallax="10" data-bgposition="center center" data-bgrepeat="no-repeat" data-no-retina="" src="{{ url('guest/images/main-slider/image-2.jpg') }}"> 
                    
                    <div class="tp-caption" 
                    data-paddingbottom="[0,0,0,0]"
                    data-paddingleft="[0,0,0,0]"
                    data-paddingright="[0,0,0,0]"
                    data-paddingtop="[0,0,0,0]"
                    data-responsive_offset="on"
                    data-type="text"
                    data-height="none"
                    data-width="['550','650','550','420']"
                    data-whitespace="normal"
                    data-hoffset="['15','15','15','15']"
                    data-voffset="['-60','-100','-100','-85']"
                    data-x="['right','right','right','right']"
                    data-y="['middle','middle','middle','middle']"
                    data-textalign="['top','top','top','top']"
                    data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                    style="z-index: 7; white-space: nowrap;text-transform:left;">
                        <h2>Have a dream <br> join with stockton</h2>
                    </div>
                    
                    <div class="tp-caption" 
                    data-paddingbottom="[0,0,0,0]"
                    data-paddingleft="[0,0,0,0]"
                    data-paddingright="[0,0,0,0]"
                    data-paddingtop="[0,0,0,0]"
                    data-responsive_offset="on"
                    data-type="text"
                    data-height="none"
                    data-width="['550','650','550','420']"
                    data-whitespace="normal"
                    data-hoffset="['15','15','15','15']"
                    data-voffset="['80','35','25','10']"
                    data-x="['right','right','right','right']"
                    data-y="['middle','middle','middle','middle']"
                    data-textalign="['top','top','top','top']"
                    data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                    style="z-index: 7; white-space: nowrap;text-transform:left;">
                        <div class="text">Over 24 years experience and knowledge international standards, technologicaly changes.</div>
                    </div>
                    
                    <div class="tp-caption tp-resizeme" 
                    data-paddingbottom="[0,0,0,0]"
                    data-paddingleft="[0,0,0,0]"
                    data-paddingright="[0,0,0,0]"
                    data-paddingtop="[0,0,0,0]"
                    data-responsive_offset="on"
                    data-type="text"
                    data-height="none"
                    data-width="['550','650','550','420']"
                    data-whitespace="normal"
                    data-hoffset="['15','15','15','15']"
                    data-voffset="['170','130','130','110']"
                    data-x="['right','right','right','right']"
                    data-y="['middle','middle','middle','middle']"
                    data-textalign="['top','top','top','top']"
                    data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                    style="z-index: 7; white-space: nowrap;text-transform:left;">
                        <div class="btns-box">
                            <a href="about.html" class="theme-btn btn-style-one">Read More</a> <a href="services.html" class="theme-btn btn-style-two">Contact Us</a>
                        </div>
                    </div>
                    
                    </li>
                    
                    <li data-description="Slide Description" data-easein="default" data-easeout="default" data-fsmasterspeed="1500" data-fsslotamount="7" data-fstransition="fade" data-hideafterloop="0" data-hideslideonmobile="off" data-index="rs-1689" data-masterspeed="default" data-param1="" data-param10="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-rotate="0" data-saveperformance="off" data-slotamount="default" data-thumb="{{ url('guest/images/main-slider/image-3.jpg') }}" data-title="Slide Title" data-transition="parallaxvertical">
                    <img alt="" class="rev-slidebg" data-bgfit="cover" data-bgparallax="10" data-bgposition="center center" data-bgrepeat="no-repeat" data-no-retina="" src="{{ url('guest/images/main-slider/image-3.jpg') }}"> 
                    
                    <div class="tp-caption" 
                    data-paddingbottom="[0,0,0,0]"
                    data-paddingleft="[0,0,0,0]"
                    data-paddingright="[0,0,0,0]"
                    data-paddingtop="[0,0,0,0]"
                    data-responsive_offset="on"
                    data-type="text"
                    data-height="none"
                    data-width="['750','720','650','420']"
                    data-whitespace="normal"
                    data-hoffset="['15','15','15','15']"
                    data-voffset="['-60','-100','-100','-85']"
                    data-x="['center','center','center','center']"
                    data-y="['middle','middle','middle','middle']"
                    data-textalign="['top','top','top','top']"
                    data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                    style="z-index: 7; white-space: nowrap;text-transform:left;">
                        <h2 class="text-center">Best Business <br> and Consulting Service</h2>
                    </div>
                    
                    <div class="tp-caption" 
                    data-paddingbottom="[0,0,0,0]"
                    data-paddingleft="[0,0,0,0]"
                    data-paddingright="[0,0,0,0]"
                    data-paddingtop="[0,0,0,0]"
                    data-responsive_offset="on"
                    data-type="text"
                    data-height="none"
                    data-width="['550','720','550','420']"
                    data-whitespace="normal"
                    data-hoffset="['15','15','15','15']"
                    data-voffset="['80','35','25','10']"
                    data-x="['center','center','center','center']"
                    data-y="['middle','middle','middle','middle']"
                    data-textalign="['top','top','top','top']"
                    data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                    style="z-index: 7; white-space: nowrap;text-transform:left;">
                        <div class="text text-center">Over 24 years experience and knowledge international standards, technologicaly changes.</div>
                    </div>
                    
                    <div class="tp-caption tp-resizeme" 
                    data-paddingbottom="[0,0,0,0]"
                    data-paddingleft="[0,0,0,0]"
                    data-paddingright="[0,0,0,0]"
                    data-paddingtop="[0,0,0,0]"
                    data-responsive_offset="on"
                    data-type="text"
                    data-height="none"
                    data-width="['550','720','550','460']"
                    data-whitespace="normal"
                    data-hoffset="['15','15','15','15']"
                    data-voffset="['170','130','130','110']"
                    data-x="['center','center','center','center']"
                    data-y="['middle','middle','middle','middle']"
                    data-textalign="['top','top','top','top']"
                    data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'
                    style="z-index: 7; white-space: nowrap;text-transform:left;">
                        <div class="btns-box text-center">
                            <a href="about.html" class="theme-btn btn-style-one">Read More</a> <a href="services.html" class="theme-btn btn-style-two">Meet Our Team</a>
                        </div>
                    </div>
                    
                    </li> -->
                    
                </ul>
            </div>
        </div>
    </section>
    <!--End Main Slider-->
    
    <!--Services Section-->
    <section class="services-section">
        <div class="auto-container">
            <!--Sec Title-->
            <div class="sec-title centered">
                <h2>Our <span class="theme_color">Business Units</span><br>and <span class="theme_color">Services</span></h2>
                <div class="title">Let's explore the possibilities to work together!</div>
                <div class="separator"></div>
            </div>
            <div class="row clearfix">
                
                <!--Services Block-->
                <div class="services-block col-md-4 col-sm-6 col-xs-12">
                    <div class="inner-box">
                        <a href="{{ url('services/1') }}">
                        <div class="image">
                            <img src="{{ url('guest/images/resource/18.jpg') }}" alt="" />
                            <!-- <div class="icon-box">
                                <span class="icon flaticon-head"></span>
                            </div> -->
                            <div class="overlay-color"></div>
                        </div>
                        </a>
                        <div class="lower-box">
                            <div class="content">
                                <div class="number">01</div>
                                <h3><a href="{{ url('services/1') }}">Cyber Cafe Venues</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--Services Block-->
                <div class="services-block col-md-4 col-sm-6 col-xs-12">
                    <div class="inner-box">
                        <a href="{{ url('services/2') }}">
                            <div class="image">
                                <img src="{{ url('guest/images/resource/7.jpg') }}" alt="" />
                                <!-- <div class="icon-box">
                                    <span class="icon flaticon-open-book"></span>
                                </div> -->
                                <div class="overlay-color"></div>
                            </div>
                        </a>
                        <div class="lower-box">
                            <div class="content">
                                <div class="number">02</div>
                                <h3><a href="{{ url('services/2') }}">Gaming and Esports Event Management</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--Services Block-->
                <div class="services-block col-md-4 col-sm-6 col-xs-12">
                    <div class="inner-box">
                        <a href="{{ url('services/3') }}">
                        <div class="image">
                            <img src="{{ url('guest/images/resource/10.jpg') }}" alt="" />
                            <!-- <div class="icon-box">
                                <span class="icon flaticon-line-chart"></span>
                            </div> -->
                            <div class="overlay-color"></div>
                        </div>
                        </a>
                        <div class="lower-box">
                            <div class="content">
                                <div class="number">03</div>
                                <h3><a href="{{ url('services/3') }}">Professional Team Management</a></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Services Block-->
                <div class="services-block col-md-4 col-sm-6 col-xs-12">
                    <div class="inner-box">
                        <a href="{{ url('services/4') }}">
                        <div class="image">
                            <img src="{{ url('guest/images/resource/13.jpg') }}" alt="" />
                            <<!-- div class="icon-box">
                                <span class="icon flaticon-head"></span>
                            </div> -->
                            <div class="overlay-color"></div>
                        </div>
                        </a>
                        <div class="lower-box">
                            <div class="content">
                                <div class="number">04</div>
                                <h3><a href="{{ url('services/4') }}">Food and Beverage Venture</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--Services Block-->
                <div class="services-block col-md-4 col-sm-6 col-xs-12">
                    <div class="inner-box">
                        <a href="{{ url('services/5') }}">
                        <div class="image">
                            <img src="{{ url('guest/images/resource/11.jpg') }}" alt="" />
                            <!-- <div class="icon-box">
                                <span class="icon flaticon-open-book"></span>
                            </div> -->
                            <div class="overlay-color"></div>
                        </div>
                        </a>
                        <div class="lower-box">
                            <div class="content">
                                <div class="number">05</div>
                                <h3><a href="{{ url('services/5') }}">Web Development and Programming Services</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--Services Block-->
                <div class="services-block col-md-4 col-sm-6 col-xs-12">
                    <div class="inner-box">
                        <a href="{{ url('services/6') }}">
                        <div class="image">
                            <img src="{{ url('guest/images/resource/12.jpg') }}" alt="" />
                            <!-- <div class="icon-box">
                                <span class="icon flaticon-line-chart"></span>
                            </div> -->
                            <div class="overlay-color"></div>
                        </div>
                        </a>
                        <div class="lower-box">
                            <div class="content">
                                <div class="number">06</div>
                                <h3><a href="{{ url('services/6') }}">Full Marketing Services </a></h3>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="text-center">
                <!-- <a href="services.html" class="theme-btn btn-style-three">View All Services</a> -->
            </div>
            
        </div>
    </section>
    <!--End Services Section-->
    
    <!--Clients Section-->
    <section class="clients-section" style="background-image:url({{ url('guest/images/background/1.jpg') }})">
        <div class="auto-container">
            <h2>Home of the One True Phoenix</h2>
            <div class="text">TNC Predator is an esports organization in the Southeast Asian region founded in 2015. TNC have made its mark in the esports landscape by achieving significant career milestones through the past years which led to its status as a global contender in its main game title, Dota 2.</div>
            <!--Sponsors Box-->
            <div class="sponsors-box">
                <div class="sponsors-outer">
                    <!--Sponsors Carousel-->
                    <ul class="sponsors-carousel owl-carousel owl-theme">
                        <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ url('guest/images/clients/1.png') }}" alt=""></a></figure></li>
                        <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ url('guest/images/clients/2.png') }}" alt=""></a></figure></li>
                        <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ url('guest/images/clients/3.png') }}" alt=""></a></figure></li>
                        <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ url('guest/images/clients/4.png') }}" alt=""></a></figure></li>
                        <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ url('guest/images/clients/5.png') }}" alt=""></a></figure></li>
                        <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ url('guest/images/clients/6.png') }}" alt=""></a></figure></li>
                        <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ url('guest/images/clients/1.png') }}" alt=""></a></figure></li>
                        <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ url('guest/images/clients/2.png') }}" alt=""></a></figure></li>
                        <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ url('guest/images/clients/3.png') }}" alt=""></a></figure></li>
                        <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ url('guest/images/clients/4.png') }}" alt=""></a></figure></li>
                        <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ url('guest/images/clients/5.png') }}" alt=""></a></figure></li>
                        <li class="slide-item"><figure class="image-box"><a href="#"><img src="{{ url('guest/images/clients/6.png') }}" alt=""></a></figure></li>
                    </ul>
                </div>
            </div>
            
        </div>
    </section>
    <!--End Clients Section-->
    
    <!--About Section-->
    <div class="about-section">
        <div class="auto-container">
            <!--Sec Title-->
            <div class="sec-title centered">
                <h2>About Our Company</h2>
                <div class="title">Know Who We Are</div>
                <div class="separator"></div>
            </div>
            <div class="inner-container clearfix">
                <!--Content Column-->
                <div class="content-column col-md-12 col-sm-12 col-xs-12">
                    <div class="inner-column">
                        <center><h3>Message from Our Founder</h3></center>
                        <div class="blockquote-box">
                            <div class="inner">
                                <center><img src="{{ url('guest/images/resource/about.jpg') }}" alt="" />
                                <div class="quote-icon">
                                    <span class="icon flaticon-left-quote"></span>
                                </div>
                                <div class="quote-text">When I started TNC, it has become my aspiration to elevate the gaming industry in all directions and capacity, which will ensure suitable and favorable circumstances to all generations, who go after their enthusiasm for gaming. All of these people from different generations simply inspire and motivate me to make great efforts to achieve a better world for gaming scene.</div>
                                <div class="author">Eric Redulfin,</div>
                                <div class="author">Founder and President</div>
                                <div class="author">TNC Group</div>
                                </center>
                            </div>
                        </div>
                        <!-- <a href="about.html" class="theme-btn btn-style-three">More About Us</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End About Section-->
    
    <!--Project Section-->
    <!-- <section class="project-section">
        
        <div class="project-tab">
            <div class="auto-container">
                <div class="sec-title clearfix">
                    <div class="pull-left">
                        <h2>Projects and Achievements</h2>
                        <div class="title">Take a look at some of our milestones and cool things we got our hands on over the past years.</div>
                        <div class="separator"></div>
                    </div>
                    <div class="tab-btns-box pull-right">
                        <div class="tabs-header">
                            <ul class="product-tab-btns clearfix">
                                <li class="p-tab-btn active-btn" data-tab="#p-tab-1">View All</li>
                                <li class="p-tab-btn" data-tab="#p-tab-2">Consulting</li>
                                <li class="p-tab-btn" data-tab="#p-tab-3">Finance</li>
                                <li class="p-tab-btn" data-tab="#p-tab-4">Marketing</li>
                                <li class="p-tab-btn" data-tab="#p-tab-5">Growth</li>
                                <li class="p-tab-btn" data-tab="#p-tab-6">Technical</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-tabs-content">
                <div class="p-tab active-tab" id="p-tab-1">
                    <div class="project-carousel owl-theme owl-carousel">
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/3.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Marketing & Growth</div>
                                        <h3><a href="cases-single.html">Transformation sparks financial income for cleints.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/4.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Finance & Consulting</div>
                                        <h3><a href="cases-single.html">Constructing the best in class global asset.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/5.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Growth & Technical</div>
                                        <h3><a href="cases-single.html">Focus on core delivers growth for retailer trading.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/4.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Finance & Consulting</div>
                                        <h3><a href="cases-single.html">Constructing the best in class global asset.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/5.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Growth & Technical</div>
                                        <h3><a href="cases-single.html">Focus on core delivers growth for retailer trading.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="p-tab" id="p-tab-2">
                    <div class="project-carousel owl-theme owl-carousel">
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/3.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Marketing & Growth</div>
                                        <h3><a href="cases-single.html">Transformation sparks financial income for cleints.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/4.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Finance & Consulting</div>
                                        <h3><a href="cases-single.html">Constructing the best in class global asset.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/5.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Growth & Technical</div>
                                        <h3><a href="cases-single.html">Focus on core delivers growth for retailer trading.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/4.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Finance & Consulting</div>
                                        <h3><a href="cases-single.html">Constructing the best in class global asset.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/5.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Growth & Technical</div>
                                        <h3><a href="cases-single.html">Focus on core delivers growth for retailer trading.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="p-tab" id="p-tab-3">
                    <div class="project-carousel owl-theme owl-carousel">
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/3.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Marketing & Growth</div>
                                        <h3><a href="cases-single.html">Transformation sparks financial income for cleints.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/4.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Finance & Consulting</div>
                                        <h3><a href="cases-single.html">Constructing the best in class global asset.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/5.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Growth & Technical</div>
                                        <h3><a href="cases-single.html">Focus on core delivers growth for retailer trading.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/4.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Finance & Consulting</div>
                                        <h3><a href="cases-single.html">Constructing the best in class global asset.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/5.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Growth & Technical</div>
                                        <h3><a href="cases-single.html">Focus on core delivers growth for retailer trading.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="p-tab" id="p-tab-4">
                    <div class="project-carousel owl-theme owl-carousel">
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/3.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Marketing & Growth</div>
                                        <h3><a href="cases-single.html">Transformation sparks financial income for cleints.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/4.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Finance & Consulting</div>
                                        <h3><a href="cases-single.html">Constructing the best in class global asset.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/5.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Growth & Technical</div>
                                        <h3><a href="cases-single.html">Focus on core delivers growth for retailer trading.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/4.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Finance & Consulting</div>
                                        <h3><a href="cases-single.html">Constructing the best in class global asset.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/5.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Growth & Technical</div>
                                        <h3><a href="cases-single.html">Focus on core delivers growth for retailer trading.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="p-tab" id="p-tab-5">
                    <div class="project-carousel owl-theme owl-carousel">
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/3.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Marketing & Growth</div>
                                        <h3><a href="cases-single.html">Transformation sparks financial income for cleints.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/4.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Finance & Consulting</div>
                                        <h3><a href="cases-single.html">Constructing the best in class global asset.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/5.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Growth & Technical</div>
                                        <h3><a href="cases-single.html">Focus on core delivers growth for retailer trading.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/4.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Finance & Consulting</div>
                                        <h3><a href="cases-single.html">Constructing the best in class global asset.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/5.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Growth & Technical</div>
                                        <h3><a href="cases-single.html">Focus on core delivers growth for retailer trading.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="p-tab" id="p-tab-6">
                    <div class="project-carousel owl-theme owl-carousel">
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/3.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Marketing & Growth</div>
                                        <h3><a href="cases-single.html">Transformation sparks financial income for cleints.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/4.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Finance & Consulting</div>
                                        <h3><a href="cases-single.html">Constructing the best in class global asset.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/5.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Growth & Technical</div>
                                        <h3><a href="cases-single.html">Focus on core delivers growth for retailer trading.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/4.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Finance & Consulting</div>
                                        <h3><a href="cases-single.html">Constructing the best in class global asset.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/5.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Growth & Technical</div>
                                        <h3><a href="cases-single.html">Focus on core delivers growth for retailer trading.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/1.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Consulting & Growth</div>
                                        <h3><a href="cases-single.html">Developing a stategy and roadmap.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gallery-block">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="{{ url('guest/images/gallery/2.jpg') }}" alt="" />
                                    <div class="overlay-box">
                                        <a href="cases-single.html" class="link-box"><span class="fa fa-link"></span></a>
                                    </div>
                                    <div class="lower-box">
                                        <div class="designation">Technical & Finance</div>
                                        <h3><a href="cases-single.html">Leading consumer products company.</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
            
        </div>
        
    </section> -->
    <!--End Project Section-->
    
    <!--Fluid Section One-->
    <section class="fluid-section-one">
        <div class="outer-container clearfix">
            <div class="left-box" style="background-image:url({{ url('guest/images/resource/image-1.jpg') }});"></div>
            <div class="right-box" style="background-image:url({{ url('guest/images/resource/image-2.png') }}') }})"></div>
            <!--Image Column-->
            <div class="image-column">
                <div class="inner-column clearfix">
                    <div class="content">
                        <div class="row clearfix">
                            <div class="column col-md-6 col-sm-6 col-xs-12">
                                <h2>Got questions? Our team will help you out on that!</h2>
                            </div>
                            <div class="column col-md-6 col-sm-6 col-xs-12">
                                <div class="text">We are dedicated to identifying and meeting the needs of our partners and clients, and stand ready to assist you in any way we can. </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Content Column-->
            <div class="content-column">
                <div class="inner-column">
                    <h2>Contact Us</h2>
                    <div>Fill out the form below and someone from our team will reach out to you as soon as we can!</div>
                    <div class="separator"></div>
                    
                    <!-- Appointment Form -->
                    <div class="apointment-form">
                        <!--Call Back Form-->
                        <form method="post" action="{{ url('contact') }}">
                            <div class="row clearfix">
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="username" placeholder="Your Name" required>
                                </div>
            
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <input type="email" name="email" placeholder="Email Address" required>
                                </div>

                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <textarea name="description" placeholder="Enter message here" required></textarea>
                                </div>
                                
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <button class="theme-btn btn-style-one" type="submit" name="submit-form">Submit Here</button>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
    <!--End Fluid Section One-->

    <!--Default Section-->
    <section class="default-section">
        <div class="auto-container">
            <div class="row clearfix">
                <!--Accordian Column-->
                <div class="accordian-column col-md-6 col-sm-12 col-xs-12">
                    
                    <!--Accordian Box-->
                    <ul class="accordion-box">
                        
                        <!--Block-->
                        <li class="accordion block">
                            <div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-arrow-down"></span></div>Application</div>
                            <div class="acc-content">
                                <div class="content">
                                    <div class="text">To start your Cyber Cafe business, submit your application to the Cyber Cafe Team of TNC for review.</div>
                                </div>
                            </div>
                        </li>

                        <!--Block-->
                        <li class="accordion block">
                            <div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-arrow-down"></span></div>Survey and Approval</div>
                            <div class="acc-content">
                                <div class="content">
                                    <div class="text">Once the application has been reviewed, a survey team will do an onsite checking of your location to conduct a feasibility study subject for the approval of the Cyber Cafe Team.</div>
                                </div>
                            </div>
                        </li>
                        
                        <!--Block-->
                        <li class="accordion block active-block">
                            <div class="acc-btn"><div class="icon-outer"><span class="icon icon-plus fa fa-arrow-down"></span></div>Planning and Construction</div>
                            <div class="acc-content">
                                <div class="content">
                                    <div class="text">Upon approval, the planning and construction process will commence. This will be spearheaded by TNC's experienced group of professionals who excel in the construction field.</div>
                                </div>
                            </div>
                        </li>

                        <!--Block-->
                        <li class="accordion block active-block">
                            <div class="acc-btn active"><div class="icon-outer"><span class="icon icon-plus fa fa-arrow-down"></span></div>Turnover and Operate</div>
                            <div class="acc-content current">
                                <div class="content">
                                    <div class="text">All that's left is for TNC to turnover the business and for you the operate and manage your business!</div>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
                <!--Counter Column-->
                <div class="counter-column col-md-6 col-sm-12 col-xs-12">
                    <div class="inner-column">
                        <div class="text">Fall down, but Always Rise!</div>
                        
                        <!--Fact Counter-->
                        <div class="fact-counter">
                            <div class="row clearfix">
                                
                                <!--Column-->
                                <div class="column counter-column col-md-6 col-sm-6 col-xs-12">
                                    <div class="inner">
                                        <span class="icon flaticon-idea"></span>
                                        <div class="count-outer count-box">
                                            <span class="count-text" data-speed="4000" data-stop="2745">0</span>
                                        </div>
                                        <h4 class="counter-title">Experienced Advisors</h4>
                                    </div>
                                </div>
                        
                                <!--Column-->
                                <div class="column counter-column col-md-6 col-sm-6 col-xs-12">
                                    <div class="inner">
                                        <span class="icon flaticon-telemarketer"></span>
                                        <div class="count-outer count-box">
                                            <span class="count-text" data-speed="2000" data-stop="240">0</span>
                                        </div>
                                        <h4 class="counter-title">Worldwide Locations</h4>
                                    </div>
                                </div>
                        
                                <!--Column-->
                                <div class="column counter-column col-md-6 col-sm-6 col-xs-12">
                                    <div class="inner">
                                        <span class="icon flaticon-smiling-emoticon-square-face"></span>
                                        <div class="count-outer count-box">
                                            <span class="count-text" data-speed="2500" data-stop="1564">0</span>
                                        </div>
                                        <h4 class="counter-title">Years of Experience</h4>
                                    </div>
                                </div>
                        
                                <!--Column-->
                                <div class="column counter-column col-md-6 col-sm-6 col-xs-12">
                                    <div class="inner">
                                        <span class="icon flaticon-medal"></span>
                                        <div class="count-outer count-box">
                                            <span class="count-text" data-speed="4000" data-stop="172">0</span>
                                        </div>
                                        <h4 class="counter-title">Satisfied Customers</h4>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    <!--End Default Section-->
    
    <!--Main Footer-->
    <footer class="main-footer">
        <div class="auto-container">
            <!--Widgets Section-->
            <div class="widgets-section">
                <div class="row clearfix">
                    <!--Footer Column-->
                    <div class="footer-column col-md-5 col-sm-12 col-xs-12">
                        <div class="footer-widget logo-widget">
                            <div class="footer-logo">
                                <div class="logo"><a href="{{ url('/') }}"><img src="{{ url('guest/images/footer-logo.png') }}" alt="" width="120px"></a></div>
                            </div>
                            <div class="widget-content">
                                <div class="text">As a leading pioneer, key driver, and innovator with over 15 years of experience across various sectors of the gaming and esports industry, TNC has a proven track record of successfully navigating challenges and pushing beyond boundaries to achieve success.</div>
                                <h3>Contact:</h3>
                                <div class="row clearfix">
                                    <div class="inner-column col-md-6 col-sm-6 col-xs-12">
                                        <div class="info-text">13, 2nd Floor, CVJ Bldg. Nicanor Roxas St. Quezon City, Metro Manila Philippines.</div>
                                    </div>
                                    <div class="inner-column col-md-6 col-sm-6 col-xs-12">
                                        <ul>
                                            <!-- <li><span>Ph:</span> +123-456-7890</li> -->
                                            <li><span>Email:</span> support@tnc.com.ph</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Footer Column-->
                    <div class="footer-column col-md-7 col-sm-12 col-xs-12">
                        <div class="row clearfix">
                        
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="news-widget">
                                    <h2>Achievements</h2>
                                    <div class="widget-content">
                                        
                                        <article class="post">
                                            <figure class="post-thumb">
                                                <a href="https://tnc.com.ph/proteam/"><img src="{{ url('guest/images/resource/news-1.jpg') }}" alt=""></a>
                                                <a class="overlay"><span class="fa fa-link"></span></a>
                                            </figure>
                                            <div class="content">
                                                <div class="text"><a href="https://tnc.com.ph/proteam/">MDL Major Champion</a></div>
                                                <div class="post-info">MDL Major is the first major event which TNC Predator won.</div>
                                            </div>
                                        </article>
                                        
                                        <article class="post">
                                            <figure class="post-thumb">
                                                <a href="https://tnc.com.ph/proteam/"><img src="{{ url('guest/images/resource/news-2.jpg') }}" alt=""></a>
                                                <a class="overlay"><span class="fa fa-link"></span></a>
                                            </figure>
                                            <div class="content">
                                                <div class="text"><a href="https://tnc.com.ph/proteam/">ESL One Hamburg 2019 Champion</a></div>
                                                <div class="post-info">The first ESL One title bagged by TNC Predator was in Hamburg.</div>
                                            </div>
                                        </article>
                                        
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="links-widget">
                                    <h2>Navigation</h2>
                                    <div class="widget-content">
                                        <ul class="links">
                                            <li><a href="{{ url('/') }}">Home</a></li>
                                            <li><a href="{{ url('/business-units') }}">Business Units</a></li>
                                            <li><a href="{{ url('/services') }}">Services</a></li>
                                            <li><a href="{{ url('/contact') }}">Contact</a></li>
                                            <li><a href="{{ url('/about') }}">About Us</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!--Footer Bottom-->
        <div class="footer-bottom">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="copyright-column col-md-5 col-sm-12 col-xs-12">
                        <div class="copyright">Copyrights © {{ date('Y') }} All Rights Reserved by <a href="#">TNC Group.</a></div>
                    </div>
                    <div class="nav-column col-md-7 col-sm-12 col-xs-12">
                        <ul class="footer-nav">
                            <li><a href="#">Legal</a></li>
                            <li><a href="#">Sitemap</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms & Condition</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--End Main Footer-->
    
</div>
<!--End pagewrapper-->

<!--Scroll to top-->
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="icon fa fa-arrow-up"></span></div>

<script src="{{ url('guest/js/jquery.js') }}"></script> 
<!--Revolution Slider-->
<script src="{{ url('guest/plugins/revolution/js/jquery.themepunch.revolution.min.js') }}"></script>
<script src="{{ url('guest/plugins/revolution/js/jquery.themepunch.tools.min.js') }}"></script>
<script src="{{ url('guest/plugins/revolution/js/extensions/revolution.extension.actions.min.js') }}"></script>
<script src="{{ url('guest/plugins/revolution/js/extensions/revolution.extension.carousel.min.js') }}"></script>
<script src="{{ url('guest/plugins/revolution/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
<script src="{{ url('guest/plugins/revolution/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
<script src="{{ url('guest/plugins/revolution/js/extensions/revolution.extension.migration.min.js') }}"></script>
<script src="{{ url('guest/plugins/revolution/js/extensions/revolution.extension.navigation.min.js') }}"></script>
<script src="{{ url('guest/plugins/revolution/js/extensions/revolution.extension.parallax.min.js') }}"></script>
<script src="{{ url('guest/plugins/revolution/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
<script src="{{ url('guest/plugins/revolution/js/extensions/revolution.extension.video.min.js') }}"></script>
<script src="{{ url('guest/js/main-slider-script.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ url('guest/js/bootstrap.min.js') }}"></script>
<script src="{{ url('guest/js/jquery.fancybox.js') }}"></script>
<script src="{{ url('guest/js/jquery-ui.js') }}"></script>
<script src="{{ url('guest/js/owl.js') }}"></script>
<script src="{{ url('guest/js/appear.js') }}"></script>
<script src="{{ url('guest/js/wow.js') }}"></script>
<script src="{{ url('guest/js/script.js') }}"></script>

</body>
</html>