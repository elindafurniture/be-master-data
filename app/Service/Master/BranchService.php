<?php

namespace App\Service\Master;

use App\Models\Branch;

class BranchService
{
    public function list ($request) {
        $data = new Branch();

        if (filled(trim($request->order_field)) && filled(trim($request->order_dir))) {
            $data = $data->orderBy($request->order_field, $request->order_dir);
        }

        if (filled(trim($request->search))) {
            $data = $data->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . trim($request->search) . '%')
                      ->orWhere('code', 'like', '%' . trim($request->search) . '%');
            });
        }

        $data = $data->orderBy('id', 'desc')->paginate($request->per_page);

        return $data;
    }

    public function store($request) {
        $branch = new Branch();
        $branch->code = $request->code;
        $branch->name = $request->name;
        $branch->alamat = $request->alamat;
        $branch->phone = $request->phone;
        $branch->pic_id = $request->pic_id;
        $branch->deleted_status = 1;

        $user = $request->user();
        $branch->created_by = $user ? $user->profile->id : 0;
        $branch->created_by_name = $user ? $user->profile->name : 'System';

        $branch->save();

        return $branch;
    }
}