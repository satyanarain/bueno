@extends('layouts.master')

@section('content')

    <section class="paddingbottom-xlg">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    @include('partials.flash')

                    <div class="col-xs-12 col-md-5 account_sec signup_sec">

                        <section class="title_sec white-bg dashed_border col-xs-12 no-padding">
                            <div class="main-sec stick_lines col-xs-12">
                                <div class="col-xs-12">
                                    <h2 class="style_header_loud">Mobile No.</h2>
                                </div> <!-- left-sec ends -->
                            </div> <!-- col-xs-12 ends -->
                        </section> <!-- title_sec ends -->

                        <form action="{{ route('users.socialregister.post') }}" method="POST" class="bueno_form col-xs-12 margintop-md">
                            {{ csrf_field() }}
                            <input type="hidden" id="fName" name="first_name" value="@if(session('user.first_name')){{ session('user.first_name') }}@else{{ old('first_name') }}@endif">
                            <input type="hidden" id="lName" name="last_name" value="@if(session('user.last_name')){{ session('user.last_name') }}@else{{ old('last_name') }}@endif">
                            <input type="hidden" id="emailAddress" name="email" value="@if(session('user.email')){{ session('user.email') }}@else{{ old('email') }}@endif">
                            
                            <div class="form-group bueno_form_group @if ($errors->has('phone')){{ 'has-error' }}@endif">
                                @if ($errors->has('phone'))<span class="help-block">{{ $errors->first('phone') }} </span>@endif
                                <input type="number" class="form-control bueno_inputtext black" id="mobileNo" name="phone" placeholder="Mobile No." required value="{{ old('phone') }}">
                            </div> <!-- bueno_form_group ends -->
                            
                            <input type="submit" class="btn btn-xlg btn-primary full_width" value="Continue">
                            
                        </form> <!-- bueno_form ends -->

                    </div> <!-- signup_sec ends -->


                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- catering_query ends -->



@endsection