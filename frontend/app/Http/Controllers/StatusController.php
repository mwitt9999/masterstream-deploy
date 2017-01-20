<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStatusPostRequest;
use Illuminate\Http\Request;
use App\Status;

class StatusController extends Controller
{
    protected $status;

    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    public function showStatus() {
        return view('status');
    }

    public function addStatus(StoreStatusPostRequest $request) {
        $this->status->saveStatus($request);
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function updateStatus(StoreStatusPostRequest $request) {
        $this->status->editStatus($request);
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function deleteStatus(Request $request) {
        $this->status->deleteStatus($request->segment(4));
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function getAllStatus() {
        return $this->status->getStatusDataTable();
    }

}
