<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Service\Master\BranchService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Master\StoreBranchRequest;
use App\Http\Requests\Master\UpdateBranchRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BranchController extends Controller
{
    private $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }
    
    public function index(Request $request)
    {
        try {
            $data = $this->branchService->list($request);
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve branches: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $data = $this->branchService->show($id);
            return response()->json($data, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve branch: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(StoreBranchRequest $request)
    {
        try {
            $data = $this->branchService->store($request);
            return response()->json([
                'status' => 'success',
                'message' => 'Branch created successfully',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create branch: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function update(UpdateBranchRequest $request, $id)
    {
        try {
            $data = $this->branchService->update($request, $id);
            return response()->json([
                'status' => 'success',
                'message' => 'Branch updated successfully',
                'data' => $data
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update branch: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $data = $this->branchService->destroy($request, $id);
            return response()->json([
                'status' => 'success',
                'message' => $data['message'],
                'data' => $data['deleted_branch']
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete branch: ' . $e->getMessage(),
            ], 500);
        }
    }
}
