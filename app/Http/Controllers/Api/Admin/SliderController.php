<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class SliderController extends Controller
{

    public function addBrand(Request $request)
    {
        try {
            $request->validate([
                'content' => 'required'
            ]);
            $slider_id = Slider::query()->insertGetId([
                'content' => $request->content_text,
                'order' => $request->order
            ]);
            if ($request->hasFile('bg_url')) {
                $rand = uniqid();
                $image = $request->file('bg_url');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/images/Slider/'), $image_name);
                $image_path = "/images/Slider/" . $image_name;
                Slider::query()->where('id',$slider_id)->update([
                    'bg_url' => $image_path
                ]);
            }
            if ($request->hasFile('image_url')) {
                $rand = uniqid();
                $image = $request->file('image_url');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/images/Slider/'), $image_name);
                $image_path = "/images/Slider/" . $image_name;
                Slider::query()->where('id',$slider_id)->update([
                    'image_url' => $image_path
                ]);
            }
            return response(['message' => 'Slider ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001','er' => $throwable->getMessage()]);
        }
    }
}
