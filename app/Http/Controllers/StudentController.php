<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::paginate(4); // Menampilkan mahasiswa dengan pagination
        return view('student.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('student.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input untuk kolom yang baru ditambahkan, termasuk foto
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:students', // Pastikan NIM unik
            'kelas' => 'required|string|max:10',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
        ]);

        // Membuat instance baru dan menyimpan data mahasiswa
        $student = new Student();
        $student->fill($validated);

        // Proses upload foto jika ada
        if ($request->hasFile('profile_image')) {
            // Menyimpan gambar ke folder 'public/profile_images'
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            // Simpan path foto ke dalam database
            $student->profile_image = $imagePath;
        }

        // Simpan data mahasiswa
        $student->save();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('student.index')->with('success', 'Data mahasiswa berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return redirect()->route('student.index')->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        return view('student.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input untuk kolom yang baru ditambahkan, termasuk foto
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:students,nim,' . $id, // Validasi NIM unik, kecuali untuk data yang sedang diedit
            'kelas' => 'required|string|max:10',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            
        ]);

        // Temukan mahasiswa yang akan diperbarui
        $student = Student::find($id);
        if (!$student) {
            return redirect()->route('student.index')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Perbarui data mahasiswa
        $student->fill($validated);{
        }

        // Simpan data mahasiswa
        $student->save();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('student.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Temukan mahasiswa yang akan dihapus
        $student = Student::find($id);

        // Jika mahasiswa ditemukan, hapus foto (jika ada) dan data
        if ($student) {
            
            // Hapus data mahasiswa
            $student->delete();
            return redirect()->route('student.index')->with('success', 'Data mahasiswa berhasil dihapus.');
        }

        return redirect()->route('student.index')->with('error', 'Data mahasiswa tidak ditemukan.');
    }
}
