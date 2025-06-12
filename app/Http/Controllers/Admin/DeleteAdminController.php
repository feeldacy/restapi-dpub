<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DeleteAdminController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $id)
    {
        try {
            $admin = User::findOrFail($id);

            $admin->delete();

            return response()->json([
                'message' => 'Admin berhasil dihapus.',
                'status' => 'success',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi data admin gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal query database',
                'error' => $e->getMessage(),
            ], 500); 
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus admin.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
