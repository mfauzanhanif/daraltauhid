<?php

namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\EmployeeAssignment;
use Modules\Employee\Http\Requests\StoreAssignmentRequest;

class EmployeeAssignmentController extends Controller
{
    /**
     * Display a listing of assignments for an employee.
     */
    public function index(Employee $employee): View
    {
        $assignments = $employee->assignments()
            ->with('institution')
            ->latest()
            ->paginate(10);

        return view('employee::assignments.index', compact('employee', 'assignments'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create(Employee $employee): View
    {
        return view('employee::assignments.create', compact('employee'));
    }

    /**
     * Store a newly created assignment.
     */
    public function store(StoreAssignmentRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->validated();
        $data['employee_id'] = $employee->id;

        EmployeeAssignment::create($data);

        return redirect()
            ->route('employee.assignments.index', $employee)
            ->with('success', 'Penugasan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing an assignment.
     */
    public function edit(Employee $employee, EmployeeAssignment $assignment): View
    {
        return view('employee::assignments.edit', compact('employee', 'assignment'));
    }

    /**
     * Update the specified assignment.
     */
    public function update(StoreAssignmentRequest $request, Employee $employee, EmployeeAssignment $assignment): RedirectResponse
    {
        $assignment->update($request->validated());

        return redirect()
            ->route('employee.assignments.index', $employee)
            ->with('success', 'Penugasan berhasil diperbarui.');
    }

    /**
     * Remove the specified assignment.
     */
    public function destroy(Employee $employee, EmployeeAssignment $assignment): RedirectResponse
    {
        $assignment->delete();

        return redirect()
            ->route('employee.assignments.index', $employee)
            ->with('success', 'Penugasan berhasil dihapus.');
    }

    /**
     * Toggle active status of an assignment.
     */
    public function toggleActive(Employee $employee, EmployeeAssignment $assignment): RedirectResponse
    {
        $assignment->update(['is_active' => !$assignment->is_active]);

        $status = $assignment->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('employee.assignments.index', $employee)
            ->with('success', "Penugasan berhasil {$status}.");
    }
}
