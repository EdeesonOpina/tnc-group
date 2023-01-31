@include('layouts.guest.header')
    
    <!--Page Title-->
    <section class="page-title" style="background-image:url({{ url('guest/images/background/3.jpg') }});">
        <div class="auto-container">
            <h1>Services</h1>
            <div class="title">What We Do For You</div>
        </div>
    </section>
    <!--End Page Title-->
    
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
                            <div class="icon-box">
                                <span class="icon flaticon-head"></span>
                            </div>
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
                                <div class="icon-box">
                                    <span class="icon flaticon-open-book"></span>
                                </div>
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
                            <div class="icon-box">
                                <span class="icon flaticon-line-chart"></span>
                            </div>
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
                            <div class="icon-box">
                                <span class="icon flaticon-head"></span>
                            </div>
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
                            <div class="icon-box">
                                <span class="icon flaticon-open-book"></span>
                            </div>
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
                            <div class="icon-box">
                                <span class="icon flaticon-line-chart"></span>
                            </div>
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
    
@include('layouts.guest.footer')