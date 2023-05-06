<?php

namespace App\Http\Controllers\Api;

use App\Actions\Reports\GenerateCSV;
use App\Actions\Reports\GeneratePDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportsController extends Controller
{
    public function csv(Request $request, GenerateCSV $csv): mixed
    {
        try {
            return $csv->handle($request);
        } catch (\Exception $err) {
            return response()->json(['error' => $err->getMessage(), 'trace' => $err->getTrace(),]);
        }
    }
    public function pdf(Request $request, GeneratePDF $generatePDF): mixed
    {
        try {
            return $generatePDF->handle($request);
        } catch (\Exception $err) {
            return response()->json(['error' => $err->getMessage()]);
        }
    }
    public function view(Request $request, GeneratePDF $generatePDF): mixed
    {
        try {
            return $generatePDF->handle($request);
        } catch (\Exception $err) {
            return response()->json(['error' => $err->getMessage()]);
        }
    }
}
