@include('layouts.guest.header')
    
    <!--Page Title-->
    <section class="page-title" style="background-image:url(guest/images/background/1.jpg);">
        <div class="auto-container">
            <h1>Contact</h1>
            <div class="title">Get Every Single Answers And Note Here.</div>
        </div>
        <!--Page Info-->
        <div class="page-info">
            <div class="auto-container">
            	<div class="inner-container">
                    <ul class="bread-crumb">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>Contact</li>
                    </ul>
                </div>
            </div>
        </div>
        <!--End Page Info-->
    </section>
    <!--End Page Title-->
    
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
                                <h2>Get Every Single Answers And Note Here.</h2>
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
                    <div class="title">For Any Enquiry</div>
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