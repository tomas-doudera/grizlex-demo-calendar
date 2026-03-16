<?php

namespace App\Domain\Shared\Concerns;

use App\Domain\Shared\Models\Company;
use App\Domain\Shared\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Model
 */
trait BelongsToCompany
{
    public static function bootBelongsToCompany(): void
    {
        static::addGlobalScope(new CompanyScope);

        static::creating(function (Model $model) {
            if (! $model->getAttribute('company_id')) {
                $companyId = CompanyScope::resolveCompanyId();
                if ($companyId) {
                    $model->setAttribute('company_id', $companyId);
                }
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
