<?php

namespace Juzaweb\Proxies\Repositories;

use Juzaweb\CMS\Repositories\BaseRepositoryEloquent;
use Juzaweb\CMS\Traits\Criterias\UseFilterCriteria;
use Juzaweb\CMS\Traits\Criterias\UseSearchCriteria;
use Juzaweb\CMS\Traits\Criterias\UseSortableCriteria;
use Juzaweb\CMS\Traits\ResourceRepositoryEloquent;
use Juzaweb\Proxies\Models\Proxy;

/**
 * Class ProxyRepositoryEloquent.
 *
 * @package namespace Juzaweb\Proxies\Repositorys;
 */
class ProxyRepositoryEloquent extends BaseRepositoryEloquent implements ProxyRepository
{
    use ResourceRepositoryEloquent, UseSearchCriteria, UseFilterCriteria, UseSortableCriteria;

    protected array $searchableFields = ['ip', 'port'];

    protected array $filterableFields = ['is_free', 'active'];

    protected array $sortableFields = ['is_free', 'active', 'protocol', 'port', 'updated_at', 'created_at'];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Proxy::class;
    }
}
