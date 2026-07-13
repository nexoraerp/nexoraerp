<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToCurrentUser
{
    public static function bootBelongsToCurrentUser(): void
    {
        static::addGlobalScope('current_user', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where(
                    $builder->getModel()->getTable().'.'.$builder->getModel()->tenantUserColumn(),
                    auth()->user()->tenantOwnerId()
                );
            }
        });

        static::creating(function ($model) {
            $column = $model->tenantUserColumn();

            if (auth()->check() && empty($model->{$column})) {
                $model->{$column} = auth()->user()->tenantOwnerId();
            }
        });
    }

    public function tenantUserColumn(): string
    {
        return property_exists($this, 'tenantUserColumn')
            ? $this->tenantUserColumn
            : 'user_id';
    }
}
