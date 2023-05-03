<?php

namespace App\Http\Controllers\Api;

use App\Actions\Reports\GenerateCSV;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportsController extends Controller
{
    public function __invoke(Request $request, GenerateCSV $csv) /* : Response|BinaryFileResponse|StreamedResponse|JsonResponse */
    {
        try {
            return $csv->handle($request);
            // return $csv->query($request->get('type'))->get();
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], $err->getCode());
        }
    }
}
