<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function index(string $model, array $with = []);
}
