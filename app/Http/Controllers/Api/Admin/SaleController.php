<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CancelNote;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Measurement;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\OfferRequestProduct;
use App\Models\OrderConfirmationDetail;
use App\Models\Product;
use App\Models\ProformaInvoiceDetails;
use App\Models\PurchasingOrderDetails;
use App\Models\Quote;
use App\Models\RfqDetails;
use App\Models\Sale;
use App\Models\SaleOffer;
use App\Models\Status;
use App\Models\StatusHistory;
use Faker\Provider\Uuid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class SaleController extends Controller
{

    public function getSales()
    {
        try {
            $sales = Sale::query()
                ->leftJoin('contacts', 'contacts.id', '=', 'sales.owner_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name, contacts.short_code as owner_short_code')
                ->where('sales.active',1)
                ->get();

            foreach ($sales as $sale) {
                $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
                $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
                $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
                $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
                $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
                $sale['request'] = $offer_request;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getActiveSales()
    {
        try {
            $sales = Sale::query()
                ->leftJoin('contacts', 'contacts.id', '=', 'sales.owner_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name, contacts.short_code as owner_short_code')
                ->where('sales.active',1)
                ->where('statuses.period','continue')
                ->get();

            foreach ($sales as $sale) {
                $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
                $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
                $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
                $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
                $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
                $sale['request'] = $offer_request;
                $sale['status'] = Status::query()->where('id', $sale->status_id)->first();
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getSaleById($sale_id)
    {
        try {
            $sale = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->first();

            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
            $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
            $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
            $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
            $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
            $sale['request'] = $offer_request;

            $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
            foreach ($sale_offers as $sale_offer){
                $sale_offer['supplier_name'] = Company::query()->where('id', $sale_offer->supplier_id)->first()->name;
                $sale_offer['product_name'] = Product::query()->where('id', $sale_offer->product_id)->first()->product_name;
                $sale_offer['product_ref_code'] = Product::query()->where('id', $sale_offer->product_id)->first()->ref_code;
                $offer_pcs_price = $sale_offer->offer_price / $sale_offer->offer_quantity;
                $sale_offer['offer_pcs_price'] = number_format($offer_pcs_price, 2,".","");
                $sale_offer['measurement_name'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name;
            }
            $sale['sale_offers'] = $sale_offers;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sale' => $sale]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addSale(Request $request)
    {
        try {
            $request->validate([
                'request_id' => 'required',
            ]);
            $sale_id = Sale::query()->where('request_id', $request->request_id)->first()->sale_id;
            Sale::query()->where('sale_id', $sale_id)->update([
                'status_id' => 4
            ]);

            StatusHistory::query()->insert([
                'sale_id' => $sale_id,
                'status_id' => 4,
                'user_id' => $request->user_id,
            ]);

            foreach ($request->offers as $offer){
                $measurement = Measurement::query()->where('name', $offer['measurement'])->where('active', 1)->first();
                SaleOffer::query()->insert([
                    'sale_id' => $sale_id,
                    'offer_id' => $offer['offer_id'],
                    'offer_product_id' => $offer['offer_product_id'],
                    'product_id' => $offer['product_id'],
                    'supplier_id' => $offer['supplier_id'],
                    'date_code' => $offer['date_code'],
                    'package_type' => $offer['package_type'],
                    'request_quantity' => $offer['request_quantity'],
                    'offer_quantity' => $offer['offer_quantity'],
                    'measurement_id' => $measurement->id,
                    'pcs_price' => $offer['pcs_price'],
                    'total_price' => $offer['total_price'],
                    'discount_rate' => $offer['discount_rate'],
                    'discounted_price' => $offer['discounted_price'],
                    'vat_rate' => $offer['vat_rate'],
                    'currency' => $offer['currency'],
                ]);
            }

            return response(['message' => __('Satış ekleme işlemi başarılı.'), 'status' => 'success', 'object' => ['sale_id' => $sale_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateSaleStatus(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'status_id' => 'required',
                'user_id' => 'required',
            ]);
            Sale::query()->where('sale_id', $request->sale_id)->update([
                'status_id' => $request->status_id,
            ]);

            StatusHistory::query()->insert([
                'sale_id' => $request->sale_id,
                'status_id' => $request->status_id,
                'user_id' => $request->user_id,
            ]);
            $status = Status::query()->where('id', $request->status_id)->first();

            return response(['message' => __('Durum güncelleme işlemi başarılı.'), 'status' => 'success', 'object' => ['period' => $status->period]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function addCancelSaleNote(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'user_id' => 'required',
                'note' => 'required',
            ]);

            CancelNote::query()->insert([
                'sale_id' => $request->sale_id,
                'user_id' => $request->user_id,
                'note' => $request->note,
            ]);

            return response(['message' => __('Not ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function getSaleOfferById($offer_product_id)
    {
        try {
            $sale_offer = SaleOffer::query()->where('offer_product_id',$offer_product_id)->first();
            $sale_offer['company'] = Company::query()->where('id', $sale_offer->supplier_id)->where('active', 1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sale_offer' => $sale_offer]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addSaleOfferPrice(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'user_id' => 'required',
                'offer_id' => 'required',
                'offer_product_id' => 'required',
                'price' => 'required',
                'currency' => 'required',
            ]);
            SaleOffer::query()->where('sale_id', $request->sale_id)->where('offer_id', $request->offer_id)->where('offer_product_id', $request->offer_product_id)->update([
                'offer_price' => $request->price,
                'offer_currency' => $request->currency
            ]);

            $offer_check = SaleOffer::query()->where('sale_id', $request->sale_id)->where('offer_price', null)->count();
            if ($offer_check == 0) {
                Sale::query()->where('sale_id', $request->sale_id)->update([
                    'status_id' => 5
                ]);

                StatusHistory::query()->insert([
                    'sale_id' => $request->sale_id,
                    'status_id' => 5,
                    'user_id' => $request->user_id,
                ]);

                $sale_offers = SaleOffer::query()->where('sale_id', $request->sale_id)->get();
                $sub_total = 0;
                $vat = 0;
                foreach ($sale_offers as $sale_offer){
                    $sub_total += $sale_offer->offer_price;
                    $vat += $sale_offer->offer_price / 100 * $sale_offer->vat_rate;
                }
                Sale::query()->where('sale_id', $request->sale_id)->update([
                    'sub_total' => $sub_total,
                    'vat' => $vat
                ]);
            }

            return response(['message' => __('Satış fiyatı ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateSaleOfferPrice(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'user_id' => 'required',
                'offer_id' => 'required',
                'offer_product_id' => 'required',
                'price' => 'required',
                'currency' => 'required',
            ]);
            SaleOffer::query()->where('sale_id', $request->sale_id)->where('offer_id', $request->offer_id)->where('offer_product_id', $request->offer_product_id)->update([
                'offer_price' => $request->price,
                'offer_currency' => $request->currency
            ]);

            return response(['message' => __('Satış fiyatı güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function getRfqDetailById($offer_id)
    {
        try {
            $rfq_detail = RfqDetails::query()->where('offer_id', $offer_id)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['rfq_detail' => $rfq_detail]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function updateRfqDetail(Request $request)
    {
        try {
            $request->validate([
                'offer_id' => 'required',
            ]);
            $count = RfqDetails::query()->where('offer_id', $request->offer_id)->count();
            if ($count == 0){
                RfqDetails::query()->insert([
                    'offer_id' => $request->offer_id,
                    'note' => $request->note,
                ]);
            }else {
                RfqDetails::query()->where('offer_id', $request->offer_id)->update([
                    'note' => $request->note,
                ]);
            }

            return response(['message' => __('Detay güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function getQuoteBySaleId($sale_id)
    {
        try {

            $quote_count = Quote::query()->where('sale_id', $sale_id)->count();
            if ($quote_count == 0){
                $quote_id = Uuid::uuid();
                Quote::query()->insert([
                    'quote_id' => $quote_id,
                    'sale_id' => $sale_id
                ]);
            }
            $quote = Quote::query()->where('sale_id', $sale_id)->first();
            $sale = Sale::query()->where('sale_id', $sale_id)->first();
            $quote['freight'] = $sale->freight;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['quote' => $quote]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function updateQuote(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'quote_id' => 'required',
            ]);
            Quote::query()->where('id', $request->quote_id)->update([
                'payment_term' => $request->payment_term,
                'lead_time' => $request->lead_time,
                'delivery_term' => $request->delivery_term,
                'country_of_destination' => $request->country_of_destination,
                'note' => $request->note
            ]);
            $sale = Sale::query()->where('sale_id', $request->sale_id)->first();
            $grand_total = null;
            if ($request->freight == ""){
                $freight = null;
                $grand_total = $sale->sub_total + $sale->vat;
            }else{
                $freight = $request->freight;
                $grand_total = $sale->sub_total + $sale->vat + $freight;
            }

            Sale::query()->where('sale_id', $request->sale_id)->update([
                'freight' => $freight,
                'grand_total' => $grand_total
            ]);

            return response(['message' => __('Bilgi güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function getPurchasingOrderDetailById($offer_id)
    {
        try {
            $purchasing_order_detail = PurchasingOrderDetails::query()->where('offer_id', $offer_id)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['purchasing_order_detail' => $purchasing_order_detail]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addPurchasingOrderDetail(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'offer_id' => 'required',
                'note' => 'required',
            ]);
            PurchasingOrderDetails::query()->insert([
                'sale_id' => $request->sale_id,
                'offer_id' => $request->offer_id,
                'note' => $request->note
            ]);

            return response(['message' => __('Not ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updatePurchasingOrderDetail(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'offer_id' => 'required',
                'note' => 'required',
            ]);
            PurchasingOrderDetails::query()->where('sale_id', $request->sale_id)->where('offer_id', $request->offer_id)->update([
                'note' => $request->note
            ]);

            return response(['message' => __('Not ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function getOrderConfirmationDetailById($sale_id)
    {
        try {
            $order_confirmation_detail = OrderConfirmationDetail::query()->where('sale_id', $sale_id)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['order_confirmation_detail' => $order_confirmation_detail]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addOrderConfirmationDetail(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'note' => 'required',
            ]);
            OrderConfirmationDetail::query()->insert([
                'sale_id' => $request->sale_id,
                'note' => $request->note
            ]);

            return response(['message' => __('Not ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateOrderConfirmationDetail(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'note' => 'required',
            ]);
            OrderConfirmationDetail::query()->where('sale_id', $request->sale_id)->update([
                'note' => $request->note
            ]);

            return response(['message' => __('Not ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function getProformaInvoiceDetailById($sale_id)
    {
        try {
            $proforma_invoice_detail = ProformaInvoiceDetails::query()->where('sale_id', $sale_id)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['proforma_invoice_detail' => $proforma_invoice_detail]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function updateProformaInvoiceDetail(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
            ]);
            $count = ProformaInvoiceDetails::query()->where('sale_id', $request->sale_id)->count();
            if ($count == 0){
                ProformaInvoiceDetails::query()->insert([
                    'sale_id' => $request->sale_id,
                    'note' => $request->note,
                    'payment_term' => $request->payment_term,
                ]);
            }else {
                ProformaInvoiceDetails::query()->where('sale_id', $request->sale_id)->update([
                    'note' => $request->note,
                    'payment_term' => $request->payment_term,
                ]);
            }

            return response(['message' => __('Detay güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateShippingPrice(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
            ]);
            $sale = Sale::query()->where('sale_id', $request->sale_id)->first();
            $grand_total_with_shipping = $sale->grand_total + $request->shipping_price;
            Sale::query()->where('sale_id', $request->sale_id)->update([
                'shipping_price' => $request->shipping_price,
                'grand_total_with_shipping' => $grand_total_with_shipping,
            ]);

            return response(['message' => __('Detay güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

}
