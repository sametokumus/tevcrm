<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductRule;
use App\Models\ProductSeo;
use App\Models\ProductVariation;
use App\Models\ProductVariationGroup;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;


class ImportController extends Controller
{
    public function productExcelImport(Request $request){
        Excel::import(new ProductImport, $request->file('file'));
        return response(['mesaj' => 'başarılı']);
    }

    public function addAllProduct(){
        $import_products = ImportProduct::all();

        foreach ($import_products as $import_product){

            $hasProduct = Product::query()->where('sku', $import_product->ana_urun_kod)->first();

            if(!isset($hasProduct)){

                if ($import_product->marka == '3_2'){

                    $product_id = Product::query()->insertGetId([
                        'sku' => $import_product->ana_urun_kod,
                        'name' => $import_product->ana_urun_ad,
                        'brand_id' => 3,
                        'type_id' => $import_product->cins,
                        'description' => $import_product->aciklama,
                        'short_description' => $import_product->kisa_aciklama,
                        'notes' => $import_product->notlar
                    ]);
                    ProductSeo::query()->insert([
                        'product_id' => $product_id,
                        'title' => $import_product->seo_baslik,
                        'keywords' => $import_product->seo_kelimeler,
                        'search_keywords' => $import_product->arama_kelimeleri
                    ]);
                    $variation_group_id = ProductVariationGroup::query()->insertGetId([
                        'group_type_id' => 1,
                        'product_id' => $product_id,
                        'order' => 1
                    ]);
                    $variation_id = ProductVariation::query()->insertGetId([
                        'variation_group_id' => $variation_group_id,
                        'sku' => $import_product->alt_urun_kod,
                        'name' => $import_product->renk,
                        'description' => ''
                    ]);
                    ProductRule::query()->insert([
                        'package_type_id' => $import_product->kmk,
                        'variation_id' => $variation_id,
                        'micro_name' => $import_product->mikro_urun_ad,
                        'micro_sku' => $import_product->mikro_urun_kod,
                        'dimensions' => $import_product->birim,
                        'weight' => $import_product->agirlik
                    ]);
                    ProductImage::query()->insert([
                        'variation_id' => $variation_id,
                        'image' => $import_product->resim,
                        'order' => 1
                    ]);

                    $product_id = Product::query()->insertGetId([
                        'sku' => $import_product->ana_urun_kod,
                        'name' => $import_product->ana_urun_ad,
                        'brand_id' => 2,
                        'type_id' => $import_product->cins,
                        'description' => $import_product->aciklama,
                        'short_description' => $import_product->kisa_aciklama,
                        'notes' => $import_product->notlar
                    ]);
                    ProductSeo::query()->insert([
                        'product_id' => $product_id,
                        'title' => $import_product->seo_baslik,
                        'keywords' => $import_product->seo_kelimeler,
                        'search_keywords' => $import_product->arama_kelimeleri
                    ]);
                    $variation_group_id = ProductVariationGroup::query()->insertGetId([
                        'group_type_id' => 1,
                        'product_id' => $product_id,
                        'order' => 1
                    ]);

                    $variation_id = ProductVariation::query()->insertGetId([
                        'variation_group_id' => $variation_group_id,
                        'sku' => $import_product->alt_urun_kod,
                        'name' => $import_product->renk,
                        'description' => ''
                    ]);
                    ProductRule::query()->insert([
                        'package_type_id' => $import_product->kmk,
                        'variation_id' => $variation_id,
                        'micro_name' => $import_product->mikro_urun_ad,
                        'micro_sku' => $import_product->mikro_urun_kod,
                        'dimensions' => $import_product->birim,
                        'weight' => $import_product->agırlik
                    ]);
                    ProductImage::query()->insert([
                        'variation_id' => $variation_id,
                        'image' => $import_product->resim,
                        'order' => 1
                    ]);


                }else{

                    $product_id = Product::query()->insertGetId([
                        'sku' => $import_product->ana_urun_kod,
                        'name' => $import_product->ana_urun_ad,
                        'brand_id' => $import_product->marka,
                        'type_id' => $import_product->cins,
                        'description' => $import_product->aciklama,
                        'short_description' => $import_product->kisa_aciklama,
                        'notes' => $import_product->notlar
                    ]);
                    ProductSeo::query()->insert([
                        'product_id' => $product_id,
                        'title' => $import_product->seo_baslik,
                        'keywords' => $import_product->seo_kelimeler,
                        'search_keywords' => $import_product->arama_kelimeleri
                    ]);
                    $variation_group_id = ProductVariationGroup::query()->insertGetId([
                        'group_type_id' => 1,
                        'product_id' => $product_id,
                        'order' => 1
                    ]);

                    $variation_id = ProductVariation::query()->insertGetId([
                        'variation_group_id' => $variation_group_id,
                        'sku' => $import_product->alt_urun_kod,
                        'name' => $import_product->renk,
                        'description' => ''
                    ]);
                    ProductRule::query()->insert([
                        'package_type_id' => $import_product->kmk,
                        'variation_id' => $variation_id,
                        'micro_name' => $import_product->mikro_urun_ad,
                        'micro_sku' => $import_product->mikro_urun_kod,
                        'dimensions' => $import_product->birim,
                        'weight' => $import_product->agırlik
                    ]);
                    ProductImage::query()->insert([
                        'variation_id' => $variation_id,
                        'image' => $import_product->resim,
                        'order' => 1
                    ]);

                }


            }else{

                if ($import_product->marka == '3_2') {

                    $variation_group_id = ProductVariationGroup::query()->where('product_id', $hasProduct->id)->first()->id;

                    $variation_id = ProductVariation::query()->insertGetId([
                        'variation_group_id' => $variation_group_id,
                        'sku' => $import_product->alt_urun_kod,
                        'name' => $import_product->renk,
                        'description' => ''
                    ]);
                    ProductRule::query()->insert([
                        'package_type_id' => $import_product->kmk,
                        'variation_id' => $variation_id,
                        'micro_name' => $import_product->mikro_urun_ad,
                        'micro_sku' => $import_product->mikro_urun_kod,
                        'dimensions' => $import_product->birim,
                        'weight' => $import_product->agırlik
                    ]);
                    ProductImage::query()->insert([
                        'variation_id' => $variation_id,
                        'image' => $import_product->resim,
                        'order' => 1
                    ]);

                    $variation_id = ProductVariation::query()->insertGetId([
                        'variation_group_id' => $variation_group_id+1,
                        'sku' => $import_product->alt_urun_kod,
                        'name' => $import_product->renk,
                        'description' => ''
                    ]);
                    ProductRule::query()->insert([
                        'package_type_id' => $import_product->kmk,
                        'variation_id' => $variation_id,
                        'micro_name' => $import_product->mikro_urun_ad,
                        'micro_sku' => $import_product->mikro_urun_kod,
                        'dimensions' => $import_product->birim,
                        'weight' => $import_product->agırlik
                    ]);
                    ProductImage::query()->insert([
                        'variation_id' => $variation_id,
                        'image' => $import_product->resim,
                        'order' => 1
                    ]);

                }else{

                    $variation_group_id = ProductVariationGroup::query()->where('product_id', $hasProduct->id)->first()->id;

                    $variation_id = ProductVariation::query()->insertGetId([
                        'variation_group_id' => $variation_group_id,
                        'sku' => $import_product->alt_urun_kod,
                        'name' => $import_product->renk,
                        'description' => ''
                    ]);
                    ProductRule::query()->insert([
                        'package_type_id' => $import_product->kmk,
                        'variation_id' => $variation_id,
                        'micro_name' => $import_product->mikro_urun_ad,
                        'micro_sku' => $import_product->mikro_urun_kod,
                        'dimensions' => $import_product->birim,
                        'weight' => $import_product->agırlik
                    ]);
                    ProductImage::query()->insert([
                        'variation_id' => $variation_id,
                        'image' => $import_product->resim,
                        'order' => 1
                    ]);

                }

            }



        }
    }
}
