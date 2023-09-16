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
            $pdf->SetFont('Arial', '', 10);

            // Add content to the PDF (example: sale information)
            $x = 10;
            $y = 15;

            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->name), '0', '0', '');

            if ($contact->registration_no != '') {
                $y += 10;
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->registration_no), '0', '0', '');
            }

            if ($contact->registration_office != '') {
                $x += 50;
                $y += 10;
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', App::getLocale()), '0', '0', '');
                $x = 10;
            }

            $y += 10;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, iconv('utf-8', 'iso-8859-9', $contact->name), '0', '0', '');

            $b64Doc = $pdf->Output('invoice.pdf', 'S');  // Set the 'I' flag to output to the browser
            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['file_pixel' => chunk_split(base64_encode($b64Doc))]]);

        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

}
