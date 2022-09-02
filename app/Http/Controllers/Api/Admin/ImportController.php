<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Imports\NewProducts;
use App\Imports\PriceImports;
use App\Models\Brand;
use App\Models\ImportPrice;
use App\Models\ImportProduct;
use App\Models\NewProduct;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductRule;
use App\Models\ProductSeo;
use App\Models\ProductType;
use App\Models\ProductVariation;
use App\Models\ProductVariationGroup;
use App\Models\TextContent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;
use Nette\Schema\ValidationException;


class ImportController extends Controller
{
    public function productExcelImport(Request $request)
    {
        Excel::import(new ProductImport, $request->file('file'));
        return response(['mesaj' => 'başarılı']);
    }

    public function addAllProduct()
    {
        $import_products = ImportProduct::all();

        foreach ($import_products as $import_product) {

            $product_id = Product::query()->insertGetId([
                'brand_id' => 1,
                'type_id' => $import_product->type_id,
                'name' => null,
                'description' => null,
                'short_description' => null,
                'notes' => null,
                'sku' => $import_product->sku,
                'is_free_shipping' => 0
            ]);

            $name_id = TextContent::query()->insertGetId([
                'original_text' => strtoupper($import_product->name)
            ]);
            $description_id = TextContent::query()->insertGetId([
                'original_text' => $import_product->description
            ]);
            $short_description_id = TextContent::query()->insertGetId([
                'original_text' => null
            ]);
            $notes_id = TextContent::query()->insertGetId([
                'original_text' => null
            ]);

            Product::query()->where('id',$product_id)->update([
                'name'  =>$name_id,
                'description' => $description_id,
                'short_description' => $short_description_id,
                'notes' => $notes_id
            ]);

            $discounted_price = null;
            $discounted_tax = null;
            $regular_tax = $import_product->regular_price / (100 + 8) * 8;

            ProductRule::query()->where('id', $product_id)->insert([
                'product_id' => $product_id,
                'quantity_stock' => $import_product->quantity_stock,
                'discount_rate' => 0,
                'tax_rate' => 8,
                'regular_price' => $import_product->regular_price,
                'regular_tax' => $regular_tax,
                'discounted_price' => $discounted_price,
                'discounted_tax' => $discounted_tax
            ]);



        }
    }

    public function productVariationUpdate()
    {
        $products = Product::query()->get();
        foreach ($products as $product) {
            $group = ProductVariationGroup::query()->where('product_id', $product->id)->first();
            $variation_id = ProductVariation::query()->where('variation_group_id', $group->id)->first()->id;
            Product::query()->where('id', $product->id)->update([
                'featured_variation' => $variation_id
            ]);
        }
        return response(['mesaj' => 'başarılı']);
    }

    public function setProductCategory()
    {

        $products = Product::query()->get();
        foreach ($products as $product) {
            $type = ProductType::query()->where('id', $product->type_id)->first();
            ProductCategory::query()->insert([
                'product_id' => $product->id,
                'category_id' => $type->cid
            ]);
        }

        return response(['mesaj' => 'başarılı']);
    }


    public function priceExcelImport(Request $request)
    {
        Excel::import(new PriceImports(), $request->file('file'));
    }

    public function addProductPrice()
    {
        $import_prices = ImportPrice::all();
        foreach ($import_prices as $import_price) {
            $products = Product::query()->where('sku', $import_price->web_servis_kodu)->get();
            foreach ($products as $product){
                $product_variation_group = ProductVariationGroup::query()->where('product_id', $product->id)->first()->id;
                $variations = ProductVariation::query()->where('variation_group_id',$product_variation_group)->get();
                $brand = Brand::query()->where('id',$product->brand_id)->first();
                foreach ($variations as $variation){
                    if($brand->dis == 0){
                        $regular_tax = $import_price->fiyati / 100 * $import_price->kdv;
                        ProductRule::query()->where('variation_id',$variation->id)->update([
                            'discount_rate' => null,
                            'tax_rate' => $import_price->kdv,
                            'regular_price' => $import_price->fiyati,
                            'regular_tax' => $regular_tax,
                            'discounted_price' => null,
                            'discounted_tax' => null,
                            'currency' => $import_price->currency
                        ]);
                    }else{
                        $regular_tax = $import_price->fiyati / 100 * $import_price->kdv;
                        $discounted_price = $import_price->fiyati - ($import_price->fiyati / 100 * $brand->dis);
                        $discounted_tax = $discounted_price / 100 * $import_price->kdv;
                        ProductRule::query()->where('variation_id',$variation->id)->update([
                            'discount_rate' => $brand->dis,
                            'tax_rate' => $import_price->kdv,
                            'regular_price' => $import_price->fiyati,
                            'regular_tax' => $regular_tax,
                            'discounted_price' => $discounted_price,
                            'discounted_tax' => $discounted_tax,
                            'currency' => $import_price->currency
                        ]);
                        Product::query()->where('id', $product->id)->update([
                           'is_campaign' => 1
                        ]);
                    }



                    if ($import_price->yeni_urun_mu == "" || $import_price->indirimli_goster == ""  || $import_price->tanitimli_goster == ""){
                        Product::query()->where('sku',$import_price->web_servis_kodu)->update([
                            'is_new' => 0,
                            'is_discounted' => $import_price->indirimli_goster,
                            'is_featured' => $import_price->tanitimli_goster,
                            'order' => $import_price->sira_no
                        ]);
                    }else{
                        Product::query()->where('sku',$import_price->web_servis_kodu)->update([
                            'is_new' => $import_price->yeni_urun_mu,
                            'is_discounted' => $import_price->indirimli_goster,
                            'is_featured' => $import_price->tanitimli_goster,
                            'order' => $import_price->sira_no
                        ]);
                    }
                }
            }
        }
    }

    public function newProduct(Request $request){
        Excel::import(new NewProducts(), $request->file('file'));

    }

    public function postNewProducts(){
        $new_products = NewProduct::all();
        foreach ($new_products as $new_product){
            ProductRule::query()->where('micro_sku',$new_product->micro_urun_kod)->update([
                'renk' => $new_product->renk
            ]);
        }
        $product_rules = ProductRule::all();
        foreach ($product_rules as $product_rule){
            ProductVariation::query()->where('id',$product_rule->variation_id)->update([
                'name' => $product_rule->renk
            ]);
        }
        return response(['message' => 'başarılı']);
    }

    public function updateProductNew(){
        try {
            $search = 'PAMUKKALE';
            $products = Product::query()->where('name', 'LIKE', '%'.$search.'%')->get();
            $i = 0;
            foreach ($products as $product){
                if($product->brand_id == '3') {
                    $i++;
                    $explodes = explode("PAMUKKALE", $product->name);
//            return $explodes[0];
                    $new_name = $explodes[0]."ÖZNUR";
//                return $new_name;
                    Product::query()->where('id', $product->id)->update([
                        'name' => $new_name
                    ]);
                }
            }
            return $products;
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'err' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'ar' => $throwable->getMessage()]);
        }

    }
}
