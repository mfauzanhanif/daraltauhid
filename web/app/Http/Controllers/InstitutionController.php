<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstitutionRequest;
use App\Http\Requests\UpdateInstitutionRequest;
use App\Models\Institution;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstitutionController extends Controller
{
    /**
     * Display a listing of institutions.
     */
    public function index()
    {
        $institutions = Institution::with('parent')
            ->orderBy('created_at', 'desc')
            ->get();

        // Build tree structure
        $tree = $this->buildTree($institutions);

        return Inertia::render('Yayasan/Institution/Index', [
            'institutions' => $institutions,
            'tree' => $tree,
        ]);
    }

    /**
     * Show the form for creating a new institution.
     */
    public function create()
    {
        $parentInstitutions = Institution::whereNull('parent_id')
            ->orWhere('category', 'YAYASAN')
            ->get(['id', 'name', 'category']);

        return Inertia::render('Yayasan/Institution/Create', [
            'parentInstitutions' => $parentInstitutions,
        ]);
    }

    /**
     * Store a newly created institution in storage.
     */
    public function store(StoreInstitutionRequest $request)
    {
        $institution = Institution::create($request->validated());

        return redirect()
            ->route('institutions.index')
            ->with('success', 'Lembaga berhasil ditambahkan.');
    }

    /**
     * Display the specified institution.
     */
    public function show(Institution $institution)
    {
        $institution->load(['parent', 'children', 'roles']);

        return Inertia::render('Yayasan/Institution/Show', [
            'institution' => $institution,
        ]);
    }

    /**
     * Show the form for editing the specified institution.
     */
    public function edit(Institution $institution)
    {
        $parentInstitutions = Institution::where('id', '!=', $institution->id)
            ->where(function ($query) use ($institution) {
                $query->whereNull('parent_id')
                    ->orWhere('category', 'YAYASAN')
                    ->orWhere('id', $institution->parent_id);
            })
            ->get(['id', 'name', 'category']);

        return Inertia::render('Yayasan/Institution/Edit', [
            'institution' => $institution,
            'parentInstitutions' => $parentInstitutions,
        ]);
    }

    /**
     * Update the specified institution in storage.
     */
    public function update(UpdateInstitutionRequest $request, Institution $institution)
    {
        $institution->update($request->validated());

        return redirect()
            ->route('institutions.index')
            ->with('success', 'Lembaga berhasil diperbarui.');
    }

    /**
     * Remove the specified institution from storage.
     */
    public function destroy(Institution $institution)
    {
        // Check if institution has children
        if ($institution->hasChildren()) {
            return back()->with('error', 'Tidak dapat menghapus lembaga yang memiliki sub-lembaga.');
        }

        $institution->delete();

        return redirect()
            ->route('institutions.index')
            ->with('success', 'Lembaga berhasil dihapus.');
    }

    /**
     * Build hierarchical tree structure from flat collection.
     */
    private function buildTree($institutions, $parentId = null)
    {
        $branch = [];

        foreach ($institutions as $institution) {
            if ($institution->parent_id == $parentId) {
                $children = $this->buildTree($institutions, $institution->id);

                $node = [
                    'id' => $institution->id,
                    'name' => $institution->name,
                    'code' => $institution->code,
                    'category' => $institution->category,
                    'type' => $institution->type,
                    'is_internal' => $institution->is_internal,
                    'is_active' => $institution->is_active,
                ];

                if ($children) {
                    $node['children'] = $children;
                }

                $branch[] = $node;
            }
        }

        return $branch;
    }
}
