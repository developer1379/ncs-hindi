<?php

namespace App\Repositories\Eloquent;

use App\Models\Language;
use App\Repositories\Contracts\LanguageRepositoryInterface;
use Illuminate\Support\Str;

class EloquentLanguageRepository implements LanguageRepositoryInterface
{
    public function getAll()
    {
        return Language::orderBy('name')->get();
    }

    public function findById($id)
    {
        return Language::findOrFail($id);
    }

    public function create(array $data)
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        return Language::create($data);
    }

    public function update($id, array $data)
    {
        $language = Language::findOrFail($id);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $language->update($data);
        return $language;
    }

    public function delete($id)
    {
        $language = Language::findOrFail($id);
        return $language->delete();
    }
}
