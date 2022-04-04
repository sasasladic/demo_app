<?php

namespace App\Repositories\Implementation;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{

    public function index(string $model, array $with = [], bool $paginate = false)
    {
        /** @var Model $model */
        $query = $model::withoutGlobalScopes();
        if (!empty($with)) {
            $query->with($with);
        }

        return $paginate ? $query->paginate(15) : $query->get();
    }
}
