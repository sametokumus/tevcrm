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
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\OfferRequestProduct;
use App\Models\Product;
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
use setasign\Fpdi\Fpdi;
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

            $fileUrl = asset('img/document/' . $contact->short_code . '-OFR-' . $sale->id . '.pdf');
            $fileName = $contact->short_code . '-OFR-' . $sale->id . '.pdf';

            return response([
                'message' => __('İşlem Başarılı.'),
                'status' => 'success',
                'object' => [
                    'file_url' => $fileUrl,
                    'file_name' => $fileName
                ]
            ]);

//            $finalPdfContent = $pdf->Output($contact->short_code.'-OFR-'.$sale->id.'.pdf', 'S');
//
//            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['file_pixel' => chunk_split(base64_encode($finalPdfContent))]]);


        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }


}
