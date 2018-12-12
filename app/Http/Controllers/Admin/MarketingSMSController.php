<?php

namespace App\Http\Controllers\Admin;

use App\Models\SmsgupshupTemplate;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Validations\CreateSmsgupshupTemplateValidator as SmsgupshupTemplateValidator;

class MarketingSMSController extends Controller
{

  protected $smsgupshupTemplateValidator;

  function __construct(SmsgupshupTemplateValidator $smsgupshupTemplateValidator)
  {
    $this->smsgupshupTemplateValidator = $smsgupshupTemplateValidator;
    //$this->middleware('access.banner');
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $templates = SmsgupshupTemplate::get();
        $page = 'datatables';
        return view('admin.smsgupshup.templates',compact('page','templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = 'datatables';
        return view('admin.smsgupshup.new_template',compact('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      $inputs = $request->all();

      try
      {
        $this->smsgupshupTemplateValidator->fire($inputs);
      }
      catch(ValidationException $e)
      {
        return redirect(route('admin.new_marketing_sms_template'))->withErrors($e->getErrors())->withInput();
      }

        $template = SmsgupshupTemplate::create($request->all());


      return redirect()->route('admin.marketing_sms_template');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $template = SmsgupshupTemplate::find($id);
        $page = 'datatables';
        return view('admin.smsgupshup.update_template',compact('template','page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $template = SmsgupshupTemplate::find($id);

        $inputs = $request->all();

        try
        {
          $this->smsgupshupTemplateValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.update_marketing_sms_template',$id))->withErrors($e->getErrors())->withInput();
        }

        $template->fill($inputs)->save();

        return redirect()->route('admin.marketing_sms_template');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $header = Header::findorFail($id);
        $header->delete();
        return redirect()->route('admin.html_banners');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulkSms($id)
    {   
        $template = SmsgupshupTemplate::find($id);
        $page = 'datatables';
        return view('admin.smsgupshup.bulk_sms',compact('template','page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulkSmsSend(Request $request, $id)
    {   
        $inputs = $request->all();

        echo '<form action="http://enterprise.smsgupshup.com/GatewayAPI/rest?" method="post" name="gupshupForm">';
        echo '<input type="hidden" name="msg" value="" />';
        echo '<input type="hidden" name="msg_type" value="TEXT" />';
        echo '<input type="hidden" name="filetype" value="csv" />';
        echo '<input type="hidden" name="method" value="xlsUpload"/>';
        echo '<input type="hidden" name="v" value="1.1" />';
        echo '<input type="hidden" name="userid" value="2000154766" />';
        echo '<input type="hidden" name="password" value="ZOXLvSJnP"/>';
        echo '<input type="hidden" name="auth_scheme" value="PLAIN" />';
        echo '</form>';
        echo '<script>var gupshupForm = document.forms.gupshupForm;gupshupForm.submit();</script>';

        die("hello");

      try
      {
        $this->bannerValidator->fire($inputs);
      }
      catch(ValidationException $e)
      {
        return redirect(route('admin.new_html_banner'))->withErrors($e->getErrors())->withInput();
      }

        $header = Header::create($request->all());

        $file = $request->file('display_pic_url');

        if($file!=null) {
          $filename = upload_file($file, 'banners');

          $header->display_image_url = $filename;

          $header->display_image_mime = $file->getClientMimeType();

        }

      $header->save();

      return redirect()->route('admin.html_banners');
    }
}
