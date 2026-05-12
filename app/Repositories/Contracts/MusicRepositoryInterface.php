<?php

namespace App\Repositories\Contracts;

interface MusicRepositoryInterface
{
    public function getLibraryMusic($filters = []);
    public function getTrendingMusic($filters = []);
    public function getTrendingSpotlight($filters = []);
    public function getTrendingCreators($filters = [], $limit = 6);
    public function getTrendingStats($filters = []);
    public function uploadMusic($categoryId, array $data);
    public function updateMusic($musicId, array $data);
    public function logInteraction($musicId, $userId, $type);
    public function deleteMusic($musicId);
}







