<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ProductDocument;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductDocumentController extends Controller
{
    public function getProductDocument()
    {
        try {
            $product_documents = ProductDocument::query()->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_documents' => $product_documents]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
