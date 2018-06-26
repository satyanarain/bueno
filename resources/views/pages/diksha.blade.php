@extends('layouts.master')

@section('content')

    @include('partials/static_links_nav_second')

    <section class="static_page_sec feedback_sec">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="well padding-md">
                        <h3 class="normal_header">Gift A Future - SUPPORT A CHILD AT DIKSHA</h3>
                    </div> <!-- well ends -->

                    <div class="static_content col-xs-12">
                        <div class="main col-xs-12 col-sm-6 col-md-5">
							<h3 class="header">About Diksha</h3>
							<p align="justify">Diksha is a non-profit school in Palam Vihar, Gurgaon, Haryana, India established to provide free and quality education to children from low-income families in the neighborhood.</p>
							<p align="justify">A core-group of volunteers along with salaried teachers and donors from the community have created a safe, stimulating learning environment that allows 300 children from disadvantaged families to develop a healthy body and mind. Children are nurtured with respect and taught to be responsible  citizens in today’s world. C.B.S.E based academic education is provided by salaried teachers who receive on-going professional development and are mentored by experienced volunteers. Children are encouraged to learn a variety of extra-curricular activities such as folk art, dance, drama, music and yoga and participate in inter-house and inter-NGO sports meets.</p>
							<p align="justify">Diksha supplies a nutritious mid-day meal (prepared on the premises daily) and a snack to each child in addition to uniforms, shoes and books. Periodic dental and medical check up is given to all students by volunteering organizations and doctors.</p>
							<p align="justify">Parents are an integral part of Diksha and participate in regular parent-teacher meetings and workshops on hygiene, nutrition and non-violence. Parents are encouraged to lend their expertise to support the school.</p>
							<p align="justify"><strong>bueno is committed to the community and is very closely associated with Diksha as a nutrition / fundraise partner since its own inception in 2012. We have been supporting their mid day meal program and also encourage customers for a Rs 10 Meal Support to the children at Diksha with each bueno meal served by us. We also regularly undertake awareness & fundraising activities for them.</strong></p>
                        </div> <!-- main ends -->
                        @include('partials.diksha_sidebar')
                    </div> <!-- static_content ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->

@stop