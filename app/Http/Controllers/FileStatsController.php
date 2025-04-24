<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileStatsResource;
use App\Models\UploadSession;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FileStatsController extends Controller
{
    public function stats(Request $request)
    {
        try {
            $validated = $request->validate([
                'token' => 'required|uuid'
            ]);


            $session = UploadSession::with('files')
                ->where('token', $validated['token'])
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => new FileStatsResource($session)
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input',
                'errors' => $e->errors()
            ], 422);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload session not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
