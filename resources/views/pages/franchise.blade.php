@extends('layouts.master')

@section('content')

    @include('partials/static_links_nav_second')

    <section class="static_page_sec feedback_sec">

        <div class="container more">
            <div class="row">
                @include('partials.errors')
                @include('partials.flash')
                <div class="col-xs-12">

                    <div class="well padding-md">
                        <h3 class="normal_header">The Call for Good Food Business!</h3>
                    </div> <!-- well ends -->

                    <div class="static_content col-xs-12">
                        <div class="main col-xs-12 col-sm-6 col-md-5">
							<h3 class="header">Presenting a Business Opportunity worth Pursuing!</h3>
							<p align="justify">Bueno’s, a name in Fresh, Healthy and nutritious Food is out on a look out for Business Partners who are as enthusiastic, as committed and as growth oriented as the brand itself.</p>
							<p align="justify">The journey started in the year 2013 from Cyber Hub Gurgaon and since then there is no look back in providing good quality food in servings. From breads to burgers to gourmet sandwiches to lasagna and pasta, everything is freshly baked and prepared. The drinks of coffees and iced teas tickle your taste buds as they are boon to coffee and tea lovers.</p>
							<p align="justify">Making of a brand requires a constant research on the subject matter and this is what which has gone into the world of Buenos kitchen to make it a sustainable and growth oriented brand. The promoters have infused their experience into the food and come up with a unique blend of growth oriented good quality food business.</p>
							<h3 class="header">Why Us</h3>
							<p align="justify">Driven by passion we can cite a list from here to the moon and back, but let us just focus on some facts and statistics.</p>
							<p align="justify"><strong>Global Cuisine:</strong> 7 cuisines to choose from, 60+ dishes across cuisines and an ever growing menu, with Bueno you are spoilt for gourmet choices.</p>
							<p align="justify"><strong>Visionary Management:</strong> Bueno’s kitchen is the result of strong vision of its Founder, Mr. Rohan Arora supported by Technical experts, experienced Chefs and Industry Experts.</p>
							<p align="justify"><strong>Clear Vision:</strong> Our Vision is to Ensure Everyone in across the Globe has access to Great Food and Quality of Life by creating the Most Preferred One-Stop Food Destination for Customers Adding Goodness Everywhere & Everyday across our stakeholders –Customers, Employees, Suppliers, Investors, Communities & Environment.</p>
							<p align="justify"><strong>Strong Business Model:</strong> At Bueno’s we strongly emphasize on creating a scalable business model for our partners and present a Low Investment High Return Franchise Opportunity</p>
                        </div> <!-- main ends -->
						@include('partials.franchise_sidebar')
                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->

@stop