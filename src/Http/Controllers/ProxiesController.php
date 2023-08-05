<?php

namespace Juzaweb\Proxies\Http\Controllers;

use Juzaweb\CMS\Http\Controllers\BackendController;

class ProxiesController extends BackendController
{
    public function index()
    {
        //

        return view(
            'jupr::index',
            [
                'title' => 'Title Page',
            ]
        );
    }
}
