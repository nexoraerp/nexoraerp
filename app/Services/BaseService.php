<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseService
{
    protected Model $model;

    public function all(): Collection
    {
        return $this->model->newQuery()->get();
    }

    public function paginate(int $perPage = 15)
    {
        return $this->model
            ->newQuery()
            ->paginate($perPage);
    }

    public function find(int $id): ?Model
    {
        return $this->model
            ->newQuery()
            ->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model
            ->newQuery()
            ->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        $model = $this->find($id);

        if (!$model) {
            return null;
        }

        $model->update($data);

        return $model->fresh();
    }

    public function delete(int $id): bool
    {
        $model = $this->find($id);

        if (!$model) {
            return false;
        }

        return (bool) $model->delete();
    }

    public function exists(int $id): bool
    {
        return $this->model
            ->newQuery()
            ->whereKey($id)
            ->exists();
    }

    public function count(): int
    {
        return $this->model
            ->newQuery()
            ->count();
    }
}