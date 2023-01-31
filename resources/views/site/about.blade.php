@include('layouts.guest.header')
    
    <!--Page Title-->
    <section class="page-title" style="background-image:url(guest/images/background/3.jpg);">
        <div class="auto-container">
            <h1>About Us</h1>
            <div class="title">Know Who We Are</div>
        </div>
    </section>
    <!--End Page Title-->
    
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
    
@include('layouts.guest.footer')