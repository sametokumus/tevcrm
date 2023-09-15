<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PDF;

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
            $pdf->Output();
            $content = ob_get_clean();

            return response::make($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="invoice.pdf"'
            ]);

//            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sale' => $sale]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

}
