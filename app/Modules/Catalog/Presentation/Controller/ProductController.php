<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Catalog\Application\Services\ProductService;

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