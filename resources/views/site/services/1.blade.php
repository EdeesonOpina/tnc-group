@include('layouts.guest.header')
    
    <!--Page Title-->
    <section class="page-title" style="background-image:url({{ url('guest/images/background/3.jpg') }});">
        <div class="auto-container">
            <h1>Services</h1>
            <!-- <div class="title">Know Who We Are</div> -->
        </div>
    </section>
    <!--End Page Title-->
    
    <!--About Section-->
    <div class="about-section style-two">
        <div class="auto-container">

            <!--Sec Title-->
            <div class="sec-title centered">
                <h2>Our <span class="theme_color">Business Units</span><br>and <span class="theme_color">Services</span></h2>
            </div>
            
            <div class="row clearfix">
                <!--Carousel Column-->
                <div class="carousel-column col-md-6 col-sm-12 col-xs-12">
                    <div class="single-item-carousel owl-carousel owl-theme">
                        <div class="slide">
                            <div class="image">
                                <img src="{{ url('guest/images/resource/18.jpg') }}" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
                <!--Content Column-->
                <div class="content-column col-md-6 col-sm-12 col-xs-12">
                    <div class="inner-column">
                        <h3>Cyber Cafe Venues</h3>
                        <div class="text">In 2007, TNC began with a single location featuring four computer stations. Through strategic expansion and growth, the company has successfully established over 150 branches throughout the Philippines by 2019. With an impressive daily patronage of 20,000 gamers, TNC has solidified its position as the premier and fastest-growing cybercafe chain in the Philippines.</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--End About Section-->

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
    
@include('layouts.guest.footer')