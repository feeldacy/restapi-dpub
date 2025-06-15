<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreGroundRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Detail Tanah Validate Data Rules
            'nama_tanah' => 'required|string',
            'luas_tanah' => 'required|numeric',

            // Marker Tanah Validate Data Rules
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',

            // Polygon Tanah Validate Data Rule
            'coordinates' => 'nullable|json',

            // Alamat Tanah Validate Data Rules
            'detail_alamat' => 'required|string',
            'rt' => 'required|string',
            'rw' => 'required|string',
            'padukuhan' => 'required|string',

            // Foto Tanah Validate Data Rules
            'foto_tanah' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',

            // Sertifikat Validate Data Rules
            'sertifikat_tanah' => 'nullable|file|mimes:pdf,doc,docx|max:5120',

            // Status Kepemilikan Validate Data Rules
            'status_kepemilikan_id' => 'required|string',

            // Status Tanah Validate Data Rules
            'status_tanah_id' => 'required|string',

            // Tipe Tanah Validate Data Rules
            'tipe_tanah_id' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            // Detail Tanah
            'nama_tanah.required' => 'Nama tanah wajib diisi.',
            'nama_tanah.string' => 'Nama tanah harus berupa teks.',
            'luas_tanah.required' => 'Luas tanah wajib diisi.',
            'luas_tanah.numeric' => 'Luas tanah harus berupa angka.',

            // Marker Tanah
            'latitude.required' => 'Latitude wajib diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'longitude.required' => 'Longitude wajib diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka.',

            // Polygon
            'coordinates.json' => 'Data koordinat harus dalam format JSON yang valid.',

            // Alamat Tanah
            'detail_alamat.required' => 'Detail alamat wajib diisi.',
            'detail_alamat.string' => 'Detail alamat harus berupa teks.',
            'rt.required' => 'RT wajib diisi.',
            'rt.string' => 'RT harus berupa teks.',
            'rw.required' => 'RW wajib diisi.',
            'rw.string' => 'RW harus berupa teks.',
            'padukuhan.required' => 'Padukuhan wajib diisi.',
            'padukuhan.string' => 'Padukuhan harus berupa teks.',

            // Foto Tanah
            'foto_tanah.file' => 'Foto tanah harus berupa file.',
            'foto_tanah.image' => 'Foto tanah harus berupa gambar.',
            'foto_tanah.mimes' => 'Foto tanah harus berformat jpeg, png, atau jpg.',
            'foto_tanah.max' => 'Ukuran foto tanah maksimal 2MB.',

            // Sertifikat
            'sertifikat_tanah.file' => 'Sertifikat harus berupa file.',
            'sertifikat_tanah.mimes' => 'Sertifikat harus berformat PDF, DOC, atau DOCX.',
            'sertifikat_tanah.max' => 'Ukuran sertifikat maksimal 5MB.',

            // Status Kepemilikan
            'status_kepemilikan_id.required' => 'Status kepemilikan wajib dipilih.',
            'status_kepemilikan_id.string' => 'Status kepemilikan harus berupa teks.',

            // Status Tanah
            'status_tanah_id.required' => 'Status tanah wajib dipilih.',
            'status_tanah_id.string' => 'Status tanah harus berupa teks.',

            // Tipe Tanah
            'tipe_tanah_id.required' => 'Tipe tanah wajib dipilih.',
            'tipe_tanah_id.string' => 'Tipe tanah harus berupa teks.',
        ];
    }

    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422));
    }
}
