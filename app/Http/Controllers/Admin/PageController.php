<?php

namespace App\Http\Controllers\Admin;

use Flash;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Bueno\Validations\ValidationException;
use Bueno\Repositories\DbPageRepository as PageRepo;
use Bueno\Validations\CreatePageValidator as PageValidator;
use Nitsmax\Client\Configuration;
use Nitsmax\Client\ApiClient;
use Nitsmax\Client\Api\SaleApi;
use Nitsmax\Client\Api\CustomerApi;
use Firebase\JWT\JWT;
use Carbon\Carbon;

class PageController extends Controller
{
  protected $pageRepo,$pageValidator,$ristaApi;

    function __construct(PageRepo $pageRepo,PageValidator $pageValidator, Configuration $ristaConfig) 
    {
        $this->pageRepo = $pageRepo;
        $this->pageValidator = $pageValidator;
        $this->middleware('access.pages');
        $this->ristaConfig = $ristaConfig;
        
        $key = "ZqMdLUKvi14p4zhmMaJa7rE14ueb010UjcfQFtxTfSU";
        $payload = array(
                     "iss" => "2950dd16-e6d6-48c5-8080-388bc64a36e8",
                     "iat" => time(),
                     "jti" => "bueno-test-".time()
                   );
        $apiToken = JWT::encode($payload, $key);
        $this->ristaConfig->setApiKey('x-api-key', '2950dd16-e6d6-48c5-8080-388bc64a36e8');
        $this->ristaConfig->setApiKey('x-api-token', $apiToken);
        $this->ristaConfig->setAccessToken('ZqMdLUKvi14p4zhmMaJa7rE14ueb010UjcfQFtxTfSU');

        $ristaApiClient = new ApiClient($this->ristaConfig);
        $this->ristaSaleApi = new SaleApi($ristaApiClient);
        


$sale = array(
   'branchName' => 'Gurgaon',
   'branchCode' => 'bueno-gurgaon',
   'invoiceNumber' => '164',
   'invoiceDate' => '2016-12-19T16:02:26+05:30',
   'invoiceType' => 'Sale',
   'status' => 'Open',
   'saleBy' => 'Support',
   'channel' => 'Dine In',
   'currency' => 'INR',
   'itemCount' => 1,
   'items' => array(
    0 => array(
       'shortName' => 'Fusilli Alfredo Veg',
       'longName' => 'Fusilli Alfredo Veg',
       'skuCode' => 'BFAV',
       'quantity' => 1,
       'unitPrice' => 260,
       'measuringUnit' => 'ea',
       'itemAmount' => 260,
       'optionAmount' => 0,
       'discountAmount' => 0,
       'itemTotalAmount' => 260,
       'taxAmountIncluded' => 0,
       'taxAmountExcluded' => 34.125,
       'taxes' => array(
        0 => array(
           'name' => 'VAT',
           'percentage' => 13.125,
           'saleAmount' => 260,
           'amount' => 34.125,
           'amountIncluded' => 0,
           'amountExcluded' => 34.125,
        ),
      ),
    ),
  ),
   'itemTotalAmount' => 260,
   'discountAmount' => 0,
   'chargeAmount' => 0,
   'taxAmountIncluded' => 0,
   'taxAmountExcluded' => 34.130000000000003,
   'taxAmount' => 34.130000000000003,
   'billAmount' => 294.13,
   'roundOffAmount' => 0,
   'billRoundedAmount' => 294.13,
   'tipAmount' => 0,
   'taxes' => array(
    0 => array(
       'name' => 'VAT',
       'percentage' => 13.125,
       'saleAmount' => 260,
       'amount' => 34.130000000000003,
       'amountIncluded' => 0,
       'amountExcluded' => 34.130000000000003,
    ),
  ),
   'payments' => array(
    0 => array(
       'mode' => 'Cash',
       'amount' => 294.13,
       'postedDate' => '2016-12-19T16:02:26+05:30',
    ),
  ),
   'totalAmount' => 294.13,
);
    
        try {
            //$result = $this->ristaSaleApi->salePost($sale);
            $result = $this->ristaSaleApi->saleGet(10);
            dd($result);
        } catch (Exception $e) {
            echo 'Exception when calling CustomerApi->customerGet: ', $e->getMessage(), PHP_EOL;
        }
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $pages = $this->pageRepo->getAllPages();
        $page = 'datatables';
        return view('admin.seo.seo_titles',compact('page','pages'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = 'datatables';
        return view('admin.seo.new_seo_title',compact('page'));
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
          $this->pageValidator->fire($inputs);
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_seo_title'))->withErrors($e->getErrors())->withInput();
        }

        $web_page = $this->pageRepo->newPage($request);
        Flash::success('SEO settings successfully created');
        return redirect()->route('admin.seo_titles');
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
        $pages = $this->pageRepo->getAllPages();
        $web_page = $this->pageRepo->getPageById($id); 
        $page = 'datatables';
        return view('admin.seo.update_seo_title',compact('page','web_page','pages'));
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
        $this->pageRepo->updatePage($id,$request);
        return redirect()->route('admin.seo_titles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $web_page = $this->pageRepo->getPageById($id); 
        $web_page->delete();
        Flash::success('Seo Settings Successfully Deleted');
        return redirect()->route('admin.seo_titles');
    }

    public function ristaTest()
    {
        $xcv = $this->ristaSaleApi->salePost([]);
        dd($xcv);
    }
}
