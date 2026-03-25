<?php

namespace App\Repositories\Eloquent;

use App\Models\MusicStem;
use App\Models\StemInteraction;
use App\Services\ImgBBService;
use App\Repositories\Contracts\StemRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StemRepository implements StemRepositoryInterface
{
    protected $imgBB;

    public function __construct(ImgBBService $imgBB)
    {
        $this->imgBB = $imgBB;
    }

    public function uploadStem($categoryId, array $data)
    {
        try {
            // Since jQuery sends the Mega Link as a string in 'stem_file'
            $audioPath = $data['stem_file'];

            $imageUrl = null;
            if (isset($data['featured_image']) && $data['featured_image'] instanceof \Illuminate\Http\UploadedFile) {
                $imageUrl = $this->imgBB->upload($data['featured_image']);
            }

            $stem = MusicStem::create([
                'id'               => (string) Str::uuid(), // Ensure UUID is generated if not in boot
                'category_id'      => $categoryId,
                'title'            => $data['title'],
                'artist_name'      => $data['artist_name'] ?? null,
                'album_movie_name' => $data['album_movie_name'] ?? null,
                'language'         => $data['language'] ?? null,
                'description'      => $data['description'] ?? null,
                'tags_keywords'    => $data['tags_keywords'] ?? null,
                'file_name'        => 'External Link',
                'file_path'        => $audioPath, // This saves the Mega Link
                'featured_image'   => $imageUrl,
                'file_size'        => '0 KB', // URL doesn't have a local file size
                'bpm'              => $data['bpm'] ?? null,
                'music_key'        => $data['music_key'] ?? null,
                'mega_link'        => $data['mega_link'] ?? $audioPath,
                'seo_title'        => $data['seo_title'] ?? $data['title'],
                'seo_description'  => $data['seo_description'] ?? Str::limit($data['description'] ?? '', 150),
                'is_public'        => $data['is_public'] ?? true,
                'slug'             => Str::slug($data['title']),
            ]);

            Log::info("Stem uploaded successfully", ['id' => $stem->id]);
            return $stem;
        } catch (\Exception $e) {
            Log::error("Failed to upload stem", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateStem($stemId, array $data)
    {
        try {
            $stem = MusicStem::findOrFail($stemId);

            // Update Audio path if a new link/string is provided
            if (isset($data['stem_file'])) {
                $stem->file_path = $data['stem_file'];
                $stem->mega_link = $data['mega_link'] ?? $data['stem_file'];
            }

            if (isset($data['featured_image']) && $data['featured_image'] instanceof \Illuminate\Http\UploadedFile) {
                $imageUrl = $this->imgBB->upload($data['featured_image']);
                if ($imageUrl) {
                    $stem->featured_image = $imageUrl;
                }
            }

            $stem->update([
                'category_id'      => $data['category_id'] ?? $stem->category_id,
                'title'            => $data['title'] ?? $stem->title,
                'artist_name'      => $data['artist_name'] ?? $stem->artist_name,
                'album_movie_name' => $data['album_movie_name'] ?? $stem->album_movie_name,
                'language'         => $data['language'] ?? $stem->language,
                'description'      => $data['description'] ?? $stem->description,
                'tags_keywords'    => $data['tags_keywords'] ?? $stem->tags_keywords,
                'music_key'        => $data['music_key'] ?? $stem->music_key,
                'seo_title'        => $data['seo_title'] ?? $stem->seo_title,
                'seo_description'  => $data['seo_description'] ?? $stem->seo_description,
                'is_public'        => isset($data['is_public']) ? (bool)$data['is_public'] : $stem->is_public,
                'slug'             => isset($data['title']) ? Str::slug($data['title']) : $stem->slug,
            ]);

            Log::info("Stem updated successfully", ['id' => $stem->id]);
            return $stem;
        } catch (\Exception $e) {
            Log::error("Failed to update stem", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function getLibraryStems($filters = [])
    {
        $query = MusicStem::with('category');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('artist_name', 'LIKE', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (($filters['sort'] ?? '') === 'popular') {
            $query->orderBy('download_count', 'desc');
        } else {
            $query->latest();
        }

        return $query->paginate(20);
    }

    public function logInteraction($stemId, $userId, $type)
    {
        try {
            $interaction = StemInteraction::create([
                'id'      => (string) Str::uuid(),
                'user_id' => $userId,
                'stem_id' => $stemId,
                'type'    => $type,
            ]);

            $column = $type . '_count';
            MusicStem::where('id', $stemId)->increment($column);

            return $interaction;
        } catch (\Exception $e) {
            Log::error("Failed to log interaction", ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function deleteStem($stemId)
    {
        try {
            $stem = MusicStem::findOrFail($stemId);

            if ($stem->file_path) {
                Storage::disk('public')->delete($stem->file_path);
            }

            $stem->delete();
            Log::info("Stem deleted successfully", ['id' => $stemId]);
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to delete stem", ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
