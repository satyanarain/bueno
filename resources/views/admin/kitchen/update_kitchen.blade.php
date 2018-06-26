@extends('admin.master')

  @section('title') Bueno Kitchen @endsection

  @section('header')

  <!-- stylesheets -->
  @include('admin.partials.css')

  <!-- javascript -->
  @include('admin.partials.js')


  <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  
  @endsection

  @section('content')

  <div id="content">
      <div class="menubar">
        <div class="sidebar-toggler visible-xs">
          <i class="ion-navicon"></i>
        </div>

        <div class="page-title">
          Update Kitchen 
        </div>
      </div>

      <div class="content-wrapper">
      @include('admin.partials.flash')
      @include('admin.partials.errors')
        <form id="new-product" class="form-horizontal" method="post" role="form">
        {{csrf_field()}}
        {{ method_field('PATCH') }}
                    <input type="hidden" name="id" value="{{$kitchen->id}}"> 
                    <input type="hidden" name="kitchen_id" value="bueno-kitchen-{{$kitchen->id}}">
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Kitchen Name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="name" @if(old('name')!=null)}} value="{{old('name')}}" @else value="{{$kitchen->name . old('status')}} " @endif required=""/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Unit Number</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="unit_number" @if(old('unit_number')!=null)}} value="{{old('unit_number')}}" @else value="{{$kitchen->unit_number}} " @endif required=""/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Address Line1</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="address_line1" @if(old('address_line1')!=null)}} value="{{old('address_line1')}}" @else value="{{$kitchen->address_line1}} " @endif required=""/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Address Line2</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="address_line2" @if(old('address_line2')!=null)}} value="{{old('address_line2')}}" @else value="{{$kitchen->address_line2}} " @endif required=""/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Sub area</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="sub_area" @if(old('sub_area')!=null)}} value="{{old('sub_area')}}" @else value="{{$kitchen->sub_area}} " @endif required=""/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Latitude</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" id="latitude" name="latitude" @if(old('latitude')!=null)}} value="{{old('latitude')}}" @else value="{{$kitchen->latitude}} " @endif required=""/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Longitude</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" id="longitude" name="longitude" @if(old('longitude')!=null)}} value="{{old('longitude')}}" @else value="{{$kitchen->longitude}} " @endif required=""/>
              </div>
            </div>
            <div id="dc-edit-google-map">
                <div id="lat-long-selector-map" style="height:200px; border:1px solid #ddd; text-align:center">
                </div>
                <p></p>
            </div>
            <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Areas Covered</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="areas[]"  multiple="multiple" class="GroupGroup form-control">
                                <?php 
                                $area_counter = 0;
                                $kitchenArea[$area_counter]=-1;
                                foreach ($kitchen->areas as $area) {
                                  $kitchenArea[$area_counter] = $area->id;
                                  $area_counter++;
                                }?>
                                @foreach($areas as $area)
                                <option value="{{$area->id}}"  @if(old('areas')!=null) @if(in_array($area->id,old('areas'))) selected="" @endif @elseif(in_array($area->id,$kitchenArea))selected="" @endif>{{$area->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
            </div> 
            <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Managers of this Kitchen</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                            <?php 
                                $manager_counter = 0;
                                $kitchenManager[$manager_counter]=-1;
                                foreach ($kitchen->managers as $manager) {
                                  $kitchenManager[$manager_counter] = $manager->user_id;
                                  $manager_counter++;
                                }
                                ?>
                                <select name="managers[]" multiple="multiple" class="GroupGroup form-control">
                                @foreach($users as $user)
                                <option value="{{$user->id}}"   @if(old('managers')!=null) @if(in_array($user->id,old('managers'))) selected="" @endif @elseif(in_array($user->id,$assignedMangersIds))selected="" @endif>{{$user->full_name}}:{{$user->email}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                </div>

            
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Packaging Charge(INR)</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="packaging_charge"  required="" value="@if(old('packaging_charge')){{old('packaging_charge')}}@else{{$kitchen->packaging_charge}}@endif" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Delivery Charge (INR)</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="delivery_charge" value = "@if(old('delivery_charge')){{old('delivery_charge')}}@else{{$kitchen->delivery_charge}}@endif"  required=""/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">VAT (%)</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="vat"  required="" value="@if(old('vat')){{old('vat')}}@else{{$kitchen->vat}}@endif" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Service Tax (%)</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="service_tax"  required="" value="@if(old('service_tax')){{old('service_tax')}}@else{{$kitchen->service_tax}}@endif" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Service Charge (%)</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="service_charge"  required="" value="@if(old('service_charge')){{old('service_charge')}}@else{{$kitchen->service_charge}}@endif" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Pickup Time (Minute)</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="pickup_time"  required="" value="@if(old('pickup_time')){{old('pickup_time')}}@else{{$kitchen->pickup_time}}@endif" />
                </div>
            </div>
            <!--<div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Jooleh Username</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="jooleh_username"  required="" value="@if(old('jooleh_username')){{old('jooleh_username')}}@else{{$kitchen->jooleh_username}}@endif" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Jooleh Token</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="jooleh_token"  required="" value="@if(old('jooleh_token')){{old('jooleh_token')}}@else{{$kitchen->jooleh_token}}@endif" />
                </div>
            </div>-->
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Kitchen Status</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control"  name="status" >
                  <option value="1" @if(old('status')===1)  selected="" @elseif($kitchen->status===1) selected="" @endif>Active</option>
                  <option value="0" @if(old('status')===0)  selected="" @elseif($kitchen->status===0) selected="" @endif>Disabled</option>
                </select>
              </div>
            </div>
            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                <a href="{{URL::route('admin.kitchens')}}" class="btn btn-default">Back</a>
                  <button class="btn btn-success">Update Kitchen </button>
                <a href="#" data-toggle="modal" data-target="#confirm-modal" class="btn btn-danger">Delete</a>
              </div>
            </div>
        </form>
      </div>
    </div>

    <!-- Confirm Modal -->
  <div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="get" action="#" role="form">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">
                  Are you sure you want to delete this?
                </h4>
              </div>
              <div class="modal-body">
            Do you want to delete this Kitchen? All the info will be erased.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="{{URL::route('admin.delete_kitchen',$kitchen->id)}}"  class="btn btn-danger">Yes, delete it</a>
              </div>
          </form>
        </div>
      </div>
  </div>

  @endsection

  @section('script')
  <script src="http://maps.googleapis.com/maps/api/js"></script>
  <script type="text/javascript">
    $(function () {

      // Form validation
      $('#new-product').validate({
        rules: {
          "product[first_name]": {
            required: true
          },
          "product[email]": {
            required: true,
            email: true
          },
          "product[address]": {
            required: true
          },
          "product[notes]": {
            required: true
          }
        },
        highlight: function (element) {
          $(element).closest('.form-group').removeClass('success').addClass('error');
        },
        success: function (element) {
          element.addClass('valid').closest('.form-group').removeClass('error').addClass('success');
        }
      });

      // Product tags with select2
      $("#product-tags").select2({
        placeholder: 'Select tags or add new ones',
        tags:["shirt", "gloves", "socks", "sweater"],
        tokenSeparators: [",", " "]
      });

      // Bootstrap wysiwyg
      $("#summernote").summernote({
        height: 240,
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['picture', 'link', 'video']],
            ['view', ['fullscreen', 'codeview']],
            ['table', ['table']],
        ]
      });

      

    });
$('.GroupGroup').select2();
  

  if($('#dc-edit-google-map').length)
  {
      if($('#latitude').val() && $('#longitude').val()){
          initializeMap($('#latitude').val(), $('#longitude').val());
      }else{
          var address = $('#area').text() +',' + $('#city option:selected').text();
          var geocoder = new google.maps.Geocoder();
          geocoder.geocode( { 'address': address}, function(results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                  $('#latitude').val(results[0].geometry.location.lat());
                  $('#longitude').val(results[0].geometry.location.lng());
                  $('#lat-long-selector-map').parent('div').show();
                  initializeMap(results[0].geometry.location.lat(),results[0].geometry.location.lng());
              } else {
                  alert("Something got wrong " + status);
              }
          });
      }

  }

  function updateMarkerPosition(latLng) {
                $('#latitude').val(latLng.lat());
                $('#longitude').val(latLng.lng());
            }

            function initializeMap(initLat, initLong) {
                var latLng = new google.maps.LatLng(initLat, initLong);
                var map = new google.maps.Map(document.getElementById('lat-long-selector-map'), {
                    zoom: 13,
                    center: latLng,
                    zoomControl: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                var marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    draggable: true
                });

                // Update current position info.
                updateMarkerPosition(latLng);
                geocodePosition(latLng);


                google.maps.event.addListener(marker, 'drag', function() {
                    updateMarkerPosition(marker.getPosition());
                });

            }

            function geocodePosition(pos) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    latLng: pos
                }, function(responses) {
                    if (!responses) {
                        console.log('error');
                    }
                });
            }
  </script>

  @endsection