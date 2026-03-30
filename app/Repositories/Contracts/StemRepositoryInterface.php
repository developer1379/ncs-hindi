<?php

namespace App\Repositories\Contracts;

interface StemRepositoryInterface
{
    public function getLibraryStems($filters = []);
    public function getTrendingStems($filters = []);
    public function getTrendingSpotlight($filters = []);
    public function getTrendingCreators($filters = [], $limit = 6);
    public function getTrendingStats($filters = []);
    public function uploadStem($categoryId, array $data);
    public function updateStem($stemId, array $data); // Added this
    public function logInteraction($stemId, $userId, $type);
    public function deleteStem($stemId);
}
