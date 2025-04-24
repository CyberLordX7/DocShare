<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Http\Resources\UploadSessionResource;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function __construct(protected FileUploadService $fileUploadService) {
        //
    }

    protected $middleware = [
        'auth:sanctum' => ['except' => ['store']],
    ];

    public function store(FileUploadRequest $request)
    {
        $request->validateGuestRestrictions();
        $validated = $request->validated();

        try {
            $response = $this->fileUploadService->handleUpload($validated, false);
            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File upload failed',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'error_code' => 'UPLOAD_FAILED'
            ], 500);
        }
    }

    public function secureStore(FileUploadRequest $request)
    {
        $request->validateGuestRestrictions();
        $validated = $request->validated();

        try {
            $response = $this->fileUploadService->handleUpload($validated, true);
            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File upload failed',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'error_code' => 'UPLOAD_FAILED'
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $sessions = $request->user()
            ->uploadSessions()
            ->with('files')
            ->latest()
            ->paginate($request->input('per_page', 10));

        return UploadSessionResource::collection($sessions);
    }
}
