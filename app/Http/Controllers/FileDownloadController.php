<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateDownloadRequest;
use App\Services\FileDownloadService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileDownloadController extends Controller
{
    protected $downloadService;

    public function __construct(FileDownloadService $downloadService)
    {
        $this->downloadService = $downloadService;
    }

    public function download(string $token): StreamedResponse|BinaryFileResponse
    {
        return $this->downloadService->handleDownload($token, false);
    }

    public function secureDownload(string $token): StreamedResponse|BinaryFileResponse
    {
        return $this->downloadService->handleDownload($token, true);
    }

    public function authenticate(string $token, AuthenticateDownloadRequest $request)
    {
        try {
            $result = $this->downloadService->authenticateDownload(
                $token,
                $request->input('password')
            );

            return response()->json([
                'success' => true,
                ...$result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
}
