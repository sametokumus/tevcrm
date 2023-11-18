<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Company;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Measurement;
use App\Models\MobileDocument;
use App\Models\MobileDocumentType;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\OfferRequestProduct;
use App\Models\PackingList;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Sale;
use App\Models\SaleNote;
use App\Models\SaleOffer;
use App\Models\Status;
use App\Models\StatusHistory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Nette\Schema\ValidationException;

class MobileController extends Controller
{
    public function getOrder($sale_id)
    {
        try {
            $sale = Sale::query()
                ->where('active',1)
                ->where('id',$sale_id)
                ->first();

            $item = array();
            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->first();
            $company = Company::query()->where('id', $offer_request->company_id)->first();
            $status = Status::query()->where('id', $sale->status_id)->first();

            $item['company_id'] = $company->id;
            $item['company'] = $company->name;
            $item['completed'] = 0;
            if ($status->mobile_id == 41){
                $item['completed'] = 1;
            }
            $item['confirmation_no'] = 'SMY-'.$sale_id;
            $item['creation_date'] = Carbon::parse($sale->created_at)->format('d.m.Y h:i:s');
            $item['currency'] = $sale->currency;
            $item['order_date'] = Carbon::parse($sale->created_at)->format('Y-m-d');
            $item['order_id'] = $sale->id;
            $item['pieces'] = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->sum('offer_quantity');
            $item['po_no'] = $sale->id;
            $item['pr_no'] = $sale->id;
            $item['price_total'] = $sale->grand_total;
            $item['ref_no'] = $sale->id;
            $item['subject'] = "";
            $item['user_id'] = "";
            $item['customer_po_no'] = "";

            $order_items = array();
            $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
            $i = 1;
            foreach ($sale_offers as $sale_offer){
                $product = Product::query()->where('id', $sale_offer->product_id)->first();
                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                $measurement = Measurement::query()->where('id', $offer_product->measurement_id)->first();

                $order_item = array();
                $order_item['auto_date'] = Carbon::parse($sale_offer->created_at)->format('d.m.Y h:i:s');
                $order_item['delivery_day'] = $sale_offer->offer_lead_time;
                $order_item['description'] = $product->product_name;
                $order_item['order_number'] = $i;
                $order_item['progress'] = 0;
                $order_item['state'] = $status->mobile_id;
                $order_item['total_price'] = $sale_offer->sale_price;
                $order_item['unit'] = $sale_offer->offer_quantity;
                $order_item['unit_price'] = number_format(($sale_offer->sale_price / $sale_offer->offer_quantity), 2,".","");
                $order_item['unit_type'] = $measurement->name_tr;
                $order_item['product_id'] = $product;


                if ($product->category_id != null){
                    $category1 = Category::query()->where('id', $product->category_id)->first();
                    if ($category1->parent_id != 0){

                        $category2 = Category::query()->where('id', $category1->parent_id)->first();
                        if ($category2->parent_id != 0){

                            $category3 = Category::query()->where('id', $category2->parent_id)->first();
                            $order_item['item_name'] = $category3->name;

                        }else{
                            $order_item['item_name'] = $category2->name;
                        }

                    }else{
                        $order_item['item_name'] = $category1->name;
                    }

                }else{
                    $order_item['item_name'] = "";
                }



                array_push($order_items, $order_item);
                $i++;
            }

            $item['order_items'] = $order_items;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'pro' => $item]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getOrders(Request $request)
    {
        try {
            $data = array();
            $sales = array();
            $offers = array();

            foreach ($request->orders as $sale_id) {

                $sale = Sale::query()
                    ->where('active', 1)
                    ->where('id', $sale_id)
                    ->first();

                if ($sale) {

                    $packing_lists = PackingList::query()->where('sale_id', $sale_id)->where('active', 1)->get();

                    if ($packing_lists) {


                        foreach ($packing_lists as $packing_list) {


                            $item = array();
                            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->first();
                            $company = Company::query()->where('id', $offer_request->company_id)->first();
                            $status = Status::query()->where('id', $sale->status_id)->first();

                            $item['company_id'] = $company->id;
                            $item['company'] = $company->name;
                            $item['completed'] = 0;
                            if ($status->mobile_id == 41) {
                                $item['completed'] = 1;
                            }
                            $item['confirmation_no'] = 'SMY-' . $sale_id;
                            $item['creation_date'] = Carbon::parse($sale->created_at)->format('d.m.Y h:i:s');
                            $item['currency'] = $sale->currency;
                            $item['order_date'] = Carbon::parse($sale->created_at)->format('Y-m-d');
                            $item['order_id'] = $sale->id;
                            $item['pieces'] = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->sum('offer_quantity');
                            $item['po_no'] = $sale->id;
                            $item['pr_no'] = $sale->id;
                            $item['ref_no'] = $sale->id;
                            $item['subject'] = "";
                            $item['user_id'] = "";
                            $item['customer_po_no'] = "";
                            $item['success'] = true;


                            $order_items = array();

                            $sale_offers = SaleOffer::query()
                                ->leftJoin('packing_list_products', 'packing_list_products.sale_offer_id', '=', 'sale_offers.id')
                                ->leftJoin('sales', 'sales.sale_id', '=', 'sale_offers.sale_id')
                                ->selectRaw('sale_offers.*, packing_list_products.quantity as list_quantity')
                                ->where('sale_offers.sale_id', $sale_id)
                                ->where('sale_offers.active', 1)
                                ->where('packing_list_products.packing_list_id', $packing_list->packing_list_id)
                                ->get();

                            $i = 1;
                            $list_grand_total = 0;
                            foreach ($sale_offers as $sale_offer) {
                                $offer_pcs_price = $sale_offer->offer_price / $sale_offer->offer_quantity;
                                $list_offer_price = $offer_pcs_price * $sale_offer->list_quantity;
                                $list_grand_total += $list_offer_price;


                                $product = Product::query()->where('id', $sale_offer->product_id)->first();
                                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                                $measurement = Measurement::query()->where('id', $offer_product->measurement_id)->first();

                                $order_item = array();
                                $order_item['auto_date'] = Carbon::parse($sale_offer->created_at)->format('d.m.Y h:i:s');
                                $order_item['delivery_day'] = $sale_offer->offer_lead_time;
                                $order_item['description'] = $product->product_name;
                                $order_item['order_number'] = $i;
                                $order_item['progress'] = 0;
                                $order_item['state'] = $status->mobile_id;
                                $order_item['total_price'] = $list_offer_price;
                                $order_item['unit'] = $sale_offer->list_quantity;
                                $order_item['unit_price'] = number_format($offer_pcs_price, 2, ".", "");
                                $order_item['unit_type'] = $measurement->name_tr;

                                if ($product->category_id != null) {
                                    $category1 = Category::query()->where('id', $product->category_id)->first();
                                    if ($category1->parent_id != 0) {

                                        $category2 = Category::query()->where('id', $category1->parent_id)->first();
                                        if ($category2->parent_id != 0) {

                                            $category3 = Category::query()->where('id', $category2->parent_id)->first();
                                            $order_item['item_name'] = $category3->name;

                                        } else {
                                            $order_item['item_name'] = $category2->name;
                                        }

                                    } else {
                                        $order_item['item_name'] = $category1->name;
                                    }

                                } else {
                                    $order_item['item_name'] = "";
                                }

                                array_push($order_items, $order_item);
                                $i++;
                            }

                            $item['price_total'] = $list_grand_total;
                            $item['order_items'] = $order_items;


                        }


                        if ($status->period == 'approved' || $status->period == 'completed'){
                            array_push($data, $item);
                            array_push($sales, $item);
                        }elseif ($status->period == 'continue'){
                            $history = StatusHistory::query()->where('sale_id', $sale->sale_id)->where('status_id', 6)->where('active', 1)->orderByDesc('id')->first();
                            if ($history){
                                $document = Document::query()->where('sale_id', $sale->sale_id)->where('document_type_id', 1)->where('active', 1)->first();
                                if ($document){
                                    $item['offer_document'] = 'https://lenis-crm.wimco.com.tr/'.$document->file_url;
                                }else{
                                    $item['offer_document'] = '';
                                }
                                $quote = Quote::query()->where('sale_id', $sale->sale_id)->where('active', 1)->first();
                                if ($quote){
                                    $item['expiry_date'] = Carbon::parse($quote->expiry_date)->format('d-m-Y');
                                }else{
                                    $item['expiry_date'] = '';
                                }
                                array_push($offers, $item);
                            }
                        }







                    }else{

                        $item = array();
                        $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->first();
                        $company = Company::query()->where('id', $offer_request->company_id)->first();
                        $status = Status::query()->where('id', $sale->status_id)->first();

                        $item['company_id'] = $company->id;
                        $item['company'] = $company->name;
                        $item['completed'] = 0;
                        if ($status->mobile_id == 41) {
                            $item['completed'] = 1;
                        }
                        $item['confirmation_no'] = 'SMY-' . $sale_id;
                        $item['creation_date'] = Carbon::parse($sale->created_at)->format('d.m.Y h:i:s');
                        $item['currency'] = $sale->currency;
                        $item['order_date'] = Carbon::parse($sale->created_at)->format('Y-m-d');
                        $item['order_id'] = $sale->id;
                        $item['pieces'] = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->sum('offer_quantity');
                        $item['po_no'] = $sale->id;
                        $item['pr_no'] = $sale->id;
                        $item['price_total'] = $sale->grand_total;
                        $item['ref_no'] = $sale->id;
                        $item['subject'] = "";
                        $item['user_id'] = "";
                        $item['customer_po_no'] = "";
                        $item['success'] = true;

                        $order_items = array();


                        $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
                        $i = 1;
                        foreach ($sale_offers as $sale_offer) {
                            $product = Product::query()->where('id', $sale_offer->product_id)->first();
                            $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                            $measurement = Measurement::query()->where('id', $offer_product->measurement_id)->first();

                            $order_item = array();
                            $order_item['auto_date'] = Carbon::parse($sale_offer->created_at)->format('d.m.Y h:i:s');
                            $order_item['delivery_day'] = $sale_offer->offer_lead_time;
                            $order_item['description'] = $product->product_name;
                            $order_item['order_number'] = $i;
                            $order_item['progress'] = 0;
                            $order_item['state'] = $status->mobile_id;
                            $order_item['total_price'] = $sale_offer->sale_price;
                            $order_item['unit'] = $sale_offer->offer_quantity;
                            $order_item['unit_price'] = number_format(($sale_offer->sale_price / $sale_offer->offer_quantity), 2, ".", "");
                            $order_item['unit_type'] = $measurement->name_tr;

                            if ($product->category_id != null) {
                                $category1 = Category::query()->where('id', $product->category_id)->first();
                                if ($category1->parent_id != 0) {

                                    $category2 = Category::query()->where('id', $category1->parent_id)->first();
                                    if ($category2->parent_id != 0) {

                                        $category3 = Category::query()->where('id', $category2->parent_id)->first();
                                        $order_item['item_name'] = $category3->name;

                                    } else {
                                        $order_item['item_name'] = $category2->name;
                                    }

                                } else {
                                    $order_item['item_name'] = $category1->name;
                                }

                            } else {
                                $order_item['item_name'] = "";
                            }

                            array_push($order_items, $order_item);
                            $i++;
                        }

                        $item['order_items'] = $order_items;

                        if ($status->period == 'approved' || $status->period == 'completed'){
                            array_push($data, $item);
                            array_push($sales, $item);
                        }elseif ($status->period == 'continue'){
                            $history = StatusHistory::query()->where('sale_id', $sale->sale_id)->where('status_id', 6)->where('active', 1)->orderByDesc('id')->first();
                            if ($history){
                                $document = Document::query()->where('sale_id', $sale->sale_id)->where('document_type_id', 1)->where('active', 1)->first();
                                if ($document){
                                    $item['offer_document'] = 'https://lenis-crm.wimco.com.tr/'.$document->file_url;
                                }else{
                                    $item['offer_document'] = '';
                                }
                                $quote = Quote::query()->where('sale_id', $sale->sale_id)->where('active', 1)->first();
                                if ($quote){
                                    $item['expiry_date'] = Carbon::parse($quote->expiry_date)->format('d-m-Y');
                                }else{
                                    $item['expiry_date'] = '';
                                }
                                array_push($offers, $item);
                            }
                        }

                    }



                }else{


                    $item = array();
                    $item['po_no'] = $sale_id;
                    $item['success'] = false;
                    $item['err_message'] = 'Sipariş Bulunamadı.';

                    array_push($data, $item);

                }



            }




            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'pro' => $data, 'sales' => $sales, 'offers' => $offers]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }




    public function getDocuments($sale_id)
    {
        try {
            $documents = MobileDocument::query()
                ->leftJoin('mobile_document_types', 'mobile_document_types.id', '=', 'mobile_documents.document_type_id')
                ->selectRaw('mobile_documents.*, mobile_document_types.name as type_name')
                ->where('mobile_documents.sale_id', $sale_id)
                ->where('mobile_documents.active', 1)
                ->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['documents' => $documents]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getDocumentTypes()
    {
        try {
            $document_types = MobileDocumentType::query()
                ->where('active', 1)
                ->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['document_types' => $document_types]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getDocumentUrl($sale_id)
    {
        try {
            $datas = MobileDocument::query()->where('sale_id', $sale_id)->where('active', 1)->get();
            $documents = array();
            foreach ($datas as $data){
                $key = $data['document_type_id'];
                $value = 'https://lenis-crm.wimco.com.tr'.$data['file_url'];
                $documentArray = [$key => $value];
                array_push($documents, $documentArray);
            }

            return response(['message' => __('İşlem Başarılı.'), 'success' => 1, 'documents' => $documents]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function addDocument(Request $request, $sale_id)
    {
        try {
            $request->validate([
                'document_type_id' => 'required',
            ]);
            $document_id = MobileDocument::query()->insertGetId([
                'sale_id' => $sale_id,
                'document_type_id' => $request->document_type_id
            ]);
            if ($request->hasFile('file')) {
                $rand = uniqid();
                $file = $request->file('file');
                $file_name = $rand . "-" . $file->getClientOriginalName();
                $file->move(public_path('/img/document/'), $file_name);
                $file_path = "/img/document/" . $file_name;
                MobileDocument::query()->where('id',$document_id)->update([
                    'file_url' => $file_path
                ]);
            }

            return response(['message' => __('Döküman ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }
    public function deleteDocument($document_id){
        try {

            MobileDocument::query()->where('id',$document_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Döküman silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
