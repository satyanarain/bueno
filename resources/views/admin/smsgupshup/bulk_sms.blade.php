
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
                    Send Bulk SMS 
                </div>
            </div>

            <div class="content-wrapper">
                <form id="gupshupForm" target="_blank" action="http://enterprise.smsgupshup.com/GatewayAPI/rest?" class="form-horizontal" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                    
                  <input type="hidden" name="msg" value="{{urlencode($template->template_text)}}" />
                  <input type="hidden" name="msg_type" value="TEXT" />
                  <input type="hidden" name="filetype" value="csv" />
                  <input type="hidden" name="method" value="xlsUpload"/>
                  <input type="hidden" name="v" value="1.1" />
                  <input type="hidden" name="userid" value="2000154766" />
                  <input type="hidden" name="password" value="ZOXLvSJnP"/>
                  <input type="hidden" name="auth_scheme" value="PLAIN" />
        
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Template Text</label>
                        <div class="col-sm-10 col-md-8">
                            <textarea readonly="readonly" class="form-control" rows="5" name="template_text" required="">{{$template->template_text}}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Upload File</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="well">
                              <div class="control-group">
                                    <label for="post_featured_image">
                                        Choose a file:
                                    </label>
                                    <input id="xlsFile" name="xlsFile" type="file" required="">
                                </div>
                            </div>
                    
                        </div>
                    </div>
                    
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.marketing_sms_template')}}" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-success">Send SMS</button>
                            
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

            /*$('#gupshupForm').submit(function(e){
             e.preventDefault();
             form = $(this);
             $.ajax({
                 url : form.attr('action'),
                 data : form.serialize(),
                 type : 'POST'
             }).done(function(response){
                 cancel_select = $('#order-cancel-reason select');
                 cancel_select.append($('<option>', {
                     value: response.id,
                     text: response.reason
                 }));
                 cancel_select.val(response.id);
                 $('#cancelReasonModelAdd').modal('hide');
             });
         });*/
           
        });
    </script>

  @endsection