<div class="hero_sidebar col-xs-12 col-xs-offset-0 col-sm-5 col-sm-offset-1 col-md-4 col-md-offset-2">
    <ul class="hero_features">
        <li>
            <h6 class="text-uppercase">Call / WhatsApp us on:</h6>
            <p class="margintop-xs">{{ config('bueno.site.catering_phone') }}</p>
        </li>
        <li>
            <h6 class="text-uppercase">Email us on:</h6>
            <p class="margintop-xs"><a href="mailto:{{ config('bueno.site.partner_email')}}">{{ config('bueno.site.partner_email')}}</a></p>
        </li>
		<li>
			<p class="margintop-xs"><a href="{{ route('pages.franchise.download.get') }}" class="btn btn-primary full_width">Download Franchise Info</a></p>
		</li>
    </ul> <!-- hero_features ends -->
	
	<h3 class="marginbottom-md paddingleft-sm"><em>Business Query</em></h3>
	<form action="{{ route('pages.franchise.post') }}" class="bueno_form" method="POST">
		{{ csrf_field() }}
		<div class="form-group bueno_form_group @if($errors->has('full_name')){{ 'has-error' }}@endif">
			@if ($errors->has('full_name'))<span class="help-block">{{ $errors->first('full_name') }} </span>@endif
			<input type="text" name="full_name" required class="bueno_inputtext black full_width" id="fName" placeholder="Full Name" value="{{ old('full_name') }}">
		</div> <!-- bueno_form_group ends -->
		<div class="form-group bueno_form_group @if($errors->has('email')){{ 'has-error' }}@endif">
			@if ($errors->has('email'))<span class="help-block">{{ $errors->first('email') }} </span>@endif
			<input type="email" name="email" required class="bueno_inputtext black full_width" id="email" placeholder="Email Address" value="{{ old('email') }}">
		</div> <!-- bueno_form_group ends -->
		<div class="form-group bueno_form_group @if($errors->has('phone')){{ 'has-error' }}@endif">
			@if ($errors->has('phone'))<span class="help-block">{{ $errors->first('phone') }} </span>@endif
			<input type="number" name="phone" required class="bueno_inputtext black full_width" id="mobileNo" placeholder="Mobile No." value="{{ old('phone') }}">
		</div> <!-- bueno_form_group ends -->
		<div class="form-group bueno_form_group @if($errors->has('subject')){{ 'has-error' }}@endif">
			@if ($errors->has('subject'))<span class="help-block">{{ $errors->first('subject') }} </span>@endif
			<input type="text" name="subject" requried class="bueno_inputtext black full_width" id="subject" placeholder="Subject" value="{{ old('subject') }}">
		</div> <!-- bueno_form_group ends -->
		<div class="form-group bueno_form_group @if($errors->has('query')){{ 'has-error' }}@endif">
			@if ($errors->has('query'))<span class="help-block">{{ $errors->first('query') }} </span>@endif
			<label for="message" class="label_inputtext">Message (upto 400 words)</label>
			<textarea class="bueno_inputtext black full_width" name="query" id="message" rows="6">{{ old('query') }}</textarea>
		</div> <!-- bueno_form_group ends -->
		<div class="form-group bueno_form_group">
			<input type="submit" class="btn btn-primary full_width">
		</div> <!-- bueno_form_group ends -->
	</form> <!-- bueno_form ends -->
	
</div> <!-- main ends -->