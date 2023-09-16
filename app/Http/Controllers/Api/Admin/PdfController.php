<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Employee;
use App\Models\MobileDocument;
use App\Models\OfferRequest;
use App\Models\OfferRequestProduct;
use App\Models\Quote;
use App\Models\Sale;
use App\Models\SaleNote;
use App\Models\SaleOffer;
use Faker\Provider\Uuid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use FPDF;
use Carbon\Carbon;


class PdfController extends Controller
{

    public function getGeneratePDF($owner_id, $sale_id)
    {
        try {

            $sale = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->first();

            $this_document = MobileDocument::query()->where('sale_id', $sale->id)->first();
            if ($this_document){
                $createdAt = Carbon::parse($this_document->created_at);
                $document_date = $createdAt->format('d/m/Y');
                $document_id = $this_document->id;
            }else{
                $createdAt = Carbon::now();
                $document_date = $createdAt->format('d/m/Y');
                $document_id = MobileDocument::query()->insertGetId([
                    'sale_id' => $sale->id,
                    'document_type_id' => 30,
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


            //COMPANY INFO
            $pdf->AddFont('ChakraPetch-Regular', '', 'ChakraPetch-Regular.php');
            $pdf->AddFont('ChakraPetch-Bold', '', 'ChakraPetch-Bold.php');
            $pdf->SetFont('ChakraPetch-Bold', '', 12);

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


            // LOGO
            $pageWidth = $pdf->GetPageWidth();
            $x = $pageWidth - $contact->logo_width - 10;
            $pdf->Image(public_path($contact->logo), $x, 15, $contact->logo_width);  // Parameters: image file, x position, y position, width

            list($imageWidth, $imageHeight) = getimagesize(public_path($contact->logo));
            $actual_height = (int) ($contact->logo_width * $imageHeight / $imageWidth);

            //TARİH - KOD

            $x = $pageWidth - $pdf->GetStringWidth(__('Date').': '.$document_date) - 10;
            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $actual_height + 15);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Date').': '.$document_date), '0', '0', '');

            $x = $pageWidth - $pdf->GetStringWidth($contact->short_code.'-OFR-'.$sale->id) - 10;
            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $actual_height + 20);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->short_code.'-OFR-'.$sale->id), '0', '0', '');



            $b64Doc = $pdf->Output('invoice.pdf', 'S');  // Set the 'I' flag to output to the browser
            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['file_pixel' => chunk_split(base64_encode($b64Doc))]]);

        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }


}
