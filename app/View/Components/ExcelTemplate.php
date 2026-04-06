<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ExcelTemplate extends Component
{
    public $headers;
    public $sampleRows;

    public function __construct($headers = [], $sampleRows = [])
    {
        $this->headers = $headers;
        $this->sampleRows = $sampleRows;
    }

    public function render()
    {
        return view('components.excel-template');
    }
}
