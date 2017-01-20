<?php

namespace App\Http\Controllers;

use App\Operation;

class HistoryController extends Controller
{
    protected $operation;

    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }

    public function getAllOperationHistory() {
        return $this->operation->getOperationHistoryDataTable();
    }

    public function showHistory() {
        return view('history');
    }
}