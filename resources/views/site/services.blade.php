@include('layouts.guest.header')
    
    <!--Page Title-->
    <section class="page-title" style="background-image:url(guest/images/background/3.jpg);">
        <div class="auto-container">
            <h1>Services</h1>
            <div class="title">What We Do For You</div>
        </div>
        <!--Page Info-->
        <div class="page-info">
            <div class="auto-container">
            	<div class="inner-container">
                    <ul class="bread-crumb">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>About us</li>
                    </ul>
                </div>
            </div>
        </div>
        <!--End Page Info-->
    </section>
    <!--End Page Title-->
    
    <!--Services Section-->
    <section class="services-section">
        <div class="auto-container">
            <!--Sec Title-->
            <div class="sec-title centered">
                <h2>Our Services</h2>
                <div class="title">What We Do For You</div>
                <div class="separator"></div>
            </div>
            <div class="row clearfix">
                
                <!--Services Block-->
                <div class="services-block col-md-4 col-sm-6 col-xs-12">
                    <div class="inner-box">
                        <div class="image">
                            <img src="{{ url('guest/images/resource/18.jpg') }}" alt="" />
                            <div class="icon-box">
                                <span class="icon flaticon-head"></span>
                            </div>
                            <div class="overlay-color"></div>
                        </div>
                        <div class="lower-box">
                            <div class="content">
                                <div class="number">01</div>
                                <h3><a href="services-single.html">Cyber Cafe Venues</a></h3>
                                <div class="text">Cyber Cafe Venues</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--Services Block-->
                <div class="services-block col-md-4 col-sm-6 col-xs-12">
                    <div class="inner-box">
                        <div class="image">
                            <img src="{{ url('guest/images/resource/7.jpg') }}" alt="" />
                            <div class="icon-box">
                                <span class="icon flaticon-open-book"></span>
                            </div>
                            <div class="overlay-color"></div>
                        </div>
                        <div class="lower-box">
                            <div class="content">
                                <div class="number">02</div>
                                <h3><a href="services-single.html">Gaming and Esports Event Management</a></h3>
                                <div class="text">Except to obtain some of advantages...</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--Services Block-->
                <div class="services-block col-md-4 col-sm-6 col-xs-12">
                    <div class="inner-box">
                        <div class="image">
                            <img src="{{ url('guest/images/resource/10.jpg') }}" alt="" />
                            <div class="icon-box">
                                <span class="icon flaticon-line-chart"></span>
                            </div>
                            <div class="overlay-color"></div>
                        </div>
                        <div class="lower-box">
                            <div class="content">
                                <div class="number">03</div>
                                <h3><a href="services-single.html">Professional Team Management</a></h3>
                                <div class="text">Some advantage from right all default...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Services Block-->
                <div class="services-block col-md-4 col-sm-6 col-xs-12">
                    <div class="inner-box">
                        <div class="image">
                            <img src="{{ url('guest/images/resource/13.jpg') }}" alt="" />
                            <div class="icon-box">
                                <span class="icon flaticon-head"></span>
                            </div>
                            <div class="overlay-color"></div>
                        </div>
                        <div class="lower-box">
                            <div class="content">
                                <div class="number">04</div>
                                <h3><a href="services-single.html">Food and Beverage Venture</a></h3>
                                <div class="text">Anyone who love pursue work itself...</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--Services Block-->
                <div class="services-block col-md-4 col-sm-6 col-xs-12">
                    <div class="inner-box">
                        <div class="image">
                            <img src="{{ url('guest/images/resource/11.jpg') }}" alt="" />
                            <div class="icon-box">
                                <span class="icon flaticon-open-book"></span>
                            </div>
                            <div class="overlay-color"></div>
                        </div>
                        <div class="lower-box">
                            <div class="content">
                                <div class="number">05</div>
                                <h3><a href="services-single.html">Web Development and Programming Services</a></h3>
                                <div class="text">Except to obtain some of advantages...</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--Services Block-->
                <div class="services-block col-md-4 col-sm-6 col-xs-12">
                    <div class="inner-box">
                        <div class="image">
                            <img src="{{ url('guest/images/resource/12.jpg') }}" alt="" />
                            <div class="icon-box">
                                <span class="icon flaticon-line-chart"></span>
                            </div>
                            <div class="overlay-color"></div>
                        </div>
                        <div class="lower-box">
                            <div class="content">
                                <div class="number">06</div>
                                <h3><a href="services-single.html">Full Marketing Services </a></h3>
                                <div class="text">Some advantage from right all default...</div>
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
    
@include('layouts.guest.footer')