
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

  <!-- Content -->
    <div id="content">
      <div class="menubar">
                <div class="sidebar-toggler visible-xs">
                    <i class="ion-navicon"></i>
                </div>
                
        <div class="page-title">
          List of Templates
        </div>&nbsp;&nbsp;

        <a href="{{URL::route('admin.new_marketing_sms_template')}}" class="new-user btn btn-primary btn-xs">
          Add New</a>
      </div>

      <div class="content-wrapper">
        
        <table id="datatable-example">
                    <thead>
                        <tr>
                            <th tabindex="0" rowspan="1" colspan="1">Sno
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Template Id
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Template text
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Send SMS
                            </th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th rowspan="1" colspan="1">Sno</th>
                            <th rowspan="1" colspan="1">Template Id</th>
                            <th rowspan="1" colspan="1">Template text</th>
                            <th rowspan="1" colspan="1">Send SMS</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($templates as $template)
                        <tr>
                            <td><a href="{{URL::route('admin.update_marketing_sms_template',$template->id)}}">{{$template->template_id}}</a></td>
                            <td>{{$template->template_id}}</td>
                            <td>{{$template->template_text}}</td>
                            <td><a href="{{URL::route('admin.bulk_marketing_sms',$template->id)}}">Send SMS</a></td>
                        </tr>  
                    @endforeach                                              
                    </tbody>
                </table>

      </div>

  @endsection

  @section('script')

   
  <script type="text/javascript">
        $(function() {
            $('#datatable-example').dataTable({
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10,
          "aLengthMenu": [[10, 50, 100, -1], [10, 50, 100, "All"]]
            });
        });
    </script>

  @endsection