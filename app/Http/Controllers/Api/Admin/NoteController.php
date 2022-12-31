<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Note;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class NoteController extends Controller
{
    //Notes
    public function getNotes()
    {
        try {
            $notes = Note::query()->where('active',1)->get();
            foreach ($notes as $note){
                $note['company'] = Company::query()->where('id', $note->company_id)->first();
                $note['employee'] = Employee::query()->where('id', $note->employee_id)->first();
                $admin = Admin::query()->where('id', $note->user_id)->first();
                $note['user_name'] = $admin->name.' '.$admin->surname;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['notes' => $notes]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getNotesByCompanyId($company_id)
    {
        try {
            $notes = Note::query()->where('active',1)->where('company_id', $company_id)->orderByDesc('created_at', 'updated_at')->get();
            foreach ($notes as $note){
                $note['company'] = Company::query()->where('id', $note->company_id)->first();
                $note['employee'] = Employee::query()->where('id', $note->employee_id)->first();
                $admin = Admin::query()->where('id', $note->user_id)->first();
                $note['user_name'] = $admin->name.' '.$admin->surname;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['notes' => $notes]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getNoteById($note_id)
    {
        try {
            $note = Note::query()->where('id', $note_id)->where('active',1)->first();
            $note['company'] = Company::query()->where('id', $note->company_id)->first();
            $note['employee'] = Employee::query()->where('id', $note->employee_id)->first();
            $admin = Admin::query()->where('id', $note->user_id)->first();
            $note['user_name'] = $admin->name.' '.$admin->surname;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['note' => $note]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addNote(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'description' => 'required',
                'company_id' => 'required',
                'employee_id' => 'required',
            ]);
            $note_id = Note::query()->insertGetId([
                'user_id' => $request->user_id,
                'description' => $request->description,
                'company_id' => $request->company_id,
                'employee_id' => $request->employee_id,
            ]);
            if ($request->hasFile('image')) {
                $rand = uniqid();
                $image = $request->file('image');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/img/note/'), $image_name);
                $image_path = "/img/note/" . $image_name;
                Note::query()->where('id',$note_id)->update([
                    'image' => $image_path
                ]);
            }

            return response(['message' => __('Not ekleme işlemi başarılı.'), 'status' => 'success', 'object' => ['note_id' => $note_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateNote(Request $request,$note_id){
        try {
            $request->validate([
                'user_id' => 'required',
                'description' => 'required',
                'company_id' => 'required',
                'employee_id' => 'required',
            ]);

            Note::query()->where('id', $note_id)->update([
                'user_id' => $request->user_id,
                'description' => $request->description,
                'company_id' => $request->company_id,
                'employee_id' => $request->employee_id,
            ]);
            if ($request->hasFile('image')) {
                $rand = uniqid();
                $image = $request->file('image');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/img/note/'), $image_name);
                $image_path = "/img/note/" . $image_name;
                Note::query()->where('id',$note_id)->update([
                    'image' => $image_path
                ]);
            }

            return response(['message' => __('Not güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deleteNote($note_id){
        try {

            Note::query()->where('id',$note_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Not silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
