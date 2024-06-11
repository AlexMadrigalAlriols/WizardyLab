<?php

namespace App\Models\Scopes;

use App\Helpers\SubdomainHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PortalScope implements Scope
{
    public function __construct(
        protected ?int $portalId = null,
        protected $userRelation = false
    ){}

    public function apply(Builder $builder, Model $model)
    {
        if(!$this->portalId) {
            $portal = SubdomainHelper::getPortal(request());
            $this->portalId = $portal->id;
        }

        if($this->userRelation) {
            $builder->whereHas('user', function($query) {
                $query->where('portal_id', $this->portalId);
            });
        } else {
            $builder->where('portal_id', $this->portalId);
        }
    }
}
