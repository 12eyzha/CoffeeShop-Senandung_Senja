<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // 🔹 Tampilkan daftar karyawan (dengan pencarian + pagination)
    public function index(Request $request)
    {
        $search = $request->input('search');

        $employees = Employee::when($search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', compact('employees', 'search'));
    }

    // 🔹 Form tambah karyawan
    public function create()
    {
        return view('employees.create');
    }

    // 🔹 Simpan data karyawan baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:employees,email',
            'phone_number' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'position' => 'nullable|string|max:100',
            'join_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'status' => 'in:active,inactive',
            'password' => 'required|string|min:6',
        ]);

        // Tidak perlu Hash::make(), model Employee akan otomatis hash password
        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    // 🔹 Tampilkan detail (opsional)
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    // 🔹 Form edit karyawan
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    // 🔹 Update data karyawan
    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:employees,email,' . $employee->id,
            'phone_number' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'position' => 'nullable|string|max:100',
            'join_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'status' => 'in:active,inactive',
            'password' => 'nullable|string|min:6',
        ]);

        // Jika password kosong, jangan ubah
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    // 🔹 Hapus karyawan
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
