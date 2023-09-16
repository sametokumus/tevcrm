<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Sale;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use FPDF;

class PdfController extends Controller
{

    public function getGeneratePDF($owner_id, $sale_id)
    {
        try {

            $sale = Sale::query()->where('sale_id', $sale_id)->first();
            $contact = Contact::query()->where('id', $owner_id)->first();

            // Create a new PDF instance
            $pdf = new \FPDF();
            $pdf->AddPage();

            $pdf->SetMargins(20, 20, 20);


            // Set font
            $pdf->AddFont('ChakraPetch-Regular', '', 'ChakraPetch-Regular.php');
            $pdf->AddFont('ChakraPetch-Bold', '', 'ChakraPetch-Bold.php');
            $pdf->SetFont('ChakraPetch-Bold', '', 10);

            // Add content to the PDF (example: sale information)
            $x = 10;
            $y = 15;

            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->name, '0', '0', '');

            $pdf->SetFont('ChakraPetch-Regular', '', 10);

            if ($contact->registration_no != '') {
                $y += 5;
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, __('Registration No').': '.$contact->registration_no, '0', '0', '');
            }

            if ($contact->registration_office != '' && App::getLocale() != 'en') {
                $x += 40;
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, __('Registration Office').': '.$contact->registration_office, '0', '0', '');
                $x = 10;
            }

            $y += 5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Address'), '0', '0', '');

            $lines = explode('<br>', $contact->address);
            foreach ($lines as $line) {
                $y += 5;
                $pdf->SetXY(10, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $line), '0', '0', '');
            }

//            $y += 5;
//            $pdf->SetXY($x, $y);
//            $pdf->Cell(0, 0, $contact->address, '0', '0', '');

            $y += 5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone').': '.$contact->phone, '0', '0', '');

            $y += 5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email').': '.$contact->email, '0', '0', '');

            $b64Doc = $pdf->Output('invoice.pdf', 'S');  // Set the 'I' flag to output to the browser
            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['file_pixel' => chunk_split(base64_encode($b64Doc))]]);

        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }


}
