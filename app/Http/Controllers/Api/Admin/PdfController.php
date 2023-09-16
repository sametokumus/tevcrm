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

    public function MultiCellWithMaxWidth($pdf, $maxWidth, $lineHeight, $text, $y) {
        $words = explode(' ', $text);
        $lines = array('');

        foreach ($words as $word) {
            $line = &$lines[count($lines) - 1];
            $testLine = $line . ' ' . $word;

            // Measure the width of the line
            $lineWidth = $pdf->GetStringWidth($testLine);

            if ($lineWidth <= $maxWidth) {
                // The word fits within the max width, add it to the line
                $line = $testLine;
            } else {
                // Start a new line with the current word
                $lines[] = $word;
            }
        }

        // Output the lines
        foreach ($lines as $line) {
            $pdf->Cell($maxWidth, $lineHeight, $line, 0, 1, 'L');
            $y += 6;
        }
        return $y;
    }

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
            $pdf->Cell(0, 0, $contact->name, '0', '0', '');

            if ($contact->registration_no != '') {
                $y += 6;
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, __('Registration No').': '.$contact->registration_no, '0', '0', '');
            }

            if ($contact->registration_office != '' && App::getLocale() != 'en') {
                $x += 50;
                $pdf->SetXY($x, $y);
                $pdf->Cell(0, 0, __('Registration Office').': '.$contact->registration_office, '0', '0', '');
                $x = 10;
            }

            $y += 6;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Address'), '0', '0', '');

            $maxWidth = 100; // Adjust as needed
            $lineHeight = 6; // Adjust as needed
            $y = $this->MultiCellWithMaxWidth($pdf, $maxWidth, $lineHeight, $contact->address, $y);

            $y += 6;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, $contact->address, '0', '0', '');

            $y += 6;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Phone').': '.$contact->phone, '0', '0', '');

            $y += 6;
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 0, __('Email').': '.$contact->email, '0', '0', '');

            $b64Doc = $pdf->Output('invoice.pdf', 'S');  // Set the 'I' flag to output to the browser
            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['file_pixel' => chunk_split(base64_encode($b64Doc))]]);

        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }


}
