<?php

namespace App\Domain\Shared\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $companyId = static::resolveCompanyId();

        if ($companyId) {
            $builder->where($model->getTable().'.company_id', $companyId);
        }
    }

    public static function resolveCompanyId(): ?int
    {
        $user = auth()->user();

        if ($user && method_exists($user, 'getAttribute')) {
            return $user->getAttribute('company_id');
        }

        return null;
    }
}
