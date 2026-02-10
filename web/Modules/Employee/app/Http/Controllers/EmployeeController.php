<?php

namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Employee\Http\Requests\StoreEmployeeRequest;
use Modules\Employee\Http\Requests\UpdateEmployeeRequest;
use Modules\Employee\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Employee::with(['assignments.institution'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('institution'), function ($q) use ($request) {
                $q->inInstitution($request->institution);
            })
            ->latest();

        $employees = $query->paginate(15)->withQueryString();

        return view('employee::index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('employee::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('employees/photos', 'public');
        }

        // Handle documents upload
        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $document) {
                $documents[] = $document->store('employees/documents', 'public');
            }
            $data['documents_path'] = $documents;
        }

        $employee = Employee::create($data);

        return redirect()
            ->route('employee.show', $employee)
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    /**
     * Show the specified resource.
     */
    public function show(Employee $employee): View
    {
        $employee->load(['user', 'assignments.institution']);

        return view('employee::show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee): View
    {
        return view('employee::edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($employee->photo_path) {
                Storage::disk('public')->delete($employee->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('employees/photos', 'public');
        }

        // Handle documents upload
        if ($request->hasFile('documents')) {
            $documents = $employee->documents_path ?? [];
            foreach ($request->file('documents') as $document) {
                $documents[] = $document->store('employees/documents', 'public');
            }
            $data['documents_path'] = $documents;
        }

        $employee->update($data);

        return redirect()
            ->route('employee.show', $employee)
            ->with('success', 'Pegawai berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        // Soft delete
        $employee->delete();

        return redirect()
            ->route('employee.index')
            ->with('success', 'Pegawai berhasil dihapus.');
    }
}
