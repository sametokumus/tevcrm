<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use FPDF;

class PdfController extends Controller
{

    public function getGeneratePDF($owner_id, $sale_id)
    {
        try {
            $sale = Sale::query()->where('sale_id', $sale_id)->first();

            // Create a new PDF instance
            $pdf = new \FPDF();
            $pdf->AddPage();

            // Set font
            $pdf->SetFont('Arial', 'B', 16);

            // Add content to the PDF (example: sale information)
            $pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Sale ID: ' . $sale->sale_id, 0, 1);

            // Output PDF as a response
            ob_start();
            $pdf->Output('invoice.pdf', 'I');  // Set the 'I' flag to output to the browser

            // Clear the output buffer and disable further output
            ob_end_flush();

        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

}
