<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;


class EditAdminController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $id)
    {
        try {
            $admin = User::findOrFail($id);

            $validateData = $request->validate([
                'email' => 'email',
                'name' => 'string'
            ]);

            $admin->update($validateData);

            return response()->json([
                'message' => 'Admin data updated successfully',
                'data' => $admin
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Admin not found'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating admin data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
