<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\LanguageRepositoryInterface;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    protected $languageRepo;

    public function __construct(LanguageRepositoryInterface $languageRepo)
    {
        $this->languageRepo = $languageRepo;
    }

    public function index()
    {
        $languages = $this->languageRepo->getAll();
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:languages,slug',
            'is_active' => 'sometimes|boolean',
        ]);
        
        if (!$request->has('is_active')) {
            $data['is_active'] = 0;
        }

        $this->languageRepo->create($data);
        return redirect()->route('admin.languages.index')->with('success', 'Language created successfully.');
    }

    public function edit(string $id)
    {
        $language = $this->languageRepo->findById($id);
        if (!$language) return back()->with('error', 'Language not found.');
        
        return view('admin.languages.edit', compact('language'));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:languages,slug,'.$id,
            'is_active' => 'sometimes|boolean',
        ]);
        
        if (!$request->has('is_active')) {
            $data['is_active'] = 0;
        }

        $this->languageRepo->update($id, $data);
        return redirect()->route('admin.languages.index')->with('success', 'Language updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->languageRepo->delete($id);
        return redirect()->route('admin.languages.index')->with('success', 'Language deleted successfully.');
    }
}
