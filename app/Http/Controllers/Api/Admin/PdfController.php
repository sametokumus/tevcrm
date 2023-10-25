<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Measurement;
use App\Models\MobileDocument;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\OfferRequestProduct;
use App\Models\OrderConfirmationDetail;
use App\Models\OwnerBankInfo;
use App\Models\PackingList;
use App\Models\PackingListProduct;
use App\Models\Product;
use App\Models\ProformaInvoiceDetails;
use App\Models\PurchasingOrderDetails;
use App\Models\Quote;
use App\Models\RfqDetails;
use App\Models\Sale;
use App\Models\SaleNote;
use App\Models\SaleOffer;
use App\Models\SaleTransaction;
use App\Models\SaleTransactionPayment;
use Faker\Provider\Uuid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use FPDF;
use setasign\Fpdi\Fpdi;
use Carbon\Carbon;


class PdfController extends Controller
{
    private function clearText($text){

    }
    private function htmlTextConvertArray($text){

    }
    private function textConvert($text){
        $inputString = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $inputString = mb_convert_encoding($inputString, 'UTF-8', 'auto');
//        $inputString = preg_replace('/[^\x20-\x7E]/u', '', $inputString);
        return iconv('utf-8', 'iso-8859-9', $inputString);
    }
    private function addOwnerLogo($pdf, $contact, $pageWidth){
        $x = $pageWidth - $contact->logo_width - 10;
        $pdf->Image(public_path($contact->logo), $x, 10, $contact->logo_width);

        list($imageWidth, $imageHeight) = getimagesize(public_path($contact->logo));
        return (int)($contact->logo_width * $imageHeight / $imageWidth);
    }
    private function addDateAndCode($pdf, $document_date, $contact, $actual_height, $sale_id, $pageWidth, $pdf_key){
        $pdf->SetFont('ChakraPetch-Bold', '', 10);
        $x = $pageWidth - $pdf->GetStringWidth(__('Date').': '.$document_date) - 10;
        $pdf->SetXY($x, $actual_height + 25);
        $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Date').': '), '0', '0', '');

        $pdf->SetFont('ChakraPetch-Regular', '', 10);
        $x = $pageWidth - $pdf->GetStringWidth($document_date) - 10;
        $pdf->SetXY($x, $actual_height + 25);
        $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $document_date), '0', '0', '');

        $pdf->SetFont('ChakraPetch-Bold', '', 11);
        $x = $pageWidth - $pdf->GetStringWidth($contact->short_code.'-'.$pdf_key.'-'.$sale_id) - 10;
        $pdf->SetXY($x, $actual_height + 32);
        $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->short_code.'-'.$pdf_key.'-'.$sale_id), '0', '0', '');
    }
    private function addOwnerInfo($pdf, $contact){
        $x = 10;
        $y = 15;

        $pdf->SetFont('ChakraPetch-Bold', '', 12);
        $pdf->SetXY($x, $y);
        $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->name), '0', '0', '');

        $pdf->SetFont('ChakraPetch-Regular', '', 10);

        if ($contact->registration_no != '') {
            $y += 5;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Registration No').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Registration No').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->registration_no, '0', '0', '');

            if ($contact->registration_office != '' && App::getLocale() != 'en') {

                $x = $x+5 + $pdf->GetStringWidth($contact->registration_no);

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, __('Registration Office').': ', '0', '0', '');

                $x = $x+2 + $pdf->GetStringWidth(__('Registration Office').': ');
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, $contact->registration_office, '0', '0', '');

            }
        }

        $pdf->SetFont('ChakraPetch-Bold', '', 10);
        $x = 10;
        $y += 5;
        $pdf->SetXY($x, $y);
        $pdf->Cell(0, 0, __('Address').': ', '0', '0', '');

        $pdf->SetFont('ChakraPetch-Regular', '', 10);
        $lines = explode('<br>', $contact->address);
        foreach ($lines as $line) {
            $y += 5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $line), '0', '0', '');
        }

        $y += 5;

        $pdf->SetFont('ChakraPetch-Bold', '', 10);
        $pdf->SetXY($x, $y);
        $pdf->Cell(0, 0, __('Phone').': ', '0', '0', '');

        $pdf->SetFont('ChakraPetch-Regular', '', 10);
        $x = $x+2 + $pdf->GetStringWidth(__('Phone').': ');
        $pdf->SetXY($x, $y);
        $pdf->Cell(0, 0, $contact->phone, '0', '0', '');

        $y += 5;
        $x = 10;

        $pdf->SetFont('ChakraPetch-Bold', '', 10);
        $pdf->SetXY($x, $y);
        $pdf->Cell(0, 0, __('Email').': ', '0', '0', '');

        $pdf->SetFont('ChakraPetch-Regular', '', 10);
        $x = $x+2 + $pdf->GetStringWidth(__('Email').': ');
        $pdf->SetXY($x, $y);
        $pdf->Cell(0, 0, $contact->email, '0', '0', '');

        return $y;
    }
    private function addPdfTitle($pdf, $title, $y){
        $y += 10;
        $x = 10;

        $pdf->SetFont('ChakraPetch-Bold', '', 20);
        $pdf->SetXY($x, $y);
        $pdf->Cell(0, 0, $title, '0', '0', '');

        return $y;
    }
    private function addCompanyInfo($pdf, $lang, $company, $y){
        $y += 10;
        $x = 10;

        $pdf->SetFont('ChakraPetch-Bold', '', 10);
        $pdf->SetXY($x, $y);
        $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Customer').': '), '0', '0', '');

        $pdf->SetFont('ChakraPetch-Regular', '', 10);
        if ($lang == 'tr') {
            $x = $x - 3 + $pdf->GetStringWidth(__('Customer') . ': ');
        }else{
            $x = $x+2 + $pdf->GetStringWidth(__('Customer') . ': ');
        }
        $pdf->SetXY($x, $y);
        $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->name), '0', '0', '');

        $y += 5;
        $x = 10;

        $pdf->SetFont('ChakraPetch-Bold', '', 10);
        $pdf->SetXY($x, $y);
        $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Address').': '), '0', '0', '');

        $pdf->SetFont('ChakraPetch-Regular', '', 10);

        $y += 2;
        $x = 10;
        $pdf->SetXY($x, $y);

        $address = $this->textConvert($company->address);
        $address_width = $pdf->GetStringWidth($address);
        $lines_needed = ceil($address_width / 100);
        $line_height = 5;
        $row_height = $lines_needed * $line_height;
        $pdf->MultiCell(100, $line_height, $address, 0, 'L');

        return $y + $row_height;
    }
    private function leadtime($lt){
        if ($lt != '' && $lt != null){
            if ($lt == 1) {
                $lead_time = __('Stock');
            } elseif (intval($lt) % 7 == 0) {
                $lead_time = (intval($lt) / 7) . ' ' . __('Week');
            } else {
                $lead_time = $lt . ' ' . __('Day');
            }
        }else{
            $lead_time = '';
        }
        return $lead_time;
    }

    public function getGeneratePDF($owner_id, $sale_id)
    {
        try {

            $sale = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->first();

            $currency = $sale->currency;

            $this_document = Document::query()->where('sale_id', $sale->id)->first();
            if ($this_document){
                $createdAt = Carbon::parse($this_document->created_at);
                $document_date = $createdAt->format('d/m/Y');
                $document_id = $this_document->id;
            }else{
                $createdAt = Carbon::now();
                $document_date = $createdAt->format('d/m/Y');
                $document_id = Document::query()->insertGetId([
                    'sale_id' => $sale->id,
                    'document_type_id' => 1,
                    'created_at' => $createdAt->format('Y-m-d H:i:s')
                ]);
            }

            $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale_id)->get();

            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
            $product_count = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
            $authorized_personnel = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
            $company = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
            $company_employee = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();

            $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
            foreach ($sale_offers as $sale_offer){
                $sale_offer['supplier_name'] = Company::query()->where('id', $sale_offer->supplier_id)->first()->name;
                $sale_offer['product_name'] = Product::query()->where('id', $sale_offer->product_id)->first()->product_name;
                $sale_offer['product_ref_code'] = Product::query()->where('id', $sale_offer->product_id)->first()->ref_code;
                $offer_pcs_price = $sale_offer->offer_price / $sale_offer->offer_quantity;
                $sale_offer['offer_pcs_price'] = number_format($offer_pcs_price, 2,",",".");
                $sale_offer->offer_price = number_format($sale_offer->offer_price, 2,",",".");
                $sale_offer->pcs_price = number_format($sale_offer->pcs_price, 2,",",".");
                $sale_offer->total_price = number_format($sale_offer->total_price, 2,",",".");
                $sale_offer->discounted_price = number_format($sale_offer->discounted_price, 2,",",".");
                $sale_offer['measurement_name_tr'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_tr;
                $sale_offer['measurement_name_en'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_en;

                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                $request_product = OfferRequestProduct::query()->where('id', $offer_product->request_product_id)->first();
                $sale_offer['sequence'] = $request_product->sequence;

            }
            $contact = Contact::query()->where('id', $owner_id)->first();

            $quote_count = Quote::query()->where('sale_id', $sale_id)->count();
            if ($quote_count == 0){
                $quote_id = Uuid::uuid();
                Quote::query()->insert([
                    'quote_id' => $quote_id,
                    'sale_id' => $sale_id
                ]);
            }
            $quote = Quote::query()->where('sale_id', $sale_id)->first();


            // Create a new PDF instance
            $pdf = new \FPDF();
            $pdf->AddPage();

            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(true, 40);

            $pdf->AddFont('ChakraPetch-Regular', '', 'ChakraPetch-Regular.php');
            $pdf->AddFont('ChakraPetch-Bold', '', 'ChakraPetch-Bold.php');
            $pdf->SetFont('ChakraPetch-Bold', '', 12);


            // LOGO
            $pageWidth = $pdf->GetPageWidth();
            $x = $pageWidth - $contact->logo_width - 20;
            $pdf->Image(public_path($contact->logo), $x, 15, $contact->logo_width);  // Parameters: image file, x position, y position, width

            list($imageWidth, $imageHeight) = getimagesize(public_path($contact->logo));
            $actual_height = (int) ($contact->logo_width * $imageHeight / $imageWidth);

            //TARİH - KOD

            $x = $pageWidth - $pdf->GetStringWidth(__('Date').': '.$document_date) - 13;
            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $actual_height + 25);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Date').': '.$document_date), '0', '0', '');

            $x = $pageWidth - $pdf->GetStringWidth($contact->short_code.'-OFR-'.$sale->id) - 16;
            $pdf->SetFont('ChakraPetch-Bold', '', 11);
            $pdf->SetXY($x, $actual_height + 32);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->short_code.'-OFR-'.$sale->id), '0', '0', '');


            //COMPANY INFO

            $x = 10;
            $y = 15;

            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->name, '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);

            if ($contact->registration_no != '') {
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, __('Registration No').': ', '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Registration No').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, $contact->registration_no, '0', '0', '');

                if ($contact->registration_office != '' && App::getLocale() != 'en') {

                    $x = $x+5 + $pdf->GetStringWidth($contact->registration_no);

                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, __('Registration Office').': ', '0', '0', '');

                    $x = $x+2 + $pdf->GetStringWidth(__('Registration Office').': ');
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, $contact->registration_office, '0', '0', '');

                }
            }

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = 10;
            $y += 5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Address'), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $lines = explode('<br>', $contact->address);
            foreach ($lines as $line) {
                $y += 5;
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $line), '0', '0', '');
            }

            $y += 5;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Phone').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->phone, '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Email').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->email, '0', '0', '');

            //TITLE

            $y += 10;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 20);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Offer'), '0', '0', '');

            //CUSTOMER INFO

            $y += 10;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Customer').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Customer').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->name), '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Address').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Address').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->address), '0', '0', '');


            //QUOTES

            $y += 5;

            if ($company->company_request_code != ''){
                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Request Code').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Request Code').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->company_request_code), '0', '0', '');
            }

            if ($quote->payment_term != null) {

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Payment Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Payment Terms').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->payment_term), '0', '0', '');

            }else if ($company->payment_term != null){

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Payment Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Payment Terms').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->payment_term), '0', '0', '');

            }

            if ($quote->delivery_term != null) {

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Delivery Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Delivery Terms').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->delivery_term), '0', '0', '');

            }

            //Insurance olarak kullanılıyor
            if ($quote->lead_time != null) {

                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Insurance').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Insurance').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->lead_time), '0', '0', '');

            }

            if ($quote->country_of_destination != null) {

                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Country of Destination').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Country of Destination').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->country_of_destination), '0', '0', '');

            }

            $x = 10;
            $y += 10;
            $pdf->SetXY($x, $y);



// Set table header
            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->Cell(10, 14, 'N#', 1, 0, 'C');
            $pdf->Cell(20, 14, iconv('utf-8', 'iso-8859-9', __('Ref. Code')), 1, 0, 'C');
            $pdf->Cell(50, 14, iconv('utf-8', 'iso-8859-9', __('Product Name')), 1, 0, 'C');
            $pdf->Cell(19, 14, iconv('utf-8', 'iso-8859-9', __('Qty')), 1, 0, 'C');
            $pdf->Cell(16, 14, iconv('utf-8', 'iso-8859-9', __('Unit')), 1, 0, 'C');
            $pdf->Cell(25, 14, iconv('utf-8', 'iso-8859-9', __('Unit Price')), 1, 0, 'C');
            $pdf->Cell(30, 14, iconv('utf-8', 'iso-8859-9', __('Total Price')), 1, 0, 'C');
//            $pdf->Cell(20, 10, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 1, 1, 'C');
            $pdf->MultiCell(20, 7, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 1, 'C');  // Move to the next line


// Set table content
            $pdf->SetFont('ChakraPetch-Regular', '', 9);
            foreach ($sale_offers as $sale_offer) {
                if (App::getLocale() == 'tr'){
                    $measurement_name = $sale_offer->measurement_name_tr;
                }else{
                    $measurement_name = $sale_offer->measurement_name_en;
                }

                if ($sale_offer->offer_lead_time != '' && $sale_offer->offer_lead_time != null){
                    if ($sale_offer->offer_lead_time == 1) {
                        $lead_time = __('Stock');
                    } elseif (intval($sale_offer->offer_lead_time) % 7 == 0) {
                        $lead_time = (intval($sale_offer->offer_lead_time) / 7) . ' ' . __('Week');
                    } else {
                        $lead_time = $sale_offer->offer_lead_time . ' ' . __('Day');
                    }
                }else{
                    $lead_time = '';
                }

                $row_height = 14;
                $name_width = $pdf->GetStringWidth($sale_offer->product_name);
                if ($name_width > 50){
                    $row_height = 14 / (((int) ($name_width / 50)) + 1);
                }

                $pdf->setX(10);
                $pdf->Cell(10, 14, $sale_offer->sequence, 1, 0, 'C');
//                $pdf->Cell(10, 14, '', 1, 0, 'C');
                $pdf->Cell(20, 14, iconv('utf-8', 'iso-8859-9', $sale_offer->product_ref_code), 1, 0, 'C');
//                $pdf->Cell(20, 14, iconv('utf-8', 'iso-8859-9', $row_height.' - '.$name_width), 1, 0, 'C');

                // Save the current X and Y position
                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();


                // Use MultiCell for product name with a width of 50mm
                $pdf->MultiCell(50, $row_height, iconv('utf-8', 'iso-8859-9', $sale_offer->product_name), 1, 'L');

                // Reset X and move Y to the saved position (next line)
                $pdf->SetXY($xPos+50, $yPos);

                // Output remaining cells for the current row
                $pdf->Cell(19, 14, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_quantity), 1, 0, 'C');
                $pdf->Cell(16, 14, iconv('utf-8', 'iso-8859-9', $measurement_name), 1, 0, 'C');
                $pdf->Cell(25, 14, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_pcs_price.' '.$currency), 1, 0, 'C');
                $pdf->Cell(30, 14, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_price.' '.$currency), 1, 0, 'C');
                $pdf->Cell(20, 14, iconv('utf-8', 'iso-8859-9', $lead_time), 1, 1, 'C');  // Move to the next line
            }

            //TOTAL PRICES

            $x = 10;
            $y = $pdf->GetY();

            if ($sale->sub_total != null) {
                $title = __('Sub Total');
                if ($sale->vat == null || $sale->vat == '0.00' && $sale->freight == null) {
                    $title = __('Grand Total');
                }

                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper($title)), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->sub_total, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->freight != null) {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Freight'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->freight, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->vat != null && $sale->vat != '0.00') {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Vat'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->vat, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->grand_total != null) {
                if ($sale->vat != null && $sale->vat != '0.00' && $sale->freight != null) {
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Grand Total'))), 1, 0, 'R');

                    $pdf->SetXY($x + 140, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->grand_total, 2,",",".") . ' ' . $currency), 1, 0, 'C');

                    $y += 10;
                }
            }


            //NOTE
            if ($quote->note != null) {
                $y += 10;
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 8);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Note')), 0, 0, '');

                $y += 5;
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 8);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->note), 0, 0, '');
            }



            //FOOTER

            $pdfContent = $pdf->Output('created.pdf', 'S');

            $pdf = new Fpdi();
            $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            $numPages = $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            for ($pageNo = 1; $pageNo <= $numPages; $pageNo++) {
                $pdf->AddPage();

                $pdf->Image(public_path($contact->footer), 10, 260, 190);

                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx, 0, 0, null, null, true);
            }

            $filePath = public_path('img/document/' . $contact->short_code . '-OFR-' . $sale->id . '.pdf');
            $pdf->Output($filePath, 'F');

            $fileUrl = 'img/document/' . $contact->short_code . '-OFR-' . $sale->id . '.pdf';
            $fileName = $contact->short_code . '-OFR-' . $sale->id . '.pdf';

            Document::query()->where('id', $document_id)->update([
                'file_url' => $fileUrl
            ]);

            return response([
                'message' => __('İşlem Başarılı.'),
                'status' => 'success',
                'object' => [
                    'file_url' => $fileUrl,
                    'file_name' => $fileName
                ]
            ]);


        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getGenerateQuatotionPDF($lang, $owner_id, $sale_id)
    {
        try {
            App::setLocale($lang);

            $sale = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->first();

            $currency = $sale->currency;

            $this_document = Document::query()->where('sale_id', $sale_id)->where('document_type_id', 1)->first();
            if ($this_document){
                $createdAt = Carbon::parse($this_document->created_at);
                $document_date = $createdAt->format('d/m/Y');
                $document_id = $this_document->id;
            }else{
                $createdAt = Carbon::now();
                $document_date = $createdAt->format('d/m/Y');
                $document_id = Document::query()->insertGetId([
                    'sale_id' => $sale_id,
                    'document_type_id' => 1,
                    'created_at' => $createdAt->format('Y-m-d H:i:s')
                ]);
            }

            $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale_id)->get();

            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
            $company = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();

            $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
            foreach ($sale_offers as $sale_offer){
                $sale_offer['supplier_name'] = Company::query()->where('id', $sale_offer->supplier_id)->first()->name;
                $sale_offer['product_name'] = Product::query()->where('id', $sale_offer->product_id)->first()->product_name;
                $sale_offer['product_ref_code'] = Product::query()->where('id', $sale_offer->product_id)->first()->ref_code;
                $offer_pcs_price = $sale_offer->offer_price / $sale_offer->offer_quantity;
                $sale_offer['offer_pcs_price'] = number_format($offer_pcs_price, 2,",",".");
                $sale_offer->offer_price = number_format($sale_offer->offer_price, 2,",",".");
                $sale_offer->pcs_price = number_format($sale_offer->pcs_price, 2,",",".");
                $sale_offer->total_price = number_format($sale_offer->total_price, 2,",",".");
                $sale_offer->discounted_price = number_format($sale_offer->discounted_price, 2,",",".");
                $sale_offer['measurement_name_tr'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_tr;
                $sale_offer['measurement_name_en'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_en;

                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                $request_product = OfferRequestProduct::query()->where('id', $offer_product->request_product_id)->first();
                $sale_offer['sequence'] = $request_product->sequence;

            }
            $contact = Contact::query()->where('id', $owner_id)->first();

            $quote_count = Quote::query()->where('sale_id', $sale_id)->count();
            if ($quote_count == 0){
                $quote_id = Uuid::uuid();
                Quote::query()->insert([
                    'quote_id' => $quote_id,
                    'sale_id' => $sale_id
                ]);
            }
            $quote = Quote::query()->where('sale_id', $sale_id)->first();


            // Create a new PDF instance
            $pdf = new \FPDF();
            $pdf->AddPage();

            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(true, 40);

            $pdf->AddFont('ChakraPetch-Regular', '', 'ChakraPetch-Regular.php');
            $pdf->AddFont('ChakraPetch-Bold', '', 'ChakraPetch-Bold.php');
            $pdf->SetFont('ChakraPetch-Bold', '', 12);


            // LOGO
            $pageWidth = $pdf->GetPageWidth();
            $actual_height = $this->addOwnerLogo($pdf, $contact, $pageWidth);

            //TARİH - KOD
            $this->addDateAndCode($pdf, $document_date, $contact, $actual_height, $sale->id, $pageWidth, 'OFR');

            //COMPANY INFO
            $y = $this->addOwnerInfo($pdf, $contact);


            //TITLE
            $y = $this->addPdfTitle($pdf, __('Offer'), $y);

            //CUSTOMER INFO
            $y = $this->addCompanyInfo($pdf, $lang, $company, $y);



            //QUOTES

            if ($company->company_request_code != ''){
                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Request Code').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Request Code').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->company_request_code), '0', '0', '');
            }

            if ($quote->payment_term != null) {

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Payment Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-4 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->payment_term), '0', '0', '');

            }else if ($company->payment_term != null){

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Payment Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-4 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->payment_term), '0', '0', '');

            }

            if ($quote->delivery_term != null) {

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Delivery Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-3 + $pdf->GetStringWidth(__('Delivery Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Delivery Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->delivery_term), '0', '0', '');

            }

            //Insurance olarak kullanılıyor
            if ($quote->lead_time != null) {

                $y += 5;
                $x = 10;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Insurance').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Insurance').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->lead_time), '0', '0', '');

            }

            if ($quote->country_of_destination != null) {

                $y += 5;
                $x = 10;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Country of Destination').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Country of Destination').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->country_of_destination), '0', '0', '');

            }

            if ($quote->expiry_date != null) {

                $y += 5;
                $x = 10;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Expiry Date').': '), '0', '0', '');

                $not_formatted_expiry_date = Carbon::parse($quote->expiry_date);
                $expiry_date = $not_formatted_expiry_date->format('d/m/Y');
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Expiry Date').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $expiry_date), '0', '0', '');

            }


            // Set table header
            $x = 10;
            $y += 10;
            $pdf->SetXY($x, $y);

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->Cell(10, 12, 'N#', 0, 0, 'C');
            $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Ref. Code')), 0, 0, 'C');
            $pdf->Cell(50, 12, iconv('utf-8', 'iso-8859-9', __('Product Name')), 0, 0, 'C');
            $pdf->Cell(19, 12, iconv('utf-8', 'iso-8859-9', __('Qty')), 0, 0, 'C');
            $pdf->Cell(16, 12, iconv('utf-8', 'iso-8859-9', __('Unit')), 0, 0, 'C');
            $pdf->Cell(25, 12, iconv('utf-8', 'iso-8859-9', __('Unit Price')), 0, 0, 'C');
            $pdf->Cell(30, 12, iconv('utf-8', 'iso-8859-9', __('Total Price')), 0, 0, 'C');
//            $pdf->Cell(20, 10, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
            $lt_width = $pdf->GetStringWidth(__('Lead Time'));
            if ($lt_width > 20){
                $pdf->MultiCell(20, 6, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 'C');  // Move to the next line
            }else{
                $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
                $pdf->Ln();
            }



            // Set table content
            $pdf->SetFont('ChakraPetch-Regular', '', 9);
            $x = 10;
            $y += 12;
            $pdf->SetXY($x, $y);
            foreach ($sale_offers as $sale_offer) {
                if ($lang == 'tr'){
                    $measurement_name = $sale_offer->measurement_name_tr;
                }else{
                    $measurement_name = $sale_offer->measurement_name_en;
                }

                $pdf->SetFont('ChakraPetch-Regular', '', 9);

                $x = 40;
                $pdf->SetXY($x, $y);

                $product_name = $this->textConvert($sale_offer->product_name);
                $name_width = $pdf->GetStringWidth($product_name);
                $lines_needed = ceil($name_width / 50);
                $line_height = 8;
                if ($lines_needed > 1){
                    $line_height = 5;
                }
                $row_height = $lines_needed * $line_height;
                $pdf->MultiCell(50, $line_height, $product_name, 1, 'L');


                $x = 10;
                $pdf->SetXY($x, $y);
                $pdf->Cell(10, $row_height, $sale_offer->sequence, 1, 0, 'C');
                $pdf->Cell(20, $row_height, iconv('utf-8', 'iso-8859-9', $sale_offer->product_ref_code), 1, 0, 'C');

                $x = 90;
                $pdf->SetXY($x, $y);
                $pdf->Cell(19, $row_height, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_quantity), 1, 0, 'C');
                $pdf->Cell(16, $row_height, iconv('utf-8', 'iso-8859-9', $measurement_name), 1, 0, 'C');
                $pdf->Cell(25, $row_height, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_pcs_price.' '.$currency), 1, 0, 'C');
                $pdf->Cell(30, $row_height, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_price.' '.$currency), 1, 0, 'C');
                $pdf->Cell(20, $row_height, iconv('utf-8', 'iso-8859-9', $this->leadtime($sale_offer->offer_lead_time)), 1, 1, 'C');

                $y += $row_height;

            }




            //TOTAL PRICES

            $x = 10;
            $y = $pdf->GetY();

            if ($sale->sub_total != null) {
                $title = __('Sub Total');
                if ($sale->vat == null || $sale->vat == '0.00' && $sale->freight == null) {
                    $title = __('Grand Total');
                }

                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper($title)), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->sub_total, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->freight != null) {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Freight'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->freight, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->vat != null && $sale->vat != '0.00') {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Vat'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->vat, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->grand_total != null) {
                if ($sale->vat != null && $sale->vat != '0.00' && $sale->freight != null) {
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Grand Total'))), 1, 0, 'R');

                    $pdf->SetXY($x + 140, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->grand_total, 2,",",".") . ' ' . $currency), 1, 0, 'C');

                    $y += 10;
                }
            }


            //NOTE
            if ($quote->note != null) {
                $y += 10;
                $x = 10;
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 8);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Note')), 0, 0, '');

                $y += 5;
                $x = 10;
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 8);
                $html = $this->textConvert($quote->note);
//                $html = utf8_decode($html);
                $html = str_replace('<br>', "\n", $html);
                $html = str_replace('<p>', '', $html);
                $html = str_replace('</p>', "\n", $html);
//                $d = $pdf->GetX();
//                $f = $pdf->GetY();
//                $pdf->Cell(0, 0, $d.'-'.$f, 0, 0, '');
//
//                $y += 5;
//                $x = 10;
//                $pdf->SetXY($x, $y);
////                $pdf->MultiCell(2, $html);
                $pdf->MultiCell(0, 5, $html);
            }



            //FOOTER

            $pdfContent = $pdf->Output('created.pdf', 'S');

            $pdf = new Fpdi();
            $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            $numPages = $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            for ($pageNo = 1; $pageNo <= $numPages; $pageNo++) {
                $pdf->AddPage();

                $pdf->Image(public_path($contact->footer), 10, 260, 190);

                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx, 0, 0, null, null, true);
            }

            $filePath = public_path('img/document/' . $contact->short_code . '-OFR-' . $sale->id . '.pdf');
            $pdf->Output($filePath, 'F');

            $fileUrl = 'img/document/' . $contact->short_code . '-OFR-' . $sale->id . '.pdf';
            $fileName = $contact->short_code . '-OFR-' . $sale->id . '.pdf';

            Document::query()->where('id', $document_id)->update([
                'file_url' => $fileUrl
            ]);

            return response([
                'message' => __('İşlem Başarılı.'),
                'status' => 'success',
                'object' => [
                    'file_url' => $fileUrl,
                    'file_name' => $fileName
                ]
            ]);


        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getGenerateOrderConfirmationPDF($lang, $owner_id, $sale_id, $bank_id)
    {
        try {
            App::setLocale($lang);

            $sale = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->first();

            $currency = $sale->currency;

            $this_document = Document::query()->where('sale_id', $sale_id)->where('document_type_id', 2)->first();
            if ($this_document){
                $createdAt = Carbon::parse($this_document->created_at);
                $document_date = $createdAt->format('d/m/Y');
                $document_id = $this_document->id;
            }else{
                $createdAt = Carbon::now();
                $document_date = $createdAt->format('d/m/Y');
                $document_id = Document::query()->insertGetId([
                    'sale_id' => $sale_id,
                    'document_type_id' => 2,
                    'created_at' => $createdAt->format('Y-m-d H:i:s')
                ]);
            }

            $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale_id)->get();

            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
            $product_count = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
            $authorized_personnel = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
            $company = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
            $company_employee = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();

            $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
            foreach ($sale_offers as $sale_offer){
                $sale_offer['supplier_name'] = Company::query()->where('id', $sale_offer->supplier_id)->first()->name;
                $sale_offer['product_name'] = Product::query()->where('id', $sale_offer->product_id)->first()->product_name;
                $sale_offer['product_ref_code'] = Product::query()->where('id', $sale_offer->product_id)->first()->ref_code;
                $offer_pcs_price = $sale_offer->offer_price / $sale_offer->offer_quantity;
                $sale_offer['offer_pcs_price'] = number_format($offer_pcs_price, 2,",",".");
                $sale_offer->offer_price = number_format($sale_offer->offer_price, 2,",",".");
                $sale_offer->pcs_price = number_format($sale_offer->pcs_price, 2,",",".");
                $sale_offer->total_price = number_format($sale_offer->total_price, 2,",",".");
                $sale_offer->discounted_price = number_format($sale_offer->discounted_price, 2,",",".");
                $sale_offer['measurement_name_tr'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_tr;
                $sale_offer['measurement_name_en'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_en;

                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                $request_product = OfferRequestProduct::query()->where('id', $offer_product->request_product_id)->first();
                $sale_offer['sequence'] = $request_product->sequence;

            }
            $contact = Contact::query()->where('id', $owner_id)->first();

            $quote_count = Quote::query()->where('sale_id', $sale_id)->count();
            if ($quote_count == 0){
                $quote_id = Uuid::uuid();
                Quote::query()->insert([
                    'quote_id' => $quote_id,
                    'sale_id' => $sale_id
                ]);
            }
            $quote = Quote::query()->where('sale_id', $sale_id)->first();


            // Create a new PDF instance
            $pdf = new \FPDF();
            $pdf->AddPage();

            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(true, 40);

            $pdf->AddFont('ChakraPetch-Regular', '', 'ChakraPetch-Regular.php');
            $pdf->AddFont('ChakraPetch-Bold', '', 'ChakraPetch-Bold.php');
            $pdf->SetFont('ChakraPetch-Bold', '', 12);


            // LOGO
            $pageWidth = $pdf->GetPageWidth();
            $x = $pageWidth - $contact->logo_width - 10;
            $pdf->Image(public_path($contact->logo), $x, 10, $contact->logo_width);  // Parameters: image file, x position, y position, width

            list($imageWidth, $imageHeight) = getimagesize(public_path($contact->logo));
            $actual_height = (int) ($contact->logo_width * $imageHeight / $imageWidth);

            //TARİH - KOD

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth(__('Date').': '.$document_date) - 10;
            $pdf->SetXY($x, $actual_height + 25);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Date').': '), '0', '0', '');
            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth($document_date) - 10;
            $pdf->SetXY($x, $actual_height + 25);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $document_date), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Bold', '', 11);
            $x = $pageWidth - $pdf->GetStringWidth($contact->short_code.'-OC-'.$sale->id) - 10;
            $pdf->SetXY($x, $actual_height + 32);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->short_code.'-OC-'.$sale->id), '0', '0', '');




            //COMPANY INFO

            $x = 10;
            $y = 15;

            $pdf->SetFont('ChakraPetch-Bold', '', 12);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->name), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);

            if ($contact->registration_no != '') {
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, __('Registration No').': ', '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Registration No').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, $contact->registration_no, '0', '0', '');

                if ($contact->registration_office != '' && App::getLocale() != 'en') {

                    $x = $x+5 + $pdf->GetStringWidth($contact->registration_no);

                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, __('Registration Office').': ', '0', '0', '');

                    $x = $x+2 + $pdf->GetStringWidth(__('Registration Office').': ');
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, $contact->registration_office, '0', '0', '');

                }
            }

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = 10;
            $y += 5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Address').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $lines = explode('<br>', $contact->address);
            foreach ($lines as $line) {
                $y += 5;
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $line), '0', '0', '');
            }

            $y += 5;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Phone').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->phone, '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Email').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->email, '0', '0', '');

            //TITLE

            $y += 10;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 20);
            $pdf->SetXY($x, $y);
            $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', __('Order Confirmation'));
            $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
            $title = iconv('utf-8', 'iso-8859-9', $inputString);
            $pdf->Cell(0, 0, $title, '0', '0', '');

            //CUSTOMER INFO

            $y += 10;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Customer').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            if ($lang == 'tr') {
                $x = $x - 3 + $pdf->GetStringWidth(__('Customer') . ': ');
            }else{
                $x = $x+2 + $pdf->GetStringWidth(__('Customer') . ': ');
            }
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->name), '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Address').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Address').': ');
            $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $company->address);
            $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
            $address = iconv('utf-8', 'iso-8859-9', $inputString);
//            $pdf->Cell(0, 0, $address, '0', '0', '');
            $address_width = $pdf->GetStringWidth($address);
            $address_height = (((int)($address_width / 100)) + 1) * 2;

            if ($address_height == 2){
                $pdf->SetXY($x, $y);
            }else {
                $pdf->SetXY($x, $y - 2);
            }
            $pdf->MultiCell(100, $address_height, $address, 0, 'L');

            //QUOTES

            $y += 8;

            if ($quote->payment_term != null) {

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Payment Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-4 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->payment_term), '0', '0', '');

            }else if ($company->payment_term != null){

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Payment Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-4 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->payment_term), '0', '0', '');

            }

            if ($quote->delivery_term != null) {

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Delivery Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-3 + $pdf->GetStringWidth(__('Delivery Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Delivery Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->delivery_term), '0', '0', '');

            }

            if ($quote->country_of_destination != null) {

                $y += 5;
                $x = 10;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Country of Destination').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Country of Destination').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->country_of_destination), '0', '0', '');

            }


            $x = 10;
            $y += 10;
            $pdf->SetXY($x, $y);



// Set table header
            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->Cell(10, 12, 'N#', 0, 0, 'C');
            $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Ref. Code')), 0, 0, 'C');
            $pdf->Cell(50, 12, iconv('utf-8', 'iso-8859-9', __('Product Name')), 0, 0, 'C');
            $pdf->Cell(19, 12, iconv('utf-8', 'iso-8859-9', __('Qty')), 0, 0, 'C');
            $pdf->Cell(16, 12, iconv('utf-8', 'iso-8859-9', __('Unit')), 0, 0, 'C');
            $pdf->Cell(25, 12, iconv('utf-8', 'iso-8859-9', __('Unit Price')), 0, 0, 'C');
            $pdf->Cell(30, 12, iconv('utf-8', 'iso-8859-9', __('Total Price')), 0, 0, 'C');
//            $pdf->Cell(20, 10, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
            $lt_width = $pdf->GetStringWidth(__('Lead Time'));
            if ($lt_width > 20){
                $pdf->MultiCell(20, 6, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 'C');  // Move to the next line
            }else{
                $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
                $pdf->Ln();
            }



// Set table content
            $pdf->SetFont('ChakraPetch-Regular', '', 9);
            foreach ($sale_offers as $sale_offer) {
                if (App::getLocale() == 'tr'){
                    $measurement_name = $sale_offer->measurement_name_tr;
                }else{
                    $measurement_name = $sale_offer->measurement_name_en;
                }

                if ($sale_offer->offer_lead_time != '' && $sale_offer->offer_lead_time != null){
                    if ($sale_offer->offer_lead_time == 1) {
                        $lead_time = __('Stock');
                    } elseif (intval($sale_offer->offer_lead_time) % 7 == 0) {
                        $lead_time = (intval($sale_offer->offer_lead_time) / 7) . ' ' . __('Week');
                    } else {
                        $lead_time = $sale_offer->offer_lead_time . ' ' . __('Day');
                    }
                }else{
                    $lead_time = '';
                }

                $row_height = 15;
                $pdf->SetFont('ChakraPetch-Regular', '', 9);

                $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $sale_offer->product_name);
                $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
                $inputString = preg_replace('/[^\x20-\x7E]/u', '', $inputString);
                $product_name = iconv('utf-8', 'iso-8859-9', $inputString);

                $name_width = $pdf->GetStringWidth($product_name);
                if ($name_width > 48){
                    $wd = (($name_width / 48));
                    if ($name_width > 60){
                        $wd = (($name_width / 60));
                    }
                    if ($name_width > 100){
                        $wd = (($name_width / 50));
                    }
                    if ($name_width > 110){
                        $wd = (($name_width / 45));
                    }
                    if ($name_width > 200){
                        $wd = (($name_width / 40));
                    }
                    if ($wd >= 0 && $wd < 1){
                        $row_height = 15;
                    }else if ($wd >= 1 && $wd < 2){
                        $row_height = 7.5;
                    }else if ($wd >= 2 && $wd < 3){
                        $row_height = 5;
                    }else if ($wd >= 3 && $wd < 4){
                        $row_height = 3.75;
                    }else if ($wd >= 4 && $wd < 5){
                        $row_height = 3;
                    }else if ($wd >= 5){
                        $row_height = 2.5;
                    }

                }

                $pdf->setX(10);
                $pdf->Cell(10, 15, $sale_offer->sequence, 1, 0, 'C');
//                $pdf->Cell(10, 14, '', 1, 0, 'C');
                $pdf->Cell(20, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->product_ref_code), 1, 0, 'C');
//                $pdf->Cell(20, 14, iconv('utf-8', 'iso-8859-9', $row_height.' - '.$name_width), 1, 0, 'C');

                // Save the current X and Y position
                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();


                // Use MultiCell for product name with a width of 50mm
                $pdf->MultiCell(50, $row_height, $product_name, 'T', 'L');

                // Reset X and move Y to the saved position (next line)
                $pdf->SetXY($xPos+50, $yPos);

                // Output remaining cells for the current row
                $pdf->Cell(19, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_quantity), 1, 0, 'C');
                $pdf->Cell(16, 15, iconv('utf-8', 'iso-8859-9', $measurement_name), 1, 0, 'C');
                $pdf->Cell(25, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_pcs_price.' '.$currency), 1, 0, 'C');
                $pdf->Cell(30, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_price.' '.$currency), 1, 0, 'C');
                $pdf->Cell(20, 15, iconv('utf-8', 'iso-8859-9', $lead_time), 1, 1, 'C');  // Move to the next line
            }

            //TOTAL PRICES

            $x = 10;
            $y = $pdf->GetY();

            if ($sale->sub_total != null) {
                $title = __('Sub Total');
                if ($sale->vat == null || $sale->vat == '0.00' && $sale->freight == null) {
                    $title = __('Grand Total');
                }

                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper($title)), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->sub_total, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->freight != null) {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Freight'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->freight, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->vat != null && $sale->vat != '0.00') {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Vat'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->vat, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->grand_total != null) {
                if ($sale->vat != null && $sale->vat != '0.00' && $sale->freight != null) {
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Grand Total'))), 1, 0, 'R');

                    $pdf->SetXY($x + 140, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->grand_total, 2,",",".") . ' ' . $currency), 1, 0, 'C');

                    $y += 10;
                }
            }


            //NOTE
            $oc_detail = OrderConfirmationDetail::query()->where('sale_id', $sale_id)->first();
            if ($oc_detail) {
                if ($oc_detail->note != null) {
                    $y += 10;
                    $x = 10;
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 8);
                    $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Note')), 0, 0, '');

                    $y += 5;
                    $x = 10;
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 8);
                    $html = utf8_decode($oc_detail->note);
                    $html = str_replace('<br>', "\n", $html);
                    $html = str_replace('<p>', '', $html);
                    $html = str_replace('</p>', "\n", $html);
                    $pdf->MultiCell(0, 5, utf8_decode($html));
                }
            }


            //SIGNATURES

            $y += 20;
            $x = 10;
            $pdf->SetXY($x, $y);
            $pdf->SetFont('ChakraPetch-Bold', '', 8);
            $pdf->Cell(70, 0, iconv('utf-8', 'iso-8859-9', __('Authorised Signature')), 0, 0, 'C');
            $x = 130;
            $pdf->SetXY($x, $y);
            $pdf->SetFont('ChakraPetch-Bold', '', 8);
            $pdf->Cell(70, 0, iconv('utf-8', 'iso-8859-9', __('Customer Confirmation')), 0, 0, 'C');

            //Signature
            $height = 20;
            $imagePath = public_path($contact->signature);
            list($originalWidth, $originalHeight) = getimagesize($imagePath);
            $aspectRatio = $originalWidth / $originalHeight;
            $width = $height * $aspectRatio;
            $y += 1;
            $x = 10 + ((70-$width)/2);
            $pdf->Image($imagePath, $x, $y, $width, $height);

            $y += 20;
            $x = 10;
            $pdf->SetXY($x, $y);
            $pdf->SetFont('ChakraPetch-Bold', '', 8);
            $pdf->Cell(70, 0, iconv('utf-8', 'iso-8859-9', $contact->authorized_name), 0, 0, 'C');

            $y += 3;
            $x = 10;
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->Line($x, $y, $x+70, $y);
            $x = 130;
            $pdf->Line($x, $y, $x+70, $y);

            $y += 3;
            $x = 10;
            $text1 = __('Name Surname')." / ".__('Signature');
            $text2 = __('Name Surname')." / ".__('Signature')." / ".__('Date');
            $pdf->SetXY($x, $y);
            $pdf->SetFont('ChakraPetch-Bold', '', 8);
            $pdf->Cell(70, 0, iconv('utf-8', 'iso-8859-9', $text1), 0, 0, 'C');
            $x = 130;
            $pdf->SetXY($x, $y);
            $pdf->SetFont('ChakraPetch-Bold', '', 8);
            $pdf->Cell(70, 0, iconv('utf-8', 'iso-8859-9', $text2), 0, 0, 'C');

            //BANK INFO
            if ($bank_id != 0){

                $bank = OwnerBankInfo::query()->where('id', $bank_id)->first();
                $y += 10;
                $x = 10;
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 8);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Bank Details')), 0, 0, '');

                $x = 10;
                $pdf->SetFont('ChakraPetch-Regular', '', 8);
                $html = str_replace('<p>', '', $bank->detail);
                $html_array = explode('</p>', $html);
                foreach ($html_array as $item) {
                    $y += 5;
                    $pdf->SetXY($x, $y);
                    $inputString = mb_convert_encoding($item, 'ISO-8859-9', 'UTF-8');

                    $pdf->Cell(0, 0, $inputString, 0, 0, '');
                }

            }



            //FOOTER

            $pdfContent = $pdf->Output('created.pdf', 'S');

            $pdf = new Fpdi();
            $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            $numPages = $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            for ($pageNo = 1; $pageNo <= $numPages; $pageNo++) {
                $pdf->AddPage();

                $pdf->Image(public_path($contact->footer), 10, 260, 190);

                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx, 0, 0, null, null, true);
            }

            $filePath = public_path('img/document/' . $contact->short_code . '-OC-' . $sale->id . '.pdf');
            $pdf->Output($filePath, 'F');

            $fileUrl = 'img/document/' . $contact->short_code . '-OC-' . $sale->id . '.pdf';
            $fileName = $contact->short_code . '-OC-' . $sale->id . '.pdf';

            Document::query()->where('id', $document_id)->update([
                'file_url' => $fileUrl
            ]);

            return response([
                'message' => __('İşlem Başarılı.'),
                'status' => 'success',
                'object' => [
                    'file_url' => $fileUrl,
                    'file_name' => $fileName
                ]
            ]);


        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getGenerateProformaInvoicePDF($lang, $owner_id, $sale_id, $bank_id)
    {
        try {
            App::setLocale($lang);

            $sale = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->first();

            $currency = $sale->currency;

            $this_document = Document::query()->where('sale_id', $sale_id)->where('document_type_id', 4)->first();
            if ($this_document){
                $createdAt = Carbon::parse($this_document->created_at);
                $document_date = $createdAt->format('d/m/Y');
                $document_id = $this_document->id;
            }else{
                $createdAt = Carbon::now();
                $document_date = $createdAt->format('d/m/Y');
                $document_id = Document::query()->insertGetId([
                    'sale_id' => $sale_id,
                    'document_type_id' => 4,
                    'created_at' => $createdAt->format('Y-m-d H:i:s')
                ]);
            }

            $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale_id)->get();

            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
            $product_count = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
            $authorized_personnel = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
            $company = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
            $company_employee = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();

            $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
            foreach ($sale_offers as $sale_offer){
                $sale_offer['supplier_name'] = Company::query()->where('id', $sale_offer->supplier_id)->first()->name;
                $sale_offer['product_name'] = Product::query()->where('id', $sale_offer->product_id)->first()->product_name;
                $sale_offer['product_ref_code'] = Product::query()->where('id', $sale_offer->product_id)->first()->ref_code;
                $offer_pcs_price = $sale_offer->offer_price / $sale_offer->offer_quantity;
                $sale_offer['offer_pcs_price'] = number_format($offer_pcs_price, 2,",",".");
                $sale_offer->offer_price = number_format($sale_offer->offer_price, 2,",",".");
                $sale_offer->pcs_price = number_format($sale_offer->pcs_price, 2,",",".");
                $sale_offer->total_price = number_format($sale_offer->total_price, 2,",",".");
                $sale_offer->discounted_price = number_format($sale_offer->discounted_price, 2,",",".");
                $sale_offer['measurement_name_tr'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_tr;
                $sale_offer['measurement_name_en'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_en;

                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                $request_product = OfferRequestProduct::query()->where('id', $offer_product->request_product_id)->first();
                $sale_offer['sequence'] = $request_product->sequence;

            }
            $contact = Contact::query()->where('id', $owner_id)->first();

            $quote_count = Quote::query()->where('sale_id', $sale_id)->count();
            if ($quote_count == 0){
                $quote_id = Uuid::uuid();
                Quote::query()->insert([
                    'quote_id' => $quote_id,
                    'sale_id' => $sale_id
                ]);
            }
            $quote = Quote::query()->where('sale_id', $sale_id)->first();


            // Create a new PDF instance
            $pdf = new \FPDF();
            $pdf->AddPage();

            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(true, 40);

            $pdf->AddFont('ChakraPetch-Regular', '', 'ChakraPetch-Regular.php');
            $pdf->AddFont('ChakraPetch-Bold', '', 'ChakraPetch-Bold.php');
            $pdf->SetFont('ChakraPetch-Bold', '', 12);


            // LOGO
            $pageWidth = $pdf->GetPageWidth();
            $x = $pageWidth - $contact->logo_width - 10;
            $pdf->Image(public_path($contact->logo), $x, 10, $contact->logo_width);  // Parameters: image file, x position, y position, width

            list($imageWidth, $imageHeight) = getimagesize(public_path($contact->logo));
            $actual_height = (int) ($contact->logo_width * $imageHeight / $imageWidth);

            //TARİH - KOD

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth(__('Date').': '.$document_date) - 10;
            $pdf->SetXY($x, $actual_height + 25);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Date').': '), '0', '0', '');
            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth($document_date) - 10;
            $pdf->SetXY($x, $actual_height + 25);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $document_date), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Bold', '', 11);
            $x = $pageWidth - $pdf->GetStringWidth($contact->short_code.'-PI-'.$sale->id) - 10;
            $pdf->SetXY($x, $actual_height + 32);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->short_code.'-PI-'.$sale->id), '0', '0', '');




            //COMPANY INFO

            $x = 10;
            $y = 15;

            $pdf->SetFont('ChakraPetch-Bold', '', 12);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->name), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);

            if ($contact->registration_no != '') {
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, __('Registration No').': ', '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Registration No').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, $contact->registration_no, '0', '0', '');

                if ($contact->registration_office != '' && App::getLocale() != 'en') {

                    $x = $x+5 + $pdf->GetStringWidth($contact->registration_no);

                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, __('Registration Office').': ', '0', '0', '');

                    $x = $x+2 + $pdf->GetStringWidth(__('Registration Office').': ');
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, $contact->registration_office, '0', '0', '');

                }
            }

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = 10;
            $y += 5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Address').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $lines = explode('<br>', $contact->address);
            foreach ($lines as $line) {
                $y += 5;
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $line), '0', '0', '');
            }

            $y += 5;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Phone').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->phone, '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Email').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->email, '0', '0', '');

            //TITLE

            $y += 10;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 20);
            $pdf->SetXY($x, $y);
            $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', __('Proforma Invoice'));
            $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
            $title = iconv('utf-8', 'iso-8859-9', $inputString);
            $pdf->Cell(0, 0, $title, '0', '0', '');

            //CUSTOMER INFO

            $y += 10;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Customer').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            if ($lang == 'tr') {
                $x = $x - 3 + $pdf->GetStringWidth(__('Customer') . ': ');
            }else{
                $x = $x+2 + $pdf->GetStringWidth(__('Customer') . ': ');
            }
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->name), '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Address').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Address').': ');
            $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $company->address);
            $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
            $address = iconv('utf-8', 'iso-8859-9', $inputString);
//            $pdf->Cell(0, 0, $address, '0', '0', '');
            $address_width = $pdf->GetStringWidth($address);
            $address_height = (((int)($address_width / 100)) + 1) * 2;

            if ($address_height == 2){
                $pdf->SetXY($x, $y);
            }else {
                $pdf->SetXY($x, $y - 2);
            }
            $pdf->MultiCell(100, $address_height, $address, 0, 'L');

            //QUOTES

            $y += 8;

            if ($quote->payment_term != null) {

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Payment Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-4 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->payment_term), '0', '0', '');

            }else if ($company->payment_term != null){

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Payment Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-4 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->payment_term), '0', '0', '');

            }

            if ($quote->delivery_term != null) {

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Delivery Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-3 + $pdf->GetStringWidth(__('Delivery Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Delivery Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->delivery_term), '0', '0', '');

            }

            if ($quote->country_of_destination != null) {

                $y += 5;
                $x = 10;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Country of Destination').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Country of Destination').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->country_of_destination), '0', '0', '');

            }


            $x = 10;
            $y += 10;
            $pdf->SetXY($x, $y);



// Set table header
            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->Cell(10, 12, 'N#', 0, 0, 'C');
            $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Ref. Code')), 0, 0, 'C');
            $pdf->Cell(50, 12, iconv('utf-8', 'iso-8859-9', __('Product Name')), 0, 0, 'C');
            $pdf->Cell(19, 12, iconv('utf-8', 'iso-8859-9', __('Qty')), 0, 0, 'C');
            $pdf->Cell(16, 12, iconv('utf-8', 'iso-8859-9', __('Unit')), 0, 0, 'C');
            $pdf->Cell(25, 12, iconv('utf-8', 'iso-8859-9', __('Unit Price')), 0, 0, 'C');
            $pdf->Cell(50, 12, iconv('utf-8', 'iso-8859-9', __('Total Price')), 0, 0, 'C');
            $pdf->Ln();
//            $pdf->Cell(20, 10, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
//            $lt_width = $pdf->GetStringWidth(__('Lead Time'));
//            if ($lt_width > 20){
//                $pdf->MultiCell(20, 6, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 'C');  // Move to the next line
//            }else{
//                $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
//                $pdf->Ln();
//            }



// Set table content
            $pdf->SetFont('ChakraPetch-Regular', '', 9);
            foreach ($sale_offers as $sale_offer) {
                if (App::getLocale() == 'tr'){
                    $measurement_name = $sale_offer->measurement_name_tr;
                }else{
                    $measurement_name = $sale_offer->measurement_name_en;
                }

                if ($sale_offer->offer_lead_time != '' && $sale_offer->offer_lead_time != null){
                    if ($sale_offer->offer_lead_time == 1) {
                        $lead_time = __('Stock');
                    } elseif (intval($sale_offer->offer_lead_time) % 7 == 0) {
                        $lead_time = (intval($sale_offer->offer_lead_time) / 7) . ' ' . __('Week');
                    } else {
                        $lead_time = $sale_offer->offer_lead_time . ' ' . __('Day');
                    }
                }else{
                    $lead_time = '';
                }

                $row_height = 15;
                $pdf->SetFont('ChakraPetch-Regular', '', 9);

                $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $sale_offer->product_name);
                $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
                $inputString = preg_replace('/[^\x20-\x7E]/u', '', $inputString);
                $product_name = iconv('utf-8', 'iso-8859-9', $inputString);

                $name_width = $pdf->GetStringWidth($product_name);
                if ($name_width > 48){
                    $wd = (($name_width / 48));
                    if ($name_width > 60){
                        $wd = (($name_width / 60));
                    }
                    if ($name_width > 100){
                        $wd = (($name_width / 50));
                    }
                    if ($name_width > 110){
                        $wd = (($name_width / 45));
                    }
                    if ($name_width > 200){
                        $wd = (($name_width / 40));
                    }
                    if ($wd >= 0 && $wd < 1){
                        $row_height = 15;
                    }else if ($wd >= 1 && $wd < 2){
                        $row_height = 7.5;
                    }else if ($wd >= 2 && $wd < 3){
                        $row_height = 5;
                    }else if ($wd >= 3 && $wd < 4){
                        $row_height = 3.75;
                    }else if ($wd >= 4 && $wd < 5){
                        $row_height = 3;
                    }else if ($wd >= 5){
                        $row_height = 2.5;
                    }

                }

                $pdf->setX(10);
                $pdf->Cell(10, 15, $sale_offer->sequence, 1, 0, 'C');
//                $pdf->Cell(10, 14, '', 1, 0, 'C');
                $pdf->Cell(20, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->product_ref_code), 1, 0, 'C');
//                $pdf->Cell(20, 14, iconv('utf-8', 'iso-8859-9', $row_height.' - '.$name_width), 1, 0, 'C');

                // Save the current X and Y position
                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();


                // Use MultiCell for product name with a width of 50mm
                $pdf->MultiCell(50, $row_height, $product_name, 'T', 'L');

                // Reset X and move Y to the saved position (next line)
                $pdf->SetXY($xPos+50, $yPos);

                // Output remaining cells for the current row
                $pdf->Cell(19, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_quantity), 1, 0, 'C');
                $pdf->Cell(16, 15, iconv('utf-8', 'iso-8859-9', $measurement_name), 1, 0, 'C');
                $pdf->Cell(25, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_pcs_price.' '.$currency), 1, 0, 'C');
                $pdf->Cell(50, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_price.' '.$currency), 1, 0, 'C');
                $pdf->Ln();
//                $pdf->Cell(20, 15, iconv('utf-8', 'iso-8859-9', $lead_time), 1, 1, 'C');  // Move to the next line
            }

            //TOTAL PRICES

            $x = 10;
            $y = $pdf->GetY();

            if ($sale->sub_total != null) {
                $title = __('Sub Total');
                if ($sale->vat == null || $sale->vat == '0.00' && $sale->freight == null) {
                    $title = __('Grand Total');
                }

                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper($title)), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->sub_total, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->freight != null) {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Freight'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->freight, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->vat != null && $sale->vat != '0.00') {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Vat'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->vat, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->grand_total != null) {
                if ($sale->vat != null && $sale->vat != '0.00' && $sale->freight != null) {
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Grand Total'))), 1, 0, 'R');

                    $pdf->SetXY($x + 140, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->grand_total, 2,",",".") . ' ' . $currency), 1, 0, 'C');

                    $y += 10;
                }
            }

            if ($sale->shipping_price != null) {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Shipping'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->shipping_price, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->grand_total_with_shipping != null) {
                if ($sale->shipping_price != null) {
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Grand Total'))), 1, 0, 'R');

                    $pdf->SetXY($x + 140, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->grand_total_with_shipping, 2,",",".") . ' ' . $currency), 1, 0, 'C');

                    $y += 10;
                }
            }


            //NOTE
            $pi_detail = ProformaInvoiceDetails::query()->where('sale_id', $sale_id)->first();
            if ($pi_detail) {
                if ($pi_detail->note != null) {
                    $y += 10;
                    $x = 10;
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 8);
                    $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Note')), 0, 0, '');

                    $y += 5;
                    $x = 10;
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 8);
                    $html = utf8_decode($pi_detail->note);
                    $html = str_replace('<br>', "\n", $html);
                    $html = str_replace('<p>', '', $html);
                    $html = str_replace('</p>', "\n", $html);
                    $pdf->MultiCell(0, 5, utf8_decode($html));
                }
            }


            //BANK INFO
            if ($bank_id != 0){

                $bank = OwnerBankInfo::query()->where('id', $bank_id)->first();
                $y += 10;
                $x = 10;
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 8);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Bank Details')), 0, 0, '');

                $x = 10;
                $pdf->SetFont('ChakraPetch-Regular', '', 8);
                $html = str_replace('<p>', '', $bank->detail);
                $html_array = explode('</p>', $html);
                foreach ($html_array as $item) {
                    $y += 5;
                    $pdf->SetXY($x, $y);
                    $inputString = mb_convert_encoding($item, 'ISO-8859-9', 'UTF-8');

                    $pdf->Cell(0, 0, $inputString, 0, 0, '');
                }

            }



            //FOOTER

            $pdfContent = $pdf->Output('created.pdf', 'S');

            $pdf = new Fpdi();
            $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            $numPages = $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            for ($pageNo = 1; $pageNo <= $numPages; $pageNo++) {
                $pdf->AddPage();

                $pdf->Image(public_path($contact->footer), 10, 260, 190);

                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx, 0, 0, null, null, true);
            }

            $filePath = public_path('img/document/' . $contact->short_code . '-PI-' . $sale->id . '.pdf');
            $pdf->Output($filePath, 'F');

            $fileUrl = 'img/document/' . $contact->short_code . '-PI-' . $sale->id . '.pdf';
            $fileName = $contact->short_code . '-PI-' . $sale->id . '.pdf';

            Document::query()->where('id', $document_id)->update([
                'file_url' => $fileUrl
            ]);

            return response([
                'message' => __('İşlem Başarılı.'),
                'status' => 'success',
                'object' => [
                    'file_url' => $fileUrl,
                    'file_name' => $fileName
                ]
            ]);


        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getGenerateInvoicePDF($lang, $owner_id, $sale_id, $bank_id)
    {
        try {
            App::setLocale($lang);

            $sale = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->first();

            $currency = $sale->currency;

            $this_document = Document::query()->where('sale_id', $sale_id)->where('document_type_id', 3)->first();
            if ($this_document){
                $createdAt = Carbon::parse($this_document->created_at);
                $document_date = $createdAt->format('d/m/Y');
                $document_id = $this_document->id;
            }else{
                $createdAt = Carbon::now();
                $document_date = $createdAt->format('d/m/Y');
                $document_id = Document::query()->insertGetId([
                    'sale_id' => $sale_id,
                    'document_type_id' => 3,
                    'created_at' => $createdAt->format('Y-m-d H:i:s')
                ]);
            }

            $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale_id)->get();

            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
            $product_count = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
            $authorized_personnel = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
            $company = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
            $company_employee = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();

            $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
            foreach ($sale_offers as $sale_offer){
                $sale_offer['supplier_name'] = Company::query()->where('id', $sale_offer->supplier_id)->first()->name;
                $sale_offer['product_name'] = Product::query()->where('id', $sale_offer->product_id)->first()->product_name;
                $sale_offer['product_ref_code'] = Product::query()->where('id', $sale_offer->product_id)->first()->ref_code;
                $offer_pcs_price = $sale_offer->offer_price / $sale_offer->offer_quantity;
                $sale_offer['offer_pcs_price'] = number_format($offer_pcs_price, 2,",",".");
                $sale_offer->offer_price = number_format($sale_offer->offer_price, 2,",",".");
                $sale_offer->pcs_price = number_format($sale_offer->pcs_price, 2,",",".");
                $sale_offer->total_price = number_format($sale_offer->total_price, 2,",",".");
                $sale_offer->discounted_price = number_format($sale_offer->discounted_price, 2,",",".");
                $sale_offer['measurement_name_tr'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_tr;
                $sale_offer['measurement_name_en'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_en;

                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                $request_product = OfferRequestProduct::query()->where('id', $offer_product->request_product_id)->first();
                $sale_offer['sequence'] = $request_product->sequence;

            }
            $contact = Contact::query()->where('id', $owner_id)->first();

            $quote_count = Quote::query()->where('sale_id', $sale_id)->count();
            if ($quote_count == 0){
                $quote_id = Uuid::uuid();
                Quote::query()->insert([
                    'quote_id' => $quote_id,
                    'sale_id' => $sale_id
                ]);
            }
            $quote = Quote::query()->where('sale_id', $sale_id)->first();


            // Create a new PDF instance
            $pdf = new \FPDF();
            $pdf->AddPage();

            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(true, 40);

            $pdf->AddFont('ChakraPetch-Regular', '', 'ChakraPetch-Regular.php');
            $pdf->AddFont('ChakraPetch-Bold', '', 'ChakraPetch-Bold.php');
            $pdf->SetFont('ChakraPetch-Bold', '', 12);


            // LOGO
            $pageWidth = $pdf->GetPageWidth();
            $x = $pageWidth - $contact->logo_width - 10;
            $pdf->Image(public_path($contact->logo), $x, 10, $contact->logo_width);  // Parameters: image file, x position, y position, width

            list($imageWidth, $imageHeight) = getimagesize(public_path($contact->logo));
            $actual_height = (int) ($contact->logo_width * $imageHeight / $imageWidth);

            //TARİH - KOD

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth(__('Date').': '.$document_date) - 10;
            $pdf->SetXY($x, $actual_height + 25);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Date').': '), '0', '0', '');
            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth($document_date) - 10;
            $pdf->SetXY($x, $actual_height + 25);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $document_date), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Bold', '', 11);
            $x = $pageWidth - $pdf->GetStringWidth($contact->short_code.'-CI-'.$sale->id) - 10;
            $pdf->SetXY($x, $actual_height + 32);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->short_code.'-CI-'.$sale->id), '0', '0', '');




            //COMPANY INFO

            $x = 10;
            $y = 15;

            $pdf->SetFont('ChakraPetch-Bold', '', 12);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->name), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);

            if ($contact->registration_no != '') {
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, __('Registration No').': ', '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Registration No').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, $contact->registration_no, '0', '0', '');

                if ($contact->registration_office != '' && App::getLocale() != 'en') {

                    $x = $x+5 + $pdf->GetStringWidth($contact->registration_no);

                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, __('Registration Office').': ', '0', '0', '');

                    $x = $x+2 + $pdf->GetStringWidth(__('Registration Office').': ');
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, $contact->registration_office, '0', '0', '');

                }
            }

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = 10;
            $y += 5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Address').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $lines = explode('<br>', $contact->address);
            foreach ($lines as $line) {
                $y += 5;
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $line), '0', '0', '');
            }

            $y += 5;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Phone').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->phone, '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Email').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->email, '0', '0', '');

            //TITLE

            $y += 10;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 20);
            $pdf->SetXY($x, $y);
            $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', __('Invoice'));
            $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
            $title = iconv('utf-8', 'iso-8859-9', $inputString);
            $pdf->Cell(0, 0, $title, '0', '0', '');

            //CUSTOMER INFO

            $y += 10;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Customer').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            if ($lang == 'tr') {
                $x = $x - 3 + $pdf->GetStringWidth(__('Customer') . ': ');
            }else{
                $x = $x+2 + $pdf->GetStringWidth(__('Customer') . ': ');
            }
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->name), '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Address').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Address').': ');
            $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $company->address);
            $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
            $address = iconv('utf-8', 'iso-8859-9', $inputString);
//            $pdf->Cell(0, 0, $address, '0', '0', '');
            $address_width = $pdf->GetStringWidth($address);
            $address_height = (((int)($address_width / 100)) + 1) * 2;

            if ($address_height == 2){
                $pdf->SetXY($x, $y);
            }else {
                $pdf->SetXY($x, $y - 2);
            }
            $pdf->MultiCell(100, $address_height, $address, 0, 'L');

            //QUOTES

            $y += 8;

            if ($quote->payment_term != null) {

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Payment Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-4 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->payment_term), '0', '0', '');

            }else if ($company->payment_term != null){

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Payment Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-4 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->payment_term), '0', '0', '');

            }

            if ($quote->delivery_term != null) {

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Delivery Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-3 + $pdf->GetStringWidth(__('Delivery Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Delivery Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->delivery_term), '0', '0', '');

            }

            if ($quote->country_of_destination != null) {

                $y += 5;
                $x = 10;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Country of Destination').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Country of Destination').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->country_of_destination), '0', '0', '');

            }


            $x = 10;
            $y += 10;
            $pdf->SetXY($x, $y);



// Set table header
            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->Cell(10, 12, 'N#', 0, 0, 'C');
            $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Ref. Code')), 0, 0, 'C');
            $pdf->Cell(50, 12, iconv('utf-8', 'iso-8859-9', __('Product Name')), 0, 0, 'C');
            $pdf->Cell(19, 12, iconv('utf-8', 'iso-8859-9', __('Qty')), 0, 0, 'C');
            $pdf->Cell(16, 12, iconv('utf-8', 'iso-8859-9', __('Unit')), 0, 0, 'C');
            $pdf->Cell(25, 12, iconv('utf-8', 'iso-8859-9', __('Unit Price')), 0, 0, 'C');
            $pdf->Cell(50, 12, iconv('utf-8', 'iso-8859-9', __('Total Price')), 0, 0, 'C');
            $pdf->Ln();
//            $pdf->Cell(20, 10, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
//            $lt_width = $pdf->GetStringWidth(__('Lead Time'));
//            if ($lt_width > 20){
//                $pdf->MultiCell(20, 6, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 'C');  // Move to the next line
//            }else{
//                $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
//                $pdf->Ln();
//            }



// Set table content
            $pdf->SetFont('ChakraPetch-Regular', '', 9);
            foreach ($sale_offers as $sale_offer) {
                if (App::getLocale() == 'tr'){
                    $measurement_name = $sale_offer->measurement_name_tr;
                }else{
                    $measurement_name = $sale_offer->measurement_name_en;
                }

                if ($sale_offer->offer_lead_time != '' && $sale_offer->offer_lead_time != null){
                    if ($sale_offer->offer_lead_time == 1) {
                        $lead_time = __('Stock');
                    } elseif (intval($sale_offer->offer_lead_time) % 7 == 0) {
                        $lead_time = (intval($sale_offer->offer_lead_time) / 7) . ' ' . __('Week');
                    } else {
                        $lead_time = $sale_offer->offer_lead_time . ' ' . __('Day');
                    }
                }else{
                    $lead_time = '';
                }

                $row_height = 15;
                $pdf->SetFont('ChakraPetch-Regular', '', 9);

                $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $sale_offer->product_name);
                $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
                $inputString = preg_replace('/[^\x20-\x7E]/u', '', $inputString);
                $product_name = iconv('utf-8', 'iso-8859-9', $inputString);

                $name_width = $pdf->GetStringWidth($product_name);
                if ($name_width > 48){
                    $wd = (($name_width / 48));
                    if ($name_width > 60){
                        $wd = (($name_width / 60));
                    }
                    if ($name_width > 100){
                        $wd = (($name_width / 50));
                    }
                    if ($name_width > 110){
                        $wd = (($name_width / 45));
                    }
                    if ($name_width > 200){
                        $wd = (($name_width / 40));
                    }
                    if ($wd >= 0 && $wd < 1){
                        $row_height = 15;
                    }else if ($wd >= 1 && $wd < 2){
                        $row_height = 7.5;
                    }else if ($wd >= 2 && $wd < 3){
                        $row_height = 5;
                    }else if ($wd >= 3 && $wd < 4){
                        $row_height = 3.75;
                    }else if ($wd >= 4 && $wd < 5){
                        $row_height = 3;
                    }else if ($wd >= 5){
                        $row_height = 2.5;
                    }

                }

                $pdf->setX(10);
                $pdf->Cell(10, 15, $sale_offer->sequence, 1, 0, 'C');
//                $pdf->Cell(10, 14, '', 1, 0, 'C');
                $pdf->Cell(20, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->product_ref_code), 1, 0, 'C');
//                $pdf->Cell(20, 14, iconv('utf-8', 'iso-8859-9', $row_height.' - '.$name_width), 1, 0, 'C');

                // Save the current X and Y position
                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();


                // Use MultiCell for product name with a width of 50mm
                $pdf->MultiCell(50, $row_height, $product_name, 'T', 'L');

                // Reset X and move Y to the saved position (next line)
                $pdf->SetXY($xPos+50, $yPos);

                // Output remaining cells for the current row
                $pdf->Cell(19, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_quantity), 1, 0, 'C');
                $pdf->Cell(16, 15, iconv('utf-8', 'iso-8859-9', $measurement_name), 1, 0, 'C');
                $pdf->Cell(25, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_pcs_price.' '.$currency), 1, 0, 'C');
                $pdf->Cell(50, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_price.' '.$currency), 1, 0, 'C');
                $pdf->Ln();
//                $pdf->Cell(20, 15, iconv('utf-8', 'iso-8859-9', $lead_time), 1, 1, 'C');  // Move to the next line
            }

            //TOTAL PRICES

            $x = 10;
            $y = $pdf->GetY();

            if ($sale->sub_total != null) {
                $title = __('Sub Total');
                if ($sale->vat == null || $sale->vat == '0.00' && $sale->freight == null) {
                    $title = __('Grand Total');
                }

                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper($title)), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->sub_total, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->freight != null) {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Freight'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->freight, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->vat != null && $sale->vat != '0.00') {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Vat'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->vat, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->grand_total != null) {
                if ($sale->vat != null && $sale->vat != '0.00' && $sale->freight != null) {
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Grand Total'))), 1, 0, 'R');

                    $pdf->SetXY($x + 140, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->grand_total, 2,",",".") . ' ' . $currency), 1, 0, 'C');

                    $y += 10;
                }
            }

            if ($sale->shipping_price != null) {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Shipping'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->shipping_price, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->grand_total_with_shipping != null) {
                if ($sale->shipping_price != null) {
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Grand Total'))), 1, 0, 'R');

                    $pdf->SetXY($x + 140, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->grand_total_with_shipping, 2,",",".") . ' ' . $currency), 1, 0, 'C');

                    $y += 10;
                }
            }


            //NOTE
            $pi_detail = ProformaInvoiceDetails::query()->where('sale_id', $sale_id)->first();
            if ($pi_detail) {
                if ($pi_detail->note != null) {
                    $y += 10;
                    $x = 10;
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 8);
                    $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Note')), 0, 0, '');

                    $y += 5;
                    $x = 10;
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 8);
                    $html = utf8_decode($pi_detail->note);
                    $html = str_replace('<br>', "\n", $html);
                    $html = str_replace('<p>', '', $html);
                    $html = str_replace('</p>', "\n", $html);
                    $pdf->MultiCell(0, 5, utf8_decode($html));
                }
            }


            //BANK INFO
            if ($bank_id != 0){

                $bank = OwnerBankInfo::query()->where('id', $bank_id)->first();
                $y += 10;
                $x = 10;
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 8);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Bank Details')), 0, 0, '');

                $x = 10;
                $pdf->SetFont('ChakraPetch-Regular', '', 8);
                $html = str_replace('<p>', '', $bank->detail);
                $html_array = explode('</p>', $html);
                foreach ($html_array as $item) {
                    $y += 5;
                    $pdf->SetXY($x, $y);
                    $inputString = mb_convert_encoding($item, 'ISO-8859-9', 'UTF-8');

                    $pdf->Cell(0, 0, $inputString, 0, 0, '');
                }

            }



            //FOOTER

            $pdfContent = $pdf->Output('created.pdf', 'S');

            $pdf = new Fpdi();
            $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            $numPages = $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            for ($pageNo = 1; $pageNo <= $numPages; $pageNo++) {
                $pdf->AddPage();

                $pdf->Image(public_path($contact->footer), 10, 260, 190);

                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx, 0, 0, null, null, true);
            }

            $filePath = public_path('img/document/' . $contact->short_code . '-CI-' . $sale->id . '.pdf');
            $pdf->Output($filePath, 'F');

            $fileUrl = 'img/document/' . $contact->short_code . '-CI-' . $sale->id . '.pdf';
            $fileName = $contact->short_code . '-CI-' . $sale->id . '.pdf';

            Document::query()->where('id', $document_id)->update([
                'file_url' => $fileUrl
            ]);

            return response([
                'message' => __('İşlem Başarılı.'),
                'status' => 'success',
                'object' => [
                    'file_url' => $fileUrl,
                    'file_name' => $fileName
                ]
            ]);


        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getGeneratePackingListInvoicePDF($lang, $owner_id, $packing_list_id, $bank_id)
    {
        try {
            App::setLocale($lang);

            $packing_list = PackingList::query()->where('packing_list_id', $packing_list_id)->first();
            $sale_id = $packing_list->sale_id;

            $sale = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->first();

            $currency = $sale->currency;

            $createdAt = Carbon::now();
            $document_date = $createdAt->format('d/m/Y');
            PackingList::query()->where('packing_list_id', $packing_list_id)->update([
                'pli_date' => $createdAt->format('Y-m-d')
            ]);

            $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale_id)->get();

            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
            $product_count = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
            $authorized_personnel = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
            $company = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
            $company_employee = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();

            $sale_offers = SaleOffer::query()
                ->leftJoin('packing_list_products', 'packing_list_products.sale_offer_id', '=', 'sale_offers.id')
                ->leftJoin('sales', 'sales.sale_id', '=', 'sale_offers.sale_id')
                ->selectRaw('sale_offers.*, packing_list_products.quantity as list_quantity')
                ->where('sale_offers.sale_id', $sale->sale_id)
                ->where('sale_offers.active', 1)
//                ->whereRaw("(sales.sale_id NOT IN (SELECT sale_id FROM sale_transactions))")
                ->where('packing_list_products.packing_list_id', $packing_list_id)
                ->get();
            $list_grand_total = 0;
            foreach ($sale_offers as $sale_offer){
                $sale_offer['supplier_name'] = Company::query()->where('id', $sale_offer->supplier_id)->first()->name;
                $sale_offer['product_name'] = Product::query()->where('id', $sale_offer->product_id)->first()->product_name;
                $sale_offer['product_ref_code'] = Product::query()->where('id', $sale_offer->product_id)->first()->ref_code;
                $offer_pcs_price = $sale_offer->offer_price / $sale_offer->offer_quantity;
                $sale_offer['offer_pcs_price'] = number_format($offer_pcs_price, 2,".","");
                $list_offer_price = $offer_pcs_price * $sale_offer->list_quantity;
                $list_grand_total += $list_offer_price;
                $sale_offer->offer_price = number_format($list_offer_price, 2,",",".");
                $sale_offer->pcs_price = number_format($sale_offer->pcs_price, 2,",",".");
                $sale_offer->total_price = number_format($sale_offer->total_price, 2,",",".");
                $sale_offer->discounted_price = number_format($sale_offer->discounted_price, 2,",",".");
                $sale_offer['measurement_name_tr'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_tr;
                $sale_offer['measurement_name_en'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_en;

                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                $request_product = OfferRequestProduct::query()->where('id', $offer_product->request_product_id)->first();
                $sale_offer['sequence'] = $request_product->sequence;

            }
            $sale['sale_offers'] = $sale_offers;
            $sale['list_total'] = number_format($list_grand_total, 2,",",".");
            $transaction = SaleTransaction::query()->where('packing_list_id', $packing_list_id)->where('active', 1)->first();
            $transaction_payment = SaleTransactionPayment::query()->where('transaction_id', $transaction->transaction_id)->where('active', 1)->first();
            $sale['list_tax'] = number_format($transaction_payment->payment_tax, 2,",",".");
            $sale['list_grand_total'] = number_format($transaction_payment->payment_total, 2,",",".");


            $contact = Contact::query()->where('id', $owner_id)->first();

            $quote_count = Quote::query()->where('sale_id', $sale_id)->count();
            if ($quote_count == 0){
                $quote_id = Uuid::uuid();
                Quote::query()->insert([
                    'quote_id' => $quote_id,
                    'sale_id' => $sale_id
                ]);
            }
            $quote = Quote::query()->where('sale_id', $sale_id)->first();


            // Create a new PDF instance
            $pdf = new \FPDF();
            $pdf->AddPage();

            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(true, 40);

            $pdf->AddFont('ChakraPetch-Regular', '', 'ChakraPetch-Regular.php');
            $pdf->AddFont('ChakraPetch-Bold', '', 'ChakraPetch-Bold.php');
            $pdf->SetFont('ChakraPetch-Bold', '', 12);


            // LOGO
            $pageWidth = $pdf->GetPageWidth();
            $x = $pageWidth - $contact->logo_width - 10;
            $pdf->Image(public_path($contact->logo), $x, 10, $contact->logo_width);  // Parameters: image file, x position, y position, width

            list($imageWidth, $imageHeight) = getimagesize(public_path($contact->logo));
            $actual_height = (int) ($contact->logo_width * $imageHeight / $imageWidth);

            //TARİH - KOD

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth(__('Date').': '.$document_date) - 10;
            $pdf->SetXY($x, $actual_height + 25);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Date').': '), '0', '0', '');
            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth($document_date) - 10;
            $pdf->SetXY($x, $actual_height + 25);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $document_date), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Bold', '', 11);
            $x = $pageWidth - $pdf->GetStringWidth($contact->short_code.'-CI-'.$sale->id) - 10;
            $pdf->SetXY($x, $actual_height + 32);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->short_code.'-CI-'.$sale->id), '0', '0', '');




            //COMPANY INFO

            $x = 10;
            $y = 15;

            $pdf->SetFont('ChakraPetch-Bold', '', 12);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->name), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);

            if ($contact->registration_no != '') {
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, __('Registration No').': ', '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Registration No').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, $contact->registration_no, '0', '0', '');

                if ($contact->registration_office != '' && App::getLocale() != 'en') {

                    $x = $x+5 + $pdf->GetStringWidth($contact->registration_no);

                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, __('Registration Office').': ', '0', '0', '');

                    $x = $x+2 + $pdf->GetStringWidth(__('Registration Office').': ');
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, $contact->registration_office, '0', '0', '');

                }
            }

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = 10;
            $y += 5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Address').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $lines = explode('<br>', $contact->address);
            foreach ($lines as $line) {
                $y += 5;
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $line), '0', '0', '');
            }

            $y += 5;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Phone').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->phone, '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Email').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->email, '0', '0', '');

            //TITLE

            $y += 10;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 20);
            $pdf->SetXY($x, $y);
            $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', __('Invoice'));
            $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
            $title = iconv('utf-8', 'iso-8859-9', $inputString);
            $pdf->Cell(0, 0, $title, '0', '0', '');

            //CUSTOMER INFO

            $y += 10;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Customer').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            if ($lang == 'tr') {
                $x = $x - 3 + $pdf->GetStringWidth(__('Customer') . ': ');
            }else{
                $x = $x+2 + $pdf->GetStringWidth(__('Customer') . ': ');
            }
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->name), '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Address').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Address').': ');
            $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $company->address);
            $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
            $address = iconv('utf-8', 'iso-8859-9', $inputString);
//            $pdf->Cell(0, 0, $address, '0', '0', '');
            $address_width = $pdf->GetStringWidth($address);
            $address_height = (((int)($address_width / 100)) + 1) * 2;

            if ($address_height == 2){
                $pdf->SetXY($x, $y);
            }else {
                $pdf->SetXY($x, $y - 2);
            }
            $pdf->MultiCell(100, $address_height, $address, 0, 'L');

            //QUOTES

            $y += 8;

            if ($quote->payment_term != null) {

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Payment Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-4 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->payment_term), '0', '0', '');

            }else if ($company->payment_term != null){

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Payment Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-4 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Payment Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->payment_term), '0', '0', '');

            }

            if ($quote->delivery_term != null) {

                $x = 10;
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Delivery Terms').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                if ($lang == 'tr') {
                    $x = $x-3 + $pdf->GetStringWidth(__('Delivery Terms').': ');
                }else{
                    $x = $x+2 + $pdf->GetStringWidth(__('Delivery Terms').': ');
                }
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->delivery_term), '0', '0', '');

            }

            if ($quote->country_of_destination != null) {

                $y += 5;
                $x = 10;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Country of Destination').': '), '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Country of Destination').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $quote->country_of_destination), '0', '0', '');

            }


            $x = 10;
            $y += 10;
            $pdf->SetXY($x, $y);



// Set table header
            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->Cell(10, 12, 'N#', 0, 0, 'C');
            $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Ref. Code')), 0, 0, 'C');
            $pdf->Cell(50, 12, iconv('utf-8', 'iso-8859-9', __('Product Name')), 0, 0, 'C');
            $pdf->Cell(19, 12, iconv('utf-8', 'iso-8859-9', __('Qty')), 0, 0, 'C');
            $pdf->Cell(16, 12, iconv('utf-8', 'iso-8859-9', __('Unit')), 0, 0, 'C');
            $pdf->Cell(25, 12, iconv('utf-8', 'iso-8859-9', __('Unit Price')), 0, 0, 'C');
            $pdf->Cell(50, 12, iconv('utf-8', 'iso-8859-9', __('Total Price')), 0, 0, 'C');
            $pdf->Ln();
//            $pdf->Cell(20, 10, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
//            $lt_width = $pdf->GetStringWidth(__('Lead Time'));
//            if ($lt_width > 20){
//                $pdf->MultiCell(20, 6, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 'C');  // Move to the next line
//            }else{
//                $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
//                $pdf->Ln();
//            }



// Set table content
            $pdf->SetFont('ChakraPetch-Regular', '', 9);
            foreach ($sale_offers as $sale_offer) {
                if (App::getLocale() == 'tr'){
                    $measurement_name = $sale_offer->measurement_name_tr;
                }else{
                    $measurement_name = $sale_offer->measurement_name_en;
                }

                if ($sale_offer->offer_lead_time != '' && $sale_offer->offer_lead_time != null){
                    if ($sale_offer->offer_lead_time == 1) {
                        $lead_time = __('Stock');
                    } elseif (intval($sale_offer->offer_lead_time) % 7 == 0) {
                        $lead_time = (intval($sale_offer->offer_lead_time) / 7) . ' ' . __('Week');
                    } else {
                        $lead_time = $sale_offer->offer_lead_time . ' ' . __('Day');
                    }
                }else{
                    $lead_time = '';
                }

                $row_height = 15;
                $pdf->SetFont('ChakraPetch-Regular', '', 9);

                $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $sale_offer->product_name);
                $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
                $inputString = preg_replace('/[^\x20-\x7E]/u', '', $inputString);
                $product_name = iconv('utf-8', 'iso-8859-9', $inputString);

                $name_width = $pdf->GetStringWidth($product_name);
                if ($name_width > 48){
                    $wd = (($name_width / 48));
                    if ($name_width > 60){
                        $wd = (($name_width / 60));
                    }
                    if ($name_width > 100){
                        $wd = (($name_width / 50));
                    }
                    if ($name_width > 110){
                        $wd = (($name_width / 45));
                    }
                    if ($name_width > 200){
                        $wd = (($name_width / 40));
                    }
                    if ($wd >= 0 && $wd < 1){
                        $row_height = 15;
                    }else if ($wd >= 1 && $wd < 2){
                        $row_height = 7.5;
                    }else if ($wd >= 2 && $wd < 3){
                        $row_height = 5;
                    }else if ($wd >= 3 && $wd < 4){
                        $row_height = 3.75;
                    }else if ($wd >= 4 && $wd < 5){
                        $row_height = 3;
                    }else if ($wd >= 5){
                        $row_height = 2.5;
                    }

                }

                $pdf->setX(10);
                $pdf->Cell(10, 15, $sale_offer->sequence, 1, 0, 'C');
//                $pdf->Cell(10, 14, '', 1, 0, 'C');
                $pdf->Cell(20, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->product_ref_code), 1, 0, 'C');
//                $pdf->Cell(20, 14, iconv('utf-8', 'iso-8859-9', $row_height.' - '.$name_width), 1, 0, 'C');

                // Save the current X and Y position
                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();


                // Use MultiCell for product name with a width of 50mm
                $pdf->MultiCell(50, $row_height, $product_name, 'T', 'L');

                // Reset X and move Y to the saved position (next line)
                $pdf->SetXY($xPos+50, $yPos);

                // Output remaining cells for the current row
                $pdf->Cell(19, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_quantity), 1, 0, 'C');
                $pdf->Cell(16, 15, iconv('utf-8', 'iso-8859-9', $measurement_name), 1, 0, 'C');
                $pdf->Cell(25, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_pcs_price.' '.$currency), 1, 0, 'C');
                $pdf->Cell(50, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_price.' '.$currency), 1, 0, 'C');
                $pdf->Ln();
//                $pdf->Cell(20, 15, iconv('utf-8', 'iso-8859-9', $lead_time), 1, 1, 'C');  // Move to the next line
            }

            //TOTAL PRICES

            $x = 10;
            $y = $pdf->GetY();


            if ($sale->list_total != null) {
                $title = __('Sub Total');
                if ($sale->list_grand_total == '0,00' && $sale->list_tax == '0,00') {
                    $title = __('Grand Total');
                }

                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper($title)), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->list_total, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($sale->list_grand_total == '0,00' && $sale->list_tax == '0,00') {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Vat'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->list_tax, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;


                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Grand Total'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($sale->list_grand_total, 2,",",".") . ' ' . $currency), 1, 0, 'C');

                $y += 10;
            }


            //NOTE
            $pi_detail = ProformaInvoiceDetails::query()->where('sale_id', $sale_id)->first();
            if ($pi_detail) {
                if ($pi_detail->note != null) {
                    $y += 10;
                    $x = 10;
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 8);
                    $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Note')), 0, 0, '');

                    $y += 5;
                    $x = 10;
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 8);
                    $html = utf8_decode($pi_detail->note);
                    $html = str_replace('<br>', "\n", $html);
                    $html = str_replace('<p>', '', $html);
                    $html = str_replace('</p>', "\n", $html);
                    $pdf->MultiCell(0, 5, utf8_decode($html));
                }
            }


            //BANK INFO
            if ($bank_id != 0){

                $bank = OwnerBankInfo::query()->where('id', $bank_id)->first();
                $y += 10;
                $x = 10;
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 8);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Bank Details')), 0, 0, '');

                $x = 10;
                $pdf->SetFont('ChakraPetch-Regular', '', 8);
                $html = str_replace('<p>', '', $bank->detail);
                $html_array = explode('</p>', $html);
                foreach ($html_array as $item) {
                    $y += 5;
                    $pdf->SetXY($x, $y);
                    $inputString = mb_convert_encoding($item, 'ISO-8859-9', 'UTF-8');

                    $pdf->Cell(0, 0, $inputString, 0, 0, '');
                }

            }



            //FOOTER

            $pdfContent = $pdf->Output('created.pdf', 'S');

            $pdf = new Fpdi();
            $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            $numPages = $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            for ($pageNo = 1; $pageNo <= $numPages; $pageNo++) {
                $pdf->AddPage();

                $pdf->Image(public_path($contact->footer), 10, 260, 190);

                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx, 0, 0, null, null, true);
            }

            $filePath = public_path('img/document/' . $contact->short_code . '-CI-' . $sale->id . '-'. $packing_list->id .'.pdf');
            $pdf->Output($filePath, 'F');

            $fileUrl = 'img/document/' . $contact->short_code . '-CI-' . $sale->id . '-'. $packing_list->id .'.pdf';
            $fileName = $contact->short_code . '-CI-' . $sale->id . '-'. $packing_list->id .'.pdf';

            PackingList::query()->where('packing_list_id', $packing_list_id)->update([
                'pli_url' => $fileUrl
            ]);

            return response([
                'message' => __('İşlem Başarılı.'),
                'status' => 'success',
                'object' => [
                    'file_url' => $fileUrl,
                    'file_name' => $fileName
                ]
            ]);


        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getGeneratePurchasingOfferPDF($lang, $owner_id, $sale_id, $offer_id)
    {
        try {
            App::setLocale($lang);

            $offer = Offer::query()
                ->leftJoin('companies', 'companies.id', '=', 'offers.supplier_id')
                ->selectRaw('offers.*, companies.name as company_name')
                ->where('offers.offer_id',$offer_id)
                ->where('offers.active',1)
                ->first();

            $sale = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->first();


            $createdAt = Carbon::now();
            $document_date = $createdAt->format('d/m/Y');
            Offer::query()->where('offer_id', $offer_id)->update([
                'po_date' => $createdAt->format('Y-m-d')
            ]);

            $global_id = $sale->id;
            $company = Company::query()
                ->leftJoin('countries', 'countries.id', '=', 'companies.country_id')
                ->selectRaw('companies.*, countries.lang as country_lang')
                ->where('companies.id', $offer->supplier_id)
                ->first();
            $contact = Contact::query()->where('id', $owner_id)->first();

            $products = SaleOffer::query()
                ->leftJoin('offer_products', 'offer_products.id', '=', 'sale_offers.offer_product_id')
                ->selectRaw('offer_products.*')
                ->where('offer_products.offer_id', $offer->offer_id)
                ->where('offer_products.active', 1)
                ->where('sale_offers.active', 1)
                ->get();

            $offer_sub_total = 0;
            $offer_vat = 0;
            $offer_grand_total = 0;
            foreach ($products as $product){
                $offer_request_product = OfferRequestProduct::query()->where('id', $product->request_product_id)->first();
                $product_detail = Product::query()->where('id', $offer_request_product->product_id)->first();
                $product['ref_code'] = $product_detail->ref_code;
                $product['product_name'] = $product_detail->product_name;
                $vat = $product->total_price / 100 * $product->vat_rate;
                $product['vat'] = number_format($vat, 2,".","");
                $product['grand_total'] = number_format($product->total_price + $vat, 2,".","");

                $offer_sub_total += $product->total_price;
                $offer_vat += $vat;
                $offer_grand_total += $product->total_price + $vat;
                $product['measurement_name_tr'] = Measurement::query()->where('id', $product->measurement_id)->first()->name_tr;
                $product['measurement_name_en'] = Measurement::query()->where('id', $product->measurement_id)->first()->name_en;

                $product['sequence'] = $offer_request_product->sequence;
            }



            // Create a new PDF instance
            $pdf = new \FPDF();
            $pdf->AddPage();

            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(true, 40);

            $pdf->AddFont('ChakraPetch-Regular', '', 'ChakraPetch-Regular.php');
            $pdf->AddFont('ChakraPetch-Bold', '', 'ChakraPetch-Bold.php');
            $pdf->SetFont('ChakraPetch-Bold', '', 12);


            // LOGO
            $pageWidth = $pdf->GetPageWidth();
            $actual_height = $this->addOwnerLogo($pdf, $contact, $pageWidth);

            //TARİH - KOD
            $this->addDateAndCode($pdf, $document_date, $contact, $actual_height, $sale->id, $pageWidth, 'PO');

            //COMPANY INFO
            $y = $this->addOwnerInfo($pdf, $contact);


            //TITLE
            $y = $this->addPdfTitle($pdf, $this->textConvert(__('Purchasing Order')), $y);

            //CUSTOMER INFO
            $y = $this->addCompanyInfo($pdf, $lang, $company, $y);



            $x = 10;
            $y += 10;
            $pdf->SetXY($x, $y);



// Set table header
            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->Cell(10, 12, 'N#', 0, 0, 'C');
            $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Ref. Code')), 0, 0, 'C');
            $pdf->Cell(50, 12, iconv('utf-8', 'iso-8859-9', __('Product Name')), 0, 0, 'C');
            $pdf->Cell(19, 12, iconv('utf-8', 'iso-8859-9', __('Qty')), 0, 0, 'C');
            $pdf->Cell(16, 12, iconv('utf-8', 'iso-8859-9', __('Unit')), 0, 0, 'C');
            $pdf->Cell(25, 12, iconv('utf-8', 'iso-8859-9', __('Unit Price')), 0, 0, 'C');
            $pdf->Cell(30, 12, iconv('utf-8', 'iso-8859-9', __('Total Price')), 0, 0, 'C');
//            $pdf->Cell(20, 10, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
            $lt_width = $pdf->GetStringWidth(__('Lead Time'));
            if ($lt_width > 20){
                $pdf->MultiCell(20, 6, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 'C');  // Move to the next line
            }else{
                $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
                $pdf->Ln();
            }



// Set table content
            $pdf->SetFont('ChakraPetch-Regular', '', 9);
            $x = 10;
            $y += 12;
            $pdf->SetXY($x, $y);
            $i = 1;
            $currency = "";
            foreach ($products as $product) {

                $currency = $product->currency;

                if ($lang == 'tr'){
                    $measurement_name = $product->measurement_name_tr;
                }else{
                    $measurement_name = $product->measurement_name_en;
                }

                $pdf->SetFont('ChakraPetch-Regular', '', 9);

                $x = 40;
                $pdf->SetXY($x, $pdf->GetY());

                $product_name = $this->textConvert($product->product_name);
                $name_width = $pdf->GetStringWidth($product_name);
                $lines_needed = ceil($name_width / 50);
                $line_height = 8;
                if ($lines_needed > 1){
                    $line_height = 5;
                }
                $row_height = $lines_needed * $line_height;
                $pdf->MultiCell(50, $line_height, $product_name, 1, 'L');


                $x = 10;
                $line_y = $pdf->GetY() - $row_height;
                $pdf->SetXY($x, $line_y);
                $pdf->Cell(10, $row_height, $i, 1, 0, 'C');
                $pdf->Cell(20, $row_height, iconv('utf-8', 'iso-8859-9', $product->product_ref_code), 1, 0, 'C');

                $x = 90;
                $pdf->SetXY($x, $line_y);
                $pdf->Cell(19, $row_height, iconv('utf-8', 'iso-8859-9', $product->offer_quantity), 1, 0, 'C');
                $pdf->Cell(16, $row_height, iconv('utf-8', 'iso-8859-9', $measurement_name), 1, 0, 'C');
                $pdf->Cell(25, $row_height, iconv('utf-8', 'iso-8859-9', number_format($product->pcs_price, 2,",",".").' '.$product->currency), 1, 0, 'C');
                $pdf->Cell(30, $row_height, iconv('utf-8', 'iso-8859-9', number_format($product->total_price, 2,",",".").' '.$product->currency), 1, 0, 'C');
                $pdf->Cell(20, $row_height, iconv('utf-8', 'iso-8859-9', $this->leadtime($product->lead_time)), 1, 1, 'C');


                $i++;
            }

            //TOTAL PRICES

            $x = 10;
            $y = $pdf->GetY();

            if ($offer_sub_total != null) {
                $title = __('Sub Total');
                if ($offer_vat == null || $offer_vat == '0.00') {
                    $title = __('Grand Total');
                }

                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper($title)), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($offer_sub_total, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($offer_vat != null && $offer_vat != '0.00') {
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Vat'))), 1, 0, 'R');

                $pdf->SetXY($x + 140, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($offer_vat, 2,",",".").' '.$currency), 1, 0, 'C');

                $y += 10;
            }

            if ($offer_grand_total != null) {
                if ($offer_vat != null && $offer_vat != '0.00') {
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->Cell(140, 10, iconv('utf-8', 'iso-8859-9', strtoupper(__('Grand Total'))), 1, 0, 'R');

                    $pdf->SetXY($x + 140, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->Cell(50, 10, iconv('utf-8', 'iso-8859-9', number_format($offer_grand_total, 2,",",".") . ' ' . $currency), 1, 0, 'C');

                    $y += 10;
                }
            }


            //NOTE
            $po_detail = PurchasingOrderDetails::query()->where('offer_id', $offer_id)->first();
            if ($po_detail) {
                if ($po_detail->note != null) {
                    $y += 10;
                    $x = 10;
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 8);
                    $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Note')), 0, 0, '');

                    $y += 5;
                    $x = 10;
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 8);
                    $html = utf8_decode($po_detail->note);
                    $html = str_replace('<br>', "\n", $html);
                    $html = str_replace('<p>', '', $html);
                    $html = str_replace('</p>', "\n", $html);
                    $pdf->MultiCell(0, 5, utf8_decode($html));
                }
            }


            //SIGNATURES

            $y += 20;
            $x = 10;
            $pdf->SetXY($x, $y);
            $pdf->SetFont('ChakraPetch-Bold', '', 8);
            $pdf->Cell(70, 0, iconv('utf-8', 'iso-8859-9', __('Authorised Signature')), 0, 0, 'C');
            $x = 130;
            $pdf->SetXY($x, $y);
            $pdf->SetFont('ChakraPetch-Bold', '', 8);
            $pdf->Cell(70, 0, iconv('utf-8', 'iso-8859-9', __('Supplier Confirmation')), 0, 0, 'C');

            //Signature
            $height = 20;
            $imagePath = public_path($contact->signature);
            list($originalWidth, $originalHeight) = getimagesize($imagePath);
            $aspectRatio = $originalWidth / $originalHeight;
            $width = $height * $aspectRatio;
            $y += 1;
            $x = 10 + ((70-$width)/2);
            $pdf->Image($imagePath, $x, $y, $width, $height);

            $y += 20;
            $x = 10;
            $pdf->SetXY($x, $y);
            $pdf->SetFont('ChakraPetch-Bold', '', 8);
            $pdf->Cell(70, 0, iconv('utf-8', 'iso-8859-9', $contact->authorized_name), 0, 0, 'C');

            $y += 3;
            $x = 10;
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->Line($x, $y, $x+70, $y);
            $x = 130;
            $pdf->Line($x, $y, $x+70, $y);

            $y += 3;
            $x = 10;
            $text1 = __('Name Surname')." / ".__('Signature');
            $text2 = __('Name Surname')." / ".__('Signature')." / ".__('Date');
            $pdf->SetXY($x, $y);
            $pdf->SetFont('ChakraPetch-Bold', '', 8);
            $pdf->Cell(70, 0, iconv('utf-8', 'iso-8859-9', $text1), 0, 0, 'C');
            $x = 130;
            $pdf->SetXY($x, $y);
            $pdf->SetFont('ChakraPetch-Bold', '', 8);
            $pdf->Cell(70, 0, iconv('utf-8', 'iso-8859-9', $text2), 0, 0, 'C');


            //FOOTER

            $pdfContent = $pdf->Output('created.pdf', 'S');

            $pdf = new Fpdi();
            $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            $numPages = $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            for ($pageNo = 1; $pageNo <= $numPages; $pageNo++) {
                $pdf->AddPage();

                $pdf->Image(public_path($contact->footer), 10, 260, 190);

                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx, 0, 0, null, null, true);
            }

            $filePath = public_path('img/document/' . $contact->short_code . '-PO-' . $sale->id . '-'. $offer->id .'.pdf');
            $pdf->Output($filePath, 'F');

            $fileUrl = 'img/document/' . $contact->short_code . '-PO-' . $sale->id . '-'. $offer->id .'.pdf';
            $fileName = $contact->short_code . '-PO-' . $sale->id . '-'. $offer->id .'.pdf';

            Offer::query()->where('offer_id', $offer_id)->update([
                'po_url' => $fileUrl
            ]);

            return response([
                'message' => __('İşlem Başarılı.'),
                'status' => 'success',
                'object' => [
                    'file_url' => $fileUrl,
                    'file_name' => $fileName
                ]
            ]);


        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getGenerateRfqPDF($lang, $owner_id, $offer_id)
    {
        try {
            App::setLocale($lang);

            $offer = Offer::query()
                ->leftJoin('companies', 'companies.id', '=', 'offers.supplier_id')
                ->selectRaw('offers.*, companies.name as company_name')
                ->where('offers.offer_id',$offer_id)
                ->where('offers.active',1)
                ->first();

            $sale = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.request_id',$offer->request_id)
                ->first();


            $createdAt = Carbon::now();
            $document_date = $createdAt->format('d/m/Y');
            Offer::query()->where('offer_id', $offer_id)->update([
                'rfq_date' => $createdAt->format('Y-m-d')
            ]);

            $company = Company::query()
                ->leftJoin('countries', 'countries.id', '=', 'companies.country_id')
                ->selectRaw('companies.*, countries.lang as country_lang')
                ->where('companies.id', $offer->supplier_id)
                ->first();

            $products = OfferProduct::query()->where('offer_id', $offer->offer_id)->where('active', 1)->get();
            $offer_sub_total = 0;
            $offer_vat = 0;
            $offer_grand_total = 0;
            foreach ($products as $product){
                $offer_request_product = OfferRequestProduct::query()->where('id', $product->request_product_id)->first();
                $product_detail = Product::query()->where('id', $offer_request_product->product_id)->first();
                $product['ref_code'] = $product_detail->ref_code;
                $product['product_name'] = $product_detail->product_name;
                $vat = $product->total_price / 100 * $product->vat_rate;
                $product['vat'] = number_format($vat, 2,".","");
                $product['grand_total'] = number_format($product->total_price + $vat, 2,".","");

                $offer_sub_total += $product->total_price;
                $offer_vat += $vat;
                $offer_grand_total += $product->total_price + $vat;
                $product['measurement_name_tr'] = Measurement::query()->where('id', $product->measurement_id)->first()->name_tr;
                $product['measurement_name_en'] = Measurement::query()->where('id', $product->measurement_id)->first()->name_en;

                $product->discount_rate = number_format($product->discount_rate, 2,",",".");
                $product->discounted_price = number_format($product->discounted_price, 2,",",".");
                $product->grand_total = number_format($product->grand_total, 2,",",".");
                $product->total_price = number_format($product->total_price, 2,",",".");
                $product->pcs_price = number_format($product->pcs_price, 2,",",".");
                $product->vat_rate = number_format($product->vat_rate, 2,",",".");
            }


            $contact = Contact::query()->where('id', $owner_id)->first();


            // Create a new PDF instance
            $pdf = new \FPDF();
            $pdf->AddPage();

            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(true, 40);

            $pdf->AddFont('ChakraPetch-Regular', '', 'ChakraPetch-Regular.php');
            $pdf->AddFont('ChakraPetch-Bold', '', 'ChakraPetch-Bold.php');
            $pdf->SetFont('ChakraPetch-Bold', '', 12);


            // LOGO
            $pageWidth = $pdf->GetPageWidth();
            $x = $pageWidth - $contact->logo_width - 10;
            $pdf->Image(public_path($contact->logo), $x, 10, $contact->logo_width);  // Parameters: image file, x position, y position, width

            list($imageWidth, $imageHeight) = getimagesize(public_path($contact->logo));
            $actual_height = (int) ($contact->logo_width * $imageHeight / $imageWidth);

            //TARİH - KOD

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth(__('Date').': '.$document_date) - 10;
            $pdf->SetXY($x, $actual_height + 25);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Date').': '), '0', '0', '');
            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth($document_date) - 10;
            $pdf->SetXY($x, $actual_height + 25);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $document_date), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Bold', '', 11);
            $x = $pageWidth - $pdf->GetStringWidth($contact->short_code.'-RFQ-'.$sale->id) - 10;
            $pdf->SetXY($x, $actual_height + 32);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->short_code.'-RFQ-'.$sale->id), '0', '0', '');




            //COMPANY INFO

            $x = 10;
            $y = 15;

            $pdf->SetFont('ChakraPetch-Bold', '', 12);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->name), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);

            if ($contact->registration_no != '') {
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, __('Registration No').': ', '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Registration No').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, $contact->registration_no, '0', '0', '');

                if ($contact->registration_office != '' && App::getLocale() != 'en') {

                    $x = $x+5 + $pdf->GetStringWidth($contact->registration_no);

                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, __('Registration Office').': ', '0', '0', '');

                    $x = $x+2 + $pdf->GetStringWidth(__('Registration Office').': ');
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, $contact->registration_office, '0', '0', '');

                }
            }

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = 10;
            $y += 5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Address').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $lines = explode('<br>', $contact->address);
            foreach ($lines as $line) {
                $y += 5;
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $line), '0', '0', '');
            }

            $y += 5;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Phone').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->phone, '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Email').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->email, '0', '0', '');

            //TITLE

            $y += 10;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 20);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Request For Quotation'), '0', '0', '');

            //CUSTOMER INFO

            $y += 10;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Supplier').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            if ($lang == 'tr') {
                $x = $x - 3 + $pdf->GetStringWidth(__('Supplier') . ': ');
            }else{
                $x = $x+2 + $pdf->GetStringWidth(__('Supplier') . ': ');
            }
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->name), '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Address').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Address').': ');
            $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $company->address);
            $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
            $address = iconv('utf-8', 'iso-8859-9', $inputString);
//            $pdf->Cell(0, 0, $address, '0', '0', '');
            $address_width = $pdf->GetStringWidth($address);
            $address_height = (((int)($address_width / 100)) + 1) * 2;

            if ($address_height == 2){
                $pdf->SetXY($x, $y);
            }else {
                $pdf->SetXY($x, $y - 2);
            }
            $pdf->MultiCell(100, $address_height, $address, 0, 'L');



            $y += 13;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Phone').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $company->phone, '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Email').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $company->email, '0', '0', '');



            $x = 10;
            $y += 10;
            $pdf->SetXY($x, $y);



// Set table header
            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->Cell(10, 12, 'N#', 0, 0, 'C');
            $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Ref. Code')), 0, 0, 'C');
            $pdf->Cell(50, 12, iconv('utf-8', 'iso-8859-9', __('Product Name')), 0, 0, 'C');
            $pdf->Cell(19, 12, iconv('utf-8', 'iso-8859-9', __('Qty')), 0, 0, 'C');
            $pdf->Cell(16, 12, iconv('utf-8', 'iso-8859-9', __('Unit')), 0, 0, 'C');
            $pdf->Cell(25, 12, iconv('utf-8', 'iso-8859-9', __('Unit Price')), 0, 0, 'C');
            $pdf->Cell(30, 12, iconv('utf-8', 'iso-8859-9', __('Total Price')), 0, 0, 'C');
//            $pdf->Cell(20, 10, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
            $lt_width = $pdf->GetStringWidth(__('Lead Time'));
            if ($lt_width > 20){
                $pdf->MultiCell(20, 6, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 'C');  // Move to the next line
            }else{
                $pdf->Cell(20, 12, iconv('utf-8', 'iso-8859-9', __('Lead Time')), 0, 0, 'C');
                $pdf->Ln();
            }



// Set table content
            $pdf->SetFont('ChakraPetch-Regular', '', 9);
            $i = 1;
            foreach ($products as $product) {
                if (App::getLocale() == 'tr'){
                    $measurement_name = $product->measurement_name_tr;
                }else{
                    $measurement_name = $product->measurement_name_en;
                }

                $row_height = 15;
                $pdf->SetFont('ChakraPetch-Regular', '', 9);

                $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $product->product_name);
                $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
                $inputString = preg_replace('/[^\x20-\x7E]/u', '', $inputString);
                $product_name = iconv('utf-8', 'iso-8859-9', $inputString);

                $name_width = $pdf->GetStringWidth($product_name);
                if ($name_width > 48){
                    $wd = (($name_width / 48));
                    if ($name_width > 60){
                        $wd = (($name_width / 60));
                    }
                    if ($name_width > 100){
                        $wd = (($name_width / 50));
                    }
                    if ($name_width > 110){
                        $wd = (($name_width / 45));
                    }
                    if ($name_width > 200){
                        $wd = (($name_width / 40));
                    }
                    if ($wd >= 0 && $wd < 1){
                        $row_height = 15;
                    }else if ($wd >= 1 && $wd < 2){
                        $row_height = 7.5;
                    }else if ($wd >= 2 && $wd < 3){
                        $row_height = 5;
                    }else if ($wd >= 3 && $wd < 4){
                        $row_height = 3.75;
                    }else if ($wd >= 4 && $wd < 5){
                        $row_height = 3;
                    }else if ($wd >= 5){
                        $row_height = 2.5;
                    }

                }

                $pdf->setX(10);
                $pdf->Cell(10, 15, $i, 1, 0, 'C');
//                $pdf->Cell(10, 14, '', 1, 0, 'C');
                $pdf->Cell(20, 15, iconv('utf-8', 'iso-8859-9', $product->ref_code), 1, 0, 'C');
//                $pdf->Cell(20, 14, iconv('utf-8', 'iso-8859-9', $row_height.' - '.$name_width), 1, 0, 'C');

                // Save the current X and Y position
                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();


                // Use MultiCell for product name with a width of 50mm
                $pdf->MultiCell(50, $row_height, $product_name, 'T', 'L');

                // Reset X and move Y to the saved position (next line)
                $pdf->SetXY($xPos+50, $yPos);

                // Output remaining cells for the current row
                $pdf->Cell(19, 15, iconv('utf-8', 'iso-8859-9', $product->quantity), 1, 0, 'C');
                $pdf->Cell(16, 15, iconv('utf-8', 'iso-8859-9', $measurement_name), 1, 0, 'C');
                $pdf->Cell(25, 15, iconv('utf-8', 'iso-8859-9', ''), 1, 0, 'C');
                $pdf->Cell(30, 15, iconv('utf-8', 'iso-8859-9', ''), 1, 0, 'C');
                $pdf->Cell(20, 15, iconv('utf-8', 'iso-8859-9', ''), 1, 1, 'C');  // Move to the next line

                $i++;
            }

            //TOTAL PRICES

            $x = 10;
            $y = $pdf->GetY();


            //NOTE
            $rfq_detail = RfqDetails::query()->where('offer_id', $offer_id)->first();
            if ($rfq_detail) {
                if ($rfq_detail->note != null) {
                    $y += 10;
                    $x = 10;
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Bold', '', 8);
                    $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Note')), 0, 0, '');

                    $y += 5;
                    $x = 10;
                    $pdf->SetXY($x, $y);
                    $pdf->SetFont('ChakraPetch-Regular', '', 8);
                    $html = utf8_decode($rfq_detail->note);
                    $html = str_replace('<br>', "\n", $html);
                    $html = str_replace('<p>', '', $html);
                    $html = str_replace('</p>', "\n", $html);
                    $pdf->MultiCell(0, 5, utf8_decode($html));
                }
            }



            //FOOTER

            $pdfContent = $pdf->Output('created.pdf', 'S');

            $pdf = new Fpdi();
            $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            $numPages = $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            for ($pageNo = 1; $pageNo <= $numPages; $pageNo++) {
                $pdf->AddPage();

                $pdf->Image(public_path($contact->footer), 10, 260, 190);

                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx, 0, 0, null, null, true);
            }

            $filePath = public_path('img/document/' . $contact->short_code . '-RFQ-' . $sale->id . '-'. $offer->id .'.pdf');
            $pdf->Output($filePath, 'F');

            $fileUrl = 'img/document/' . $contact->short_code . '-RFQ-' . $sale->id . '-'. $offer->id .'.pdf';
            $fileName = $contact->short_code . '-RFQ-' . $sale->id . '-'. $offer->id .'.pdf';

            Offer::query()->where('offer_id', $offer_id)->update([
                'rfq_url' => $fileUrl
            ]);

            return response([
                'message' => __('İşlem Başarılı.'),
                'status' => 'success',
                'object' => [
                    'file_url' => $fileUrl,
                    'file_name' => $fileName
                ]
            ]);


        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getGeneratePackingListPDF($lang, $owner_id, $packing_list_id)
    {
        try {
            App::setLocale($lang);
            $packing_list = PackingList::query()->where('packing_list_id', $packing_list_id)->first();
            $sale_id = $packing_list->sale_id;

            $sale = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->first();

            $currency = $sale->currency;

            $createdAt = Carbon::now();
            $document_date = $createdAt->format('d/m/Y');
            PackingList::query()->where('packing_list_id', $packing_list_id)->update([
                'pl_date' => $createdAt->format('Y-m-d')
            ]);

            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
            $product_count = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
            $authorized_personnel = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
            $company = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
            $company_employee = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();

            $sale_offers = SaleOffer::query()
                ->join('packing_list_products', 'packing_list_products.sale_offer_id', '=', 'sale_offers.id')
                ->selectRaw('sale_offers.*')
                ->where('sale_offers.sale_id', $sale->sale_id)
                ->where('packing_list_products.packing_list_id', $packing_list_id)
                ->where('sale_offers.active', 1)
                ->get();

            foreach ($sale_offers as $sale_offer){
                $sale_offer['supplier_name'] = Company::query()->where('id', $sale_offer->supplier_id)->first()->name;
                $sale_offer['product_name'] = Product::query()->where('id', $sale_offer->product_id)->first()->product_name;
                $sale_offer['product_ref_code'] = Product::query()->where('id', $sale_offer->product_id)->first()->ref_code;
                $offer_pcs_price = $sale_offer->offer_price / $sale_offer->offer_quantity;
                $sale_offer['offer_pcs_price'] = number_format($offer_pcs_price, 2,".","");
                $sale_offer->offer_price = number_format($sale_offer->offer_price, 2,",",".");
                $sale_offer->pcs_price = number_format($sale_offer->pcs_price, 2,",",".");
                $sale_offer->total_price = number_format($sale_offer->total_price, 2,",",".");
                $sale_offer->discounted_price = number_format($sale_offer->discounted_price, 2,",",".");
                $sale_offer['measurement_name_tr'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_tr;
                $sale_offer['measurement_name_en'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_en;

                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                $request_product = OfferRequestProduct::query()->where('id', $offer_product->request_product_id)->first();
                $sale_offer['sequence'] = $request_product->sequence;

                $sale_offer['packing_count'] = PackingListProduct::query()
                    ->where('active', 1)
                    ->where('sale_offer_id', $sale_offer->id)
                    ->where('packing_list_id', $packing_list->packing_list_id)
                    ->first()
                    ->quantity;

            }
            $contact = Contact::query()->where('id', $owner_id)->first();


            // Create a new PDF instance
            $pdf = new \FPDF();
            $pdf->AddPage();

            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(true, 40);

            $pdf->AddFont('ChakraPetch-Regular', '', 'ChakraPetch-Regular.php');
            $pdf->AddFont('ChakraPetch-Bold', '', 'ChakraPetch-Bold.php');
            $pdf->SetFont('ChakraPetch-Bold', '', 12);


            // LOGO
            $pageWidth = $pdf->GetPageWidth();
            $x = $pageWidth - $contact->logo_width - 10;
            $pdf->Image(public_path($contact->logo), $x, 10, $contact->logo_width);  // Parameters: image file, x position, y position, width

            list($imageWidth, $imageHeight) = getimagesize(public_path($contact->logo));
            $actual_height = (int) ($contact->logo_width * $imageHeight / $imageWidth);

            //TARİH - KOD

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth(__('Date').': '.$document_date) - 10;
            $pdf->SetXY($x, $actual_height + 25);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Date').': '), '0', '0', '');
            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth($document_date) - 10;
            $pdf->SetXY($x, $actual_height + 25);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $document_date), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Bold', '', 11);
            $x = $pageWidth - $pdf->GetStringWidth($contact->short_code.'-PL-'.$sale->id) - 10;
            $pdf->SetXY($x, $actual_height + 32);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->short_code.'-PL-'.$sale->id), '0', '0', '');




            //COMPANY INFO

            $x = 10;
            $y = 15;

            $pdf->SetFont('ChakraPetch-Bold', '', 12);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->name), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);

            if ($contact->registration_no != '') {
                $y += 5;

                $pdf->SetFont('ChakraPetch-Bold', '', 10);
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, __('Registration No').': ', '0', '0', '');

                $pdf->SetFont('ChakraPetch-Regular', '', 10);
                $x = $x+2 + $pdf->GetStringWidth(__('Registration No').': ');
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, $contact->registration_no, '0', '0', '');

                if ($contact->registration_office != '' && App::getLocale() != 'en') {

                    $x = $x+5 + $pdf->GetStringWidth($contact->registration_no);

                    $pdf->SetFont('ChakraPetch-Bold', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, __('Registration Office').': ', '0', '0', '');

                    $x = $x+2 + $pdf->GetStringWidth(__('Registration Office').': ');
                    $pdf->SetFont('ChakraPetch-Regular', '', 10);
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(0, 0, $contact->registration_office, '0', '0', '');

                }
            }

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = 10;
            $y += 5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Address').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $lines = explode('<br>', $contact->address);
            foreach ($lines as $line) {
                $y += 5;
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $line), '0', '0', '');
            }

            $y += 5;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Phone').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->phone, '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Email').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->email, '0', '0', '');

            //TITLE

            $y += 10;
            $x = 10;

            $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', __('Packing List'));
            $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
            $title = iconv('utf-8', 'iso-8859-9', $inputString);
            $pdf->SetFont('ChakraPetch-Bold', '', 20);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $title, '0', '0', '');

            //CUSTOMER INFO

            $y += 10;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Customer').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            if ($lang == 'tr') {
                $x = $x - 3 + $pdf->GetStringWidth(__('Customer') . ': ');
            }else{
                $x = $x+2 + $pdf->GetStringWidth(__('Customer') . ': ');
            }
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $company->name), '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Address').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Address').': ');
            $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $company->address);
            $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
            $address = iconv('utf-8', 'iso-8859-9', $inputString);
//            $pdf->Cell(0, 0, $address, '0', '0', '');
            $address_width = $pdf->GetStringWidth($address);
            $address_height = (((int)($address_width / 100)) + 1) * 2;

            if ($address_height == 2){
                $pdf->SetXY($x, $y);
            }else {
                $pdf->SetXY($x, $y - 2);
            }
            $pdf->MultiCell(100, $address_height, $address, 0, 'L');



            $y += 13;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Phone').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $company->phone, '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email').': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x+2 + $pdf->GetStringWidth(__('Email').': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $company->email, '0', '0', '');



            $x = 10;
            $y += 10;
            $pdf->SetXY($x, $y);



// Set table header
            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->Cell(10, 12, 'N#', 0, 0, 'C');
            $pdf->Cell(30, 12, iconv('utf-8', 'iso-8859-9', __('Ref. Code')), 0, 0, 'C');
            $pdf->Cell(100, 12, iconv('utf-8', 'iso-8859-9', __('Product Name')), 0, 0, 'C');
            $pdf->Cell(25, 12, iconv('utf-8', 'iso-8859-9', __('Qty')), 0, 0, 'C');
            $pdf->Cell(25, 12, iconv('utf-8', 'iso-8859-9', __('Unit')), 0, 0, 'C');
            $pdf->Ln();



// Set table content
            $pdf->SetFont('ChakraPetch-Regular', '', 9);
            foreach ($sale_offers as $sale_offer) {
                if (App::getLocale() == 'tr'){
                    $measurement_name = $sale_offer->measurement_name_tr;
                }else{
                    $measurement_name = $sale_offer->measurement_name_en;
                }

                $row_height = 15;
                $pdf->SetFont('ChakraPetch-Regular', '', 9);

                $cleanInput = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $sale_offer->product_name);
                $inputString = mb_convert_encoding($cleanInput, 'UTF-8', 'auto');
//                $inputString = preg_replace('/[^\x20-\x7E]/u', '', $inputString);
                $product_name = iconv('utf-8', 'iso-8859-9', $inputString);

                $name_width = $pdf->GetStringWidth($product_name);
                if ($name_width > 100){
                    $wd = (($name_width / 100));
                    if ($name_width > 200){
                        $wd = (($name_width / 90));
                    }
                    if ($wd >= 0 && $wd < 1){
                        $row_height = 15;
                    }else if ($wd >= 1 && $wd < 2){
                        $row_height = 7.5;
                    }else if ($wd >= 2 && $wd < 3){
                        $row_height = 5;
                    }else if ($wd >= 3 && $wd < 4){
                        $row_height = 3.75;
                    }else if ($wd >= 4 && $wd < 5){
                        $row_height = 3;
                    }else if ($wd >= 5){
                        $row_height = 2.5;
                    }

                }

                $pdf->setX(10);
                $pdf->Cell(10, 15, $sale_offer->sequence, 1, 0, 'C');
//                $pdf->Cell(10, 14, '', 1, 0, 'C');
                $pdf->Cell(30, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->product_ref_code), 1, 0, 'C');
//                $pdf->Cell(20, 14, iconv('utf-8', 'iso-8859-9', $row_height.' - '.$name_width), 1, 0, 'C');

                // Save the current X and Y position
                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();


                // Use MultiCell for product name with a width of 50mm
                $pdf->MultiCell(100, $row_height, $product_name, 'T', 'L');

                // Reset X and move Y to the saved position (next line)
                $pdf->SetXY($xPos+100, $yPos);

                // Output remaining cells for the current row
                $pdf->Cell(25, 15, iconv('utf-8', 'iso-8859-9', $sale_offer->offer_quantity), 1, 0, 'C');
                $pdf->Cell(25, 15, iconv('utf-8', 'iso-8859-9', $measurement_name), 1, 0, 'C');
                $pdf->Ln();
            }


            //NOTE
            if ($packing_list->note != null) {
                $y = $pdf->GetY() + 10;
                $x = 10;
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Bold', '', 8);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Note')), 0, 0, '');

                $y += 5;
                $x = 10;
                $pdf->SetXY($x, $y);
                $pdf->SetFont('ChakraPetch-Regular', '', 8);
                $html = utf8_decode($packing_list->note);
                $html = str_replace('<br>', "\n", $html);
                $html = str_replace('<p>', '', $html);
                $html = str_replace('</p>', "\n", $html);
                $pdf->MultiCell(0, 5, utf8_decode($html));
            }



            //FOOTER

            $pdfContent = $pdf->Output('created.pdf', 'S');

            $pdf = new Fpdi();
            $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            $numPages = $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            for ($pageNo = 1; $pageNo <= $numPages; $pageNo++) {
                $pdf->AddPage();

                $pdf->Image(public_path($contact->footer), 10, 260, 190);

                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx, 0, 0, null, null, true);
            }

            $filePath = public_path('img/document/' . $contact->short_code . '-PL-' . $sale->id . '-'. $packing_list->id .'.pdf');
            $pdf->Output($filePath, 'F');

            $fileUrl = 'img/document/' . $contact->short_code . '-PL-' . $sale->id . '-'. $packing_list->id .'.pdf';
            $fileName = $contact->short_code . '-PL-' . $sale->id . '-'. $packing_list->id .'.pdf';

            PackingList::query()->where('packing_list_id', $packing_list_id)->update([
                'pl_url' => $fileUrl
            ]);

            return response([
                'message' => __('İşlem Başarılı.'),
                'status' => 'success',
                'object' => [
                    'file_url' => $fileUrl,
                    'file_name' => $fileName
                ]
            ]);


        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }




}
