<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Service\Master\BranchService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Master\StoreBranchRequest;

class BranchController extends Controller
{
    private $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }
    
    public function index(Request $request)
    {
        // Logic to retrieve and return a list of branches
        $data = $this->branchService->list($request);
        // $data['auth'] = $request->user();
        return response()->json($data, 200);
    }

    public function store(StoreBranchRequest $request)
    {
        try {
            $data = $this->branchService->store($request);
            // $data['auth'] = $request->user();
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => ['An error occurred: ' . $e->getMessage()],
            ], 400);
        }
    }
}
