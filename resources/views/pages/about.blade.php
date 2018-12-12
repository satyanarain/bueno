@extends('layouts.master')

@section('content')
    @include('partials/static_links_nav')

    <section class="static_page_sec">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="well padding-md">
                        <h3 class="normal_header">About Bueno</h3>
                    </div> <!-- well ends -->

                    <div class="static_content col-xs-12">
                        <div class="main col-xs-12 col-sm-8 col-md-7">
                            <p>At Bueno we believe in goodness of food, communities and the environment. Our products are sourced directly from farmers to ensure that you get the best quality ingredients straight from the farm to your plate. With signature multigrain breads, fresh buns and patties, we offer an exciting range of sandwiches and burgers.</p>

                            <p>Our bakery team has handcrafted a special dessert menu with ingredients made from scratch. Nothing in our kitchen is outsourced as it is our endeavour to provide the best quality at the best price. We are closely associated with DIKSHA, a school which provides a stimulating learning environment to 250 children from low income families from the neighbourhood. We are now working towards making our packaging eco friendly with minimum waste and damage to the environment.</p>

                            <p><strong>Bueno Cafe Opening soon <br/>in the heart of Gurgaon!</strong></p>

                            <p><strong>108, Vijay Vihar <br/>Silokhera Road, Sector 30 <br/>(between BPTP Park Centre & Uniworld City) <br/>Gurgaon - 122001</strong></p>

                            <div class="team">

                                <h3 class="header">Meet the Team</h3>

                                <div class="panel-group" id="founder_accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel black panel-default">
                                        <div class="panel-heading" role="tab" id="titleFounder">
                                            <h4 class="panel-title">
                                                <a role="button" class="accordian_link arrow_down white collapsed" data-toggle="collapse" data-parent="#founder_accordion" href="#collapseFounder" aria-expanded="true" aria-controls="collapseFounder" class="full_width">
                                                    FOUNDER
                                                </a>
                                            </h4>
                                        </div> <!-- panel-heading ends -->
                                        <div id="collapseFounder" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFounder">
                                            <div class="panel-body">    
                                                <div class="member">
                                                    <h4 class="name">Rohan Arora</h4>
                                                    <p class="desc">Computer Engineering from University of Rajasthan, Jaipur &amp; MBA from FMS Delhi with 12 years of experience in PepsiCo, HUL, Olam International &amp; The Times Group.</p>
                                                </div> <!-- member ends -->
                                            </div> <!-- panel-body ends -->
                                        </div> <!-- panel-collapse ends -->
                                    </div> <!-- panel ends -->
                                </div> <!-- panel-group ends -->

                                <div class="panel-group" id="cofounder_accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel black panel-default">
                                        <div class="panel-heading" role="tab" id="titleCofounder">
                                            <h4 class="panel-title">
                                                <a role="button" class="accordian_link arrow_down white collapsed" data-toggle="collapse" data-parent="#cofounder_accordion" href="#collapseCofounder" aria-expanded="true" aria-controls="collapseCofounder" class="full_width">
                                                    TECHNOLOGY &amp; ENGINEERING
                                                </a>
                                            </h4>
                                        </div> <!-- panel-heading ends -->
                                        <div id="collapseCofounder" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleCofounder">
                                            <div class="panel-body">
                                                
                                                <div class="member">
                                                    <h4 class="name">Nagendra Singh</h4>
                                                    <p class="desc">Director at  <a href="http://opiant.in" target="_blank">Opiant Tech Solutions Private Limited</a>, is Project Management Professional with experience of more than 17 years in the field of Information Technology. Involved in IT Product Development, Project Management, Implementation and Delivery. Has acumen in catapulting bottom-lines and delighting stakeholders.</p>
                                                </div> <!-- member ends -->                                                
                                            </div> <!-- panel-body ends -->
                                        </div> <!-- panel-collapse ends -->
                                    </div> <!-- panel ends -->
                                </div> <!-- panel-group ends -->
								
                                <div class="panel-group" id="finance_accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel black panel-default">
                                        <div class="panel-heading" role="tab" id="titleFinance">
                                            <h4 class="panel-title">
                                                <a role="button" class="accordian_link arrow_down white collapsed" data-toggle="collapse" data-parent="#finance_accordion" href="#collapseFinance" aria-expanded="true" aria-controls="collapseFinance" class="full_width">
                                                    KITCHEN, SCM &amp; QUALITY
                                                </a>
                                            </h4>
                                        </div> <!-- panel-heading ends -->
                                        <div id="collapseFinance" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titleFinance">
                                            <div class="panel-body">

                                                <!--<div class="member">
                                                    <h4 class="name">Chef Vipul Arora</h4>
                                                    <p class="desc">Certified diploma holder in Culinary Arts from International Institute of Culinary Arts with a 7 years of experience with Olive Bar and Kitchen &amp; Hotel Intercontinental, Delhi.</p>
                                                </div> -->
                                                <!-- member ends -->

                                                <div class="member">
                                                    <h4 class="name">Chef Puneet Jain</h4>
                                                    <p class="desc">Diploma Holder in Hospitality &amp; Tourism Management from American Hotel and Lodging Association with 10 years of experience with the Oberoi, Trident Hilton &amp; McDonalds.</p>
                                                </div> <!-- member ends -->
                                                
                                            </div> <!-- panel-body ends -->
                                        </div> <!-- panel-collapse ends -->
                                    </div> <!-- panel ends -->
                                </div> <!-- panel-group ends -->

                            </div> <!-- team ends -->

                        </div> <!-- main ends -->
                        <div class="hero_sidebar col-xs-12 col-sm-4">
                            <ul class="hero_features">
                                <li><em><h6>5-Star Chef Team</h6></em></li>
                                <li><em><h6>Fresh &amp; Healthy gourmet meals</h6></em></li>
                                <li><em><h6>Delivery across Gurgaon</h6></em></li>
                            </ul> <!-- hero_features ends -->
                        </div> <!-- hero_sidebar ends -->
                    </div> <!-- static_content ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->



@stop