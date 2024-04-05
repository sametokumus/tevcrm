<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Measurement;
use App\Models\MobileDocument;
use App\Models\Offer;
use App\Models\OfferDetail;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\OfferRequestProduct;
use App\Models\OrderConfirmationDetail;
use App\Models\OwnerBankInfo;
use App\Models\PackingList;
use App\Models\PackingListProduct;
use App\Models\PaymentTerm;
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
use App\Models\Status;
use App\PDF\PDI;
use Faker\Provider\Uuid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use FPDF;
use setasign\Fpdi\Fpdi;
use App\PDF\PDF;


class PdfController extends Controller
{
    private function clearText($text){

    }
    private function htmlTextConvertArray($text){

    }
    private function textConvert($text){
        $inputString = mb_convert_encoding($text, 'UTF-8', 'auto');

        // Remove characters that are not letters, numbers, whitespace, or punctuation, including Turkish characters
        $inputString = mb_ereg_replace('[^[:alnum:][:space:][:punct:]ğüşıöçĞÜŞİÖÇ]', ' ', $inputString);

        // Remove characters outside the printable ASCII range, including Turkish characters
        $inputString = mb_ereg_replace('[^ -~ğüşıöçĞÜŞİÖÇ]', '', $inputString);

        // Convert the string to ISO-8859-9 encoding
        return mb_convert_encoding($inputString, 'ISO-8859-9', 'UTF-8');
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
    private function addCustomerPO($pdf, $customer_po, $actual_height, $pageWidth){
        if ($customer_po != null) {
            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth(__('CustomerPO') . ': ' . $customer_po) - 10;
            $pdf->SetXY($x, $actual_height + 40);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('CustomerPO') . ': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $pageWidth - $pdf->GetStringWidth($customer_po) - 10;
            $pdf->SetXY($x, $actual_height + 40);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $customer_po), '0', '0', '');
        }
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
    private function addCompanyInfo($pdf, $lang, $company, $employee, $y){
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

        if ($employee == null) {

            $y += $row_height + 3;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone') . ': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x + 2 + $pdf->GetStringWidth(__('Phone') . ': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $company->phone, '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email') . ': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x + 2 + $pdf->GetStringWidth(__('Email') . ': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $company->email, '0', '0', '');

        }else{

            $y += $row_height + 3;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Authorized') . ': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x + 2 + $pdf->GetStringWidth(__('Authorized') . ': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $this->textConvert($employee->name), '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone') . ': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x + 2 + $pdf->GetStringWidth(__('Phone') . ': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $employee->phone, '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email') . ': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x + 2 + $pdf->GetStringWidth(__('Email') . ': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $employee->email, '0', '0', '');

        }

        return $y + 5;
    }
    private function addCompanyInfoPackingList($pdf, $lang, $company, $employee, $y, $packing_list){
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

        if ($packing_list->address_id != null){
            $y = $pdf->getY() + 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', __('Delivery Address').': '), '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);

            $y += 2;
            $x = 10;
            $pdf->SetXY($x, $y);

            $address_data = Address::query()->where('id', $packing_list->address_id)->first();
            $address = $this->textConvert($address_data->address);

            $address_width = $pdf->GetStringWidth($address);
            $lines_needed = ceil($address_width / 100);
            $line_height = 5;
            $row_height = $lines_needed * $line_height;
            $pdf->MultiCell(100, $line_height, $address, 0, 'L');
        }

        if ($employee == null) {

            $y += $row_height + 3;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone') . ': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x + 2 + $pdf->GetStringWidth(__('Phone') . ': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $company->phone, '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email') . ': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x + 2 + $pdf->GetStringWidth(__('Email') . ': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $company->email, '0', '0', '');

        }else{

            $y += $row_height + 3;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Authorized') . ': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x + 2 + $pdf->GetStringWidth(__('Authorized') . ': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $this->textConvert($employee->name), '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone') . ': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x + 2 + $pdf->GetStringWidth(__('Phone') . ': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $employee->phone, '0', '0', '');

            $y += 5;
            $x = 10;

            $pdf->SetFont('ChakraPetch-Bold', '', 10);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email') . ': ', '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);
            $x = $x + 2 + $pdf->GetStringWidth(__('Email') . ': ');
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $employee->email, '0', '0', '');

        }

        return $y + 5;
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
    private function convertPrice($price){
        return number_format($price, 2,",",".");
    }


    //PDF'S

    public function getGenerateFR38PDF($offer_id)
    {
        try {

            $offer = Offer::query()
                ->where('offers.id',$offer_id)
                ->where('offers.active',1)
                ->first();

            $offer['manager'] = null;
            $offer['lab_manager'] = null;
            $employee = null;

            if ($offer->manager_id != null) {
                $offer['manager'] = Admin::query()->where('id', $offer->manager_id)->first();
            }
            if ($offer->manager_id != null) {
                $offer['lab_manager'] = Admin::query()->where('id', $offer->lab_manager_id)->first();
            }
            if ($offer->employee_id != null) {
                $employee = Employee::query()->where('id', $offer->employee_id)->first();
            }
            $customer = Company::query()
                ->where('companies.id', $offer->customer_id)
                ->first();

            $offer['status'] = Status::query()->where('id', $offer->status_id)->first();

            $offer['global_id'] = "LB.".$offer->id;

            $offer_details = OfferDetail::query()
                ->leftJoin('categories', 'categories.id', '=', 'offer_details.category_id')
                ->selectRaw('offer_details.*, categories.name as category_name')
                ->where('offer_details.offer_id', $offer_id)
                ->where('offer_details.active',1)
                ->get();

            $accounting = Accounting::query()->where('offer_id', $offer_id)->where('active', 1)->first();


            // Create a new PDF instance
            $pdf = new PDI();
            $pdf->setSourceFile(public_path('FR-38-Layout.pdf'));
            $templateId = $pdf->importPage(1);
            $pdf->AddPage();
            $pdf->useTemplate($templateId);

            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(true, 40);

            $pdf->AddFont('arial_tr', '', 'arial_tr.php');
            $pdf->AddFont('arial_tr', 'B', 'arial_tr_bold.php');

            $pdf->SetFont('arial_tr', '', 9);

            $offer_date = Carbon::parse($offer->created_at);
            $pdf->SetXY(65, 51);
            $pdf->Cell(0, 0, $offer_date->format('d-m-Y'), '0', '0', '');

            $pdf->SetXY(65, 61);
            $pdf->Cell(0, 0, $offer['global_id'], '0', '0', '');


            $pdf->SetXY(65, 91);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $customer->name), 0, 0, '');

            $pdf->SetXY(65, 101);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $customer->address), 0, 0, '');

            if ($employee != null) {
                $pdf->SetXY(65, 111);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $employee->name), 0, 0, '');
            }

            $pdf->SetXY(65, 121);
            $pdf->Cell(0, 0, $customer->phone, '0', '0', '');

            if ($customer->website != null) {
                $pdf->SetXY(65, 130);
                $pdf->Cell(0, 0, $customer->website." & ".$customer->email, '0', '0', '');
            }else{
                $pdf->SetXY(65, 130);
                $pdf->Cell(0, 0, $customer->email, '0', '0', '');
            }


            $x = 10;
            $y = 153.7;
            $pdf->SetXY($x, $y);
            $test_count = 1;
            foreach ($offer_details as $offer_detail) {

                $pdf->SetFont('arial_tr', '', 8);

                $product_name = '';
                $test_name = '';

                if ($offer_detail->product_name != null && $offer_detail->product_name != '') {
                    $product_name = $this->textConvert($offer_detail->product_name);
                    $product_name_width = $pdf->GetStringWidth($product_name);
                    $lines_needed1 = ceil($product_name_width / 47);
                }else{
                    $lines_needed1 = 1;
                }

                if ($offer_detail->name != null && $offer_detail->name != '') {
                    $test_name = $this->textConvert($offer_detail->name);
                    $test_name_width = $pdf->GetStringWidth($test_name);
                    $lines_needed2 = ceil($test_name_width / 44);
                }else{
                    $lines_needed2 = 1;
                }

                if ($lines_needed1 >= $lines_needed2){
                    $lines_needed = $lines_needed1;
                }else{
                    $lines_needed = $lines_needed2;
                }

                $line_height = 8;
                if ($lines_needed > 1){
                    $line_height = 5;
                }

                $x = 14.2;
                $pdf->SetXY($x, $pdf->GetY());
                $old_y = $y;

                $row_height = $lines_needed * $line_height;
                $total_y = $pdf->getY() + $row_height;
                if ($total_y > 270){
                    $pdf->AddPage();
                    $pdf->SetXY(14.2, 10);
                    $y = 10;
                    $old_y = $pdf->getY();
                }

                $pdf->SetXY(14.2, $old_y);
                if ($lines_needed == $lines_needed1){
                    $pdf->MultiCell(47.4, $line_height, $product_name, 1, 'L');
                }else{
                    $fark = $lines_needed - $lines_needed1;
                    for ($i = 0; $i < $fark; $i++) {
                        $product_name .= "\n ";
                    }
                    $pdf->MultiCell(47.4, $line_height, $product_name, 1, 'L');
                }

                $pdf->SetXY(62, $old_y);
                if ($lines_needed == $lines_needed2){
                    $pdf->MultiCell(44.5, $line_height, $test_name, 1, 'L');
                }else{
                    $fark = $lines_needed - $lines_needed2;
                    for ($i = 0; $i < $fark; $i++) {
                        $test_name .= "\n ";
                    }
                    $pdf->MultiCell(44.5, $line_height, $test_name, 1, 'L');
                }


                $new_y = $pdf->getY();
                if ($new_y > $old_y) {
                    $row_height = $new_y - $old_y;
                }else{
                    $row_height = $new_y - 20;
                }

                $x = 8;
                $pdf->SetXY($x, $y);
                $pdf->Cell(5.8, $row_height, $test_count.".", 1, 0, 'C');

                $x = 106.9;
                $pdf->SetXY($x, $y);
                $pdf->Cell(24.7, $row_height, iconv('utf-8', 'iso-8859-9', $offer_detail->sample_count), 1, 0, 'C');
                $x = 132;
                $pdf->SetXY($x, $y);
                $pdf->Cell(19.6, $row_height, iconv('utf-8', 'iso-8859-9', "1"), 1, 0, 'C');
                $x = 152;
                $pdf->SetXY($x, $y);
                $pdf->Cell(22.7, $row_height, iconv('utf-8', 'iso-8859-9', $this->convertPrice($offer_detail->price)." TL"), 1, 0, 'C');
                $x = 175.1;
                $pdf->SetXY($x, $y);
                $pdf->Cell(26.8, $row_height, iconv('utf-8', 'iso-8859-9', $this->convertPrice($offer_detail->price)." TL"), 1, 0, 'C');

                $y += $row_height + 0.4;
                $test_count++;

            }

            $pdf->SetFont('arial_tr', 'B', 8);

            $y = $pdf->getY() + $row_height + 0.4;
            $first_total_start = $y;
            $pdf->SetXY(132, $y);
            $pdf->Cell(42.7, 8, iconv('utf-8', 'iso-8859-9', "Toplam Tutar / Total Amount"), 1, 0, 'L');
            $pdf->SetXY(175.1, $y);
            $pdf->Cell(26.8, 8, iconv('utf-8', 'iso-8859-9', $this->convertPrice($accounting->test_total)." TL"), 1, 0, 'C');
            $pdf->Ln();

            $y = $pdf->getY() + 0.4;
            $pdf->SetXY(132, $y);
            $pdf->Cell(42.7, 8, iconv('utf-8', 'iso-8859-9', "İskonto / Discount"), 1, 0, 'L');
            $pdf->SetXY(175.1, $y);
            $pdf->Cell(26.8, 8, iconv('utf-8', 'iso-8859-9', $this->convertPrice($accounting->discount)." TL"), 1, 0, 'C');
            $pdf->Ln();

            $y = $pdf->getY() + 0.4;
            $pdf->SetXY(132, $y);
            $pdf->Cell(42.7, 8, iconv('utf-8', 'iso-8859-9', "Ara Toplam / Subtotal"), 1, 0, 'L');
            $pdf->SetXY(175.1, $y);
            $pdf->Cell(26.8, 8, iconv('utf-8', 'iso-8859-9', $this->convertPrice($accounting->sub_total)." TL"), 1, 0, 'C');
            $pdf->Ln();

            $y = $pdf->getY() + 0.4;
            $pdf->SetXY(132, $y);
            $pdf->Cell(42.7, 8, iconv('utf-8', 'iso-8859-9', "KDV (%".$accounting->vat_rate.") / VAT (%".$accounting->vat_rate.")"), 1, 0, 'L');
            $pdf->SetXY(175.1, $y);
            $pdf->Cell(26.8, 8, iconv('utf-8', 'iso-8859-9', $this->convertPrice($accounting->vat)." TL"), 1, 0, 'C');
            $pdf->Ln();

            $y = $pdf->getY() + 0.4;
            $pdf->SetXY(132, $y);
            $pdf->MultiCell(42.7, 5, iconv('utf-8', 'iso-8859-9', "Genel Toplam/ General total\n(KDV Dahil)/ VAT included"), 1, 'L');
            $pdf->SetXY(175.1, $y);
            $pdf->Cell(26.8, 10, iconv('utf-8', 'iso-8859-9', $this->convertPrice($accounting->grand_total)." TL"), 1, 0, 'C');
            $pdf->Ln();

            $line_last = $pdf->getY() + 0.8;

            $pdf->SetXY(8, $first_total_start);
            $pdf->MultiCell(123.6, 5, iconv('utf-8', 'iso-8859-9', "\nNOT: Test Teklif ve Sözleşme Formunun onaylanmış hali ve toplam test ücretinin yatırıldığına dair dekontun tarafımıza gönderilmesinden sonra testlere başlanacaktır. \n
NOTE: The tests will start after the approved version of the Test Offer and Contract Form and the receipt showing that the total test fee has been paid to us.
"), 0, 'L');
            $pdf->Rect(8, $first_total_start, 123.6, 43.6);
            $pdf->Ln();

            $pdf->SetLineWidth(1);
            $pdf->Line(7.1, 153.6, 7.1, $line_last);
            $pdf->Line(202.7, 153.6, 202.7, $line_last);
            $pdf->Line(7.1, $line_last, 202.7, $line_last);


//
//            $pdf->SetXY(10, 220);
//            $pdf->Cell(80, $row_height, iconv('utf-8', 'iso-8859-9', $y."-".$pdf->getY()."-".$old_y), 1, 0, 'C');
//            $pdf->SetXY(10, 240);
//            $pdf->DoubleBorderCell(80, $row_height, iconv('utf-8', 'iso-8859-9', $y."-".$pdf->getY()."-".$old_y), 1, 0, 'C');

            //CONDITIONS

            $pdf2 = new PDI();
            $pdf2->setSourceFile(public_path('FR-38-Layout.pdf'));
            $numPages2 = $pdf2->setSourceFile(public_path('FR-38-Layout.pdf'));
            $templateId = $pdf2->importPage(1);
            $pdf->AddPage();
            $pdf->useTemplate($templateId);

//            $pdf2 = new Fpdi();
//            $pdf2->setSourceFile(public_path('FR-38-Conditions.pdf'));
//            $numPages2 = $pdf2->setSourceFile(public_path('FR-38-Conditions.pdf'));
//
//            for ($pageNo = 1; $pageNo <= $numPages2; $pageNo++) {
//                $pdf->AddPage();
//
//                $tplIdx = $pdf2->importPage($pageNo);
//                $pdf->useTemplate($tplIdx, 0, 0, null, null, true);
//            }

            //FOOTER

            $pdfContent = $pdf->Output('created.pdf', 'S');

            $pdf = new PDI();
            $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            $numPages = $pdf->setSourceFile('data:application/pdf;base64,' . base64_encode($pdfContent));

            for ($pageNo = 1; $pageNo <= $numPages; $pageNo++) {
                $pdf->AddPage();

//                $width = 190;
//                $imagePath = public_path($contact->footer);
//                list($originalWidth, $originalHeight) = getimagesize($imagePath);
//                $aspectRatio = $originalWidth / $originalHeight;
//                $height = $width / $aspectRatio;
//                $y = 285 - $height;
//                $x = 10;
//                $pdf->Image($imagePath, $x, $y, $width, $height);

                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx, 0, 0, null, null, true);
            }

            $filePath = public_path('documents/LB.' . $offer_id . '-FR.38.pdf');
            $pdf->Output($filePath, 'F');

            $fileUrl = 'documents/LB.' . $offer_id . '-FR.38.pdf';
            $fileName = 'LB.' . $offer_id . '-FR.38.pdf';

//            Document::query()->where('id', $document_id)->update([
//                'file_url' => $fileUrl
//            ]);

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
