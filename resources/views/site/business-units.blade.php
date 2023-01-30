@include('layouts.guest.header')
    
    <!--Page Title-->
    <section class="page-title" style="background-image:url(guest/images/background/2.jpg);">
        <div class="auto-container">
            <h1>Business Units</h1>
            <!-- <div class="title">Know Who We Are</div> -->
        </div>
        <!--Page Info-->
        <div class="page-info">
            <div class="auto-container">
                <div class="inner-container">
                    <ul class="bread-crumb">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>Business Units</li>
                    </ul>
                </div>
            </div>
        </div>
        <!--End Page Info-->
    </section>
    <!--End Page Title-->
    
    <!--About Section-->
    <div class="about-section">
        <div class="auto-container">
            <!--Sec Title-->
            <div class="sec-title centered">
                <h2>Business Units</h2>
                <div class="separator"></div>
            </div>
            <div class="inner-container clearfix">
                <!--Content Column-->
                <div class="content-column col-md-12 col-sm-12 col-xs-12">
                    <div class="inner-column">
                        <div class="profit-bar">
                            <h3>Business Units</h3>
                            <div class="separator"></div>
                            <div class="row clearfix">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="profit-text">Great explorer of the truth, master-builder human happiness one rejects dislikes or avoids pleasure itself because pleasure but because those who do not know how to pursue rationally consequences that are extremely painful.</div>
                                    <ul class="list-style-two">
                                        <li>Production technques (e.g. irrigation managment, recommended nitrogen inputs)</li>
                                        <li>Improving agricultural productivity in terms of quantity & quality.</li>
                                        <li>Minimizing the effects of pests (weeds, insects, pathogens, nematodes).</li>
                                    </ul>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="graph-image">
                                        <img src="{{ url('guest/images/resource/graph-3.jpg') }}" alt="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End About Section-->
    
@include('layouts.guest.footer')