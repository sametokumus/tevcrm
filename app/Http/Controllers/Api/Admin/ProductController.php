<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductDocument;
use App\Models\Tag;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;

class ProductController extends Controller
{
    function objectToArray(&$object)
    {
        return @json_decode(json_encode($object), true);
    }
    public function addProduct(Request $request)
    {
        try {

            $product = json_decode($request->product);
            $productArray = $this->objectToArray($product);

            Validator::make($productArray,[
                'brand_id' => 'required|exists:brands,id',
                'type_id' => 'required|exists:product_types,id',
                'name' => 'required',
                'description' => 'required',
                'sku' => 'required',
                'delivery_price' => 'required',
                'delivery_tax' => 'required',
                'is_free_shipping' => 'required',
                'view_all_images' => 'required'
            ]);
            $product_id = Product::query()->insertGetId([
                'brand_id' => $product->brand_id,
                'type_id' => $product->type_id,
                'name' => $product->name,
                'description' => $product->description,
                'sku' => $product->sku,
                'delivery_price' => $product->delivery_price,
                'delivery_tax' => $product->delivery_tax,
                'is_free_shipping' => $product->is_free_shipping,
                'view_all_images' => $product->view_all_images
            ]);
//
            $product_documents = $request->product_documents;

//            foreach ($product_documents as $product_document){
//                if ($product_document->hasFile('product_documents')){
//                    $rand = uniqid();
//                    $image = $product_document->file('product_documents');
//                    $image_name = $rand . "-" . $image->getClientOriginalName();
//                    $image->move(public_path('/images/Admin/ProductDocument/'), $image_name);
//                    $image_path = "/images/Admin/ProductDocument/" . $image_name;
//                    ProductDocument::query()->insert([
//                        'file' => $image_path,
//                        'product_id' => $product_id,
//                    ]);
//                }
//            }

            if ($file = $request->file('product_documents')) {
                $path = $file->move(public_path('/images/Admin/ProductDocument'));
                $name = $file->getClientOriginalName();

                //store your file into directory and db
                $save = new ProductDocument();
                $save->file = $file.$path;
//                $save->store_path= $path;
                $save->save();

                return response()->json([
                    "success" => true,
                    "message" => "File successfully uploaded",
                    "file" => $file
                ]);

            }

            $product_categories = $product->product_categories;
            foreach ($product_categories as $product_category){
                ProductCategory::query()->insert([
                    'product_id' => $product_id,
                    'category_id' => $product_category->category_id
                ]);
            }



            Tag::query()->insertGetId([
                'name' => $request->name
            ]);
            return response(['message' => 'Ürün ekleme işlemi başarılı.', 'status' => 'success','ar' => $product_documents]);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

}
