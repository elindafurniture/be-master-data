<?php

namespace App\Service\Master;

use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BranchService
{
    public function list($request) 
    {
        $data = Branch::where('deleted_status', 1);

        if (filled(trim($request->order_field)) && filled(trim($request->order_dir))) {
            $data = $data->orderBy($request->order_field, $request->order_dir);
        }

        if (filled(trim($request->search))) {
            $data = $data->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . trim($request->search) . '%')
                      ->orWhere('code', 'like', '%' . trim($request->search) . '%')
                      ->orWhere('alamat', 'like', '%' . trim($request->search) . '%')
                      ->orWhere('phone', 'like', '%' . trim($request->search) . '%');
            });
        }

        $data = $data->orderBy('id', 'desc')->paginate($request->per_page ?? 10);

        return $data;
    }

    public function show($id) 
    {
        $branch = Branch::where('id', $id)
                       ->where('deleted_status', 1)
                       ->first();

        if (!$branch) {
            throw new ModelNotFoundException('Branch not found');
        }

        return $branch;
    }

    public function store($request) 
    {
        try {
            DB::beginTransaction();

            $branch = new Branch();
            $branch->code = $request->code;
            $branch->name = $request->name;
            $branch->alamat = $request->alamat;
            $branch->phone = $request->phone;
            $branch->pic_id = $request->pic_id;
            $branch->deleted_status = 1;

            // Handle logo upload if provided
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $fileName = time() . '_' . uniqid() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $logoPath = $file->storeAs('branch-logos', $fileName, 'public');
                $branch->logo = $logoPath;
            }

            // Set audit fields
            $user = $request->user();
            $branch->created_by = $user ? $user->profile->id : 0;
            $branch->created_by_name = $user ? $user->profile->name : 'System';

            $branch->save();

            DB::commit();

            return $branch;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($request, $id) 
    {
        try {
            DB::beginTransaction();

            $branch = Branch::where('id', $id)
                           ->where('deleted_status', 1)
                           ->first();

            if (!$branch) {
                throw new ModelNotFoundException('Branch not found');
            }

            // Check if code is being changed and if it's unique
            if ($request->code !== $branch->code) {
                $existingBranch = Branch::where('code', $request->code)
                                       ->where('deleted_status', 1)
                                       ->where('id', '!=', $id)
                                       ->first();
                
                if ($existingBranch) {
                    throw new \Exception('Branch code already exists');
                }
            }

            $branch->code = $request->code;
            $branch->name = $request->name;
            $branch->alamat = $request->alamat;
            $branch->phone = $request->phone;
            $branch->pic_id = $request->pic_id;

            // Handle logo upload if provided
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($branch->logo) {
                    Storage::disk('public')->delete($branch->logo);
                }
                
                $file = $request->file('logo');
                $fileName = time() . '_' . uniqid() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $logoPath = $file->storeAs('branch-logos', $fileName, 'public');
                $branch->logo = $logoPath;
            }

            // Set audit fields
            $user = $request->user();
            $branch->updated_by = $user ? $user->profile->id : 0;
            $branch->updated_by_name = $user ? $user->profile->name : 'System';

            $branch->save();

            DB::commit();

            return $branch;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($request, $id) 
    {
        try {
            DB::beginTransaction();

            $branch = Branch::where('id', $id)
                           ->where('deleted_status', 1)
                           ->first();

            if (!$branch) {
                throw new ModelNotFoundException('Branch not found');
            }

            // Delete logo file if exists
            if ($branch->logo) {
                Storage::disk('public')->delete($branch->logo);
            }

            // Set audit fields
            $user = $request->user();
            $branch->deleted_by = $user ? $user->profile->id : 0;
            $branch->deleted_by_name = $user ? $user->profile->name : 'System';
            $branch->deleted_status = null;

            $branch->save();
            $branch->delete(); // Soft delete

            DB::commit();

            return [
                'message' => 'Branch deleted successfully',
                'deleted_branch' => $branch
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}