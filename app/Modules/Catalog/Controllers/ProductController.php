<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request, ProductService $service)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'sku' => 'required|string',
        ]);

        return $service->create($data);
    }

    public function index(ProductService $service)
    {
        return $service->list();
    }
}
