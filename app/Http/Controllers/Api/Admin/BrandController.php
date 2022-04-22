<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class BrandController extends Controller
{
    public function addBrand(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'slug' => 'required'
            ]);
            $brand_id = Brand::query()->insertGetId([
                'name' => $request->name,
                'slug' => $request->slug
            ]);
            if ($request->hasFile('logo')) {
                $rand = uniqid();
                $image = $request->file('logo');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/images/Admin/Logo/'), $image_name);
                $image_path = "/images/Admin/Logo/" . $image_name;
                Brand::query()->where('id',$brand_id)->update([
                    'logo' => $image_path
                ]);
            }
            return response(['message' => 'Marka ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001','er' => $throwable->getMessage()]);
        }
    }
}
