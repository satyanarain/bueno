<!-- ############################## -->
<!-- ########### Footer ########### -->
<!-- ############################## -->

</div> <!-- #pageWrap ends -->

<footer id="buenoFooter" class="bueno_footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">

                <div class="bueno_intro col-xs-12 col-sm-3">
                    <img class="img-responsive marginbottom-md" src="{{ asset('/assets/images/bueno_logo_white.png') }}" alt="Bueno">
                    <p class="text-gray">Good Food<br>Delivered to your doorstep<br>Open from {{$timing_from_display}} till {{$timing_to_display}}.</p>
                </div> <!-- bueno_intro ends -->
                <div class="bueno_links col-xs-12 col-sm-5">
                    <ul class="links_ul">
                        <li><a href="{{ route('pages.about.get') }}">About bueno</a></li>
                        <li><a href="{{ route('pages.press.get') }}">In the Press</a></li>
                        <li><a href="{{ route('pages.faq.get') }}">FAQs</a></li>
                        <li><a href="{{ route('pages.privacy_policy.get') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('pages.terms_conditions.get') }}">Terms &amp; Conditions</a></li>
                        <li><a href="{{ route('pages.refund_cancellation.get') }}">Refunds &amp; Cancellation</a></li>
                        <li><a href="{{ route('pages.contact.get') }}">Contact & Feedback</a></li>
                        <li><a href="{{ route('pages.diksha.get') }}">Support Diksha!</a></li>
                        <li><a href="{{ route('pages.business.get') }}">Query / Enquiry</a></li>
                        <!--<li><a href="{{ route('pages.catering.get') }}">Catering</a></li>-->
                        <li><a href="{{ route('pages.franchise.get') }}">Franchising</a></li>
                        <li><a href="{{ route('pages.corporate_orders.get') }}">Corporate / Bulk Orders</a></li>
                        <li><a href="{{ route('pages.careers.get') }}">Careers</a></li>
                    </ul> <!-- links_ul ends -->
                </div> <!-- bueno_links ends -->
                <div class="bueno_extras col-xs-12 col-sm-4">
                    <form action="http://kitchen.us12.list-manage.com/subscribe/post" method="POST">
                        <div class="form-group bueno_form_group">
                                <label for="subscribeEmail">Sign up to our newsletter</label>
                                <div class="input-group bueno_inputgroup full_width icon_right">
                                        <input type="hidden" name="u" value="aebb69fd20ae68e5046533534">
                                        <input type="hidden" name="id" value="ee85a08d7e">
                                        <input type="email" id="subscribeEmail" class="bueno_inputtext full_width" placeholder="Email Address" required autocapitalize="off" autocorrect="off" name="MERGE0" id="MERGE0" size="25" value="">
                                  <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit"><i class="ion-android-send text-gray"></i></button>
                                  </span>
                                </div>
                        </div> <!-- bueno_form_group ends -->
                    </form> <!-- bueno_form ends -->
                    <div class="form-group bueno_form_group">
                        <label for="footerCall">Call us on</label>
                        <a id="footerCall" href="tel:+{{ config('bueno.site.footer_phone') }}">{{ config('bueno.site.footer_phone') }}</a>
                    </div> <!-- bueno_form_group ends -->
                    <div class="help-block text-center">
                        <small class="text-muted">{{ config('bueno.site.copyright_text') }} {{ date("Y") }}</small>
                    </div> <!-- help-block ends -->
                </div> <!-- bueno_extras ends -->

            </div> <!-- col-xs-12 ends -->


        </div> <!-- row ends -->
    </div> <!-- container ends -->
</footer>

<input type="hidden" id="session_area_id" name="session_area_id" value="@if(in_array(session('area_id'), $areas->pluck('id')->toArray())){{ session('area_id') }}@endif"/>
<input type="hidden" id="master_switch" name="master_switch" value="{{ $master_switch->value }}"/>
<input type="hidden" id="is_open" name="is_open" value="{{ $is_open }}"/>


@if( request()->path() != 'opt-out-sms' )
@include('partials.intercom')
@include('modals/locality_modal')
@include('modals/master_switch_modal')
@include('modals/android_detect_modal')
@include('modals/ios_detect_modal')
@endif

@include('partials/js_includes')
