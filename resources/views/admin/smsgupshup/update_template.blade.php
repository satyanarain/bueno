
@extends('admin.master')

  @section('title')Bueno Kitchen @endsection

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
      @include('admin.partials.errors')
            <div class="menubar">
                <div class="sidebar-toggler visible-xs">
                    <i class="ion-navicon"></i>
                </div>

                <div class="page-title">
                    Update Template
                </div>
            </div>

            <div class="content-wrapper">
                <form id="new-customer" class="form-horizontal" method="post"  role="form">
                    {{ csrf_field() }}
                   
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Template Id</label>
                        <div class="col-sm-10 col-md-8">
                          <input class="form-control" name="template_id" type="text" value="{{$template->template_id}}" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Template Text</label>
                        <div class="col-sm-10 col-md-8">
                            <textarea class="form-control" rows="5" name="template_text" required="">{{$template->template_text}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="status" class="form-control" id="OrderLocality">
                                <option value="1" @if($template->status) selected="" @endif>Active</option>
                                <option value="0" @if(!$template->status) selected="" @endif>Disable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.marketing_sms_template')}}" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-success">Update Template</button>
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
            Do you want to delete this Template? All the info will be erased.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="{{URL::route('admin.delete_marketing_sms_template',$template->id)}}"  class="btn btn-danger">Yes, delete it</a>
              </div>
          </form>
        </div>
      </div>
  </div>


  @endsection

  @section('script')

  <script type="text/javascript">
        $(function () {

            // form validation
            $('#new-customer').validate({
                rules: {
                    "customer[first_name]": {
                        required: true
                    },
                    "customer[email]": {
                        required: true,
                        email: true
                    },
                    "customer[address]": {
                        required: true
                    },
                    "customer[notes]": {
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


           
        });
    </script>

  @endsection