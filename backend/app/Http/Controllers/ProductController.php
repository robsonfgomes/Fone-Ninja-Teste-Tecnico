<?php

namespace App\Http\Controllers;

use App\Actions\Product\CreateProductAction;
use App\Actions\Product\FilterProductsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\FilterProductsRequest;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(
        private readonly CreateProductAction $createProductAction,
        private readonly FilterProductsAction $filterProductsAction,
    ) {}

    public function index(FilterProductsRequest $request): JsonResponse
    {
        $products = $this->filterProductsAction->execute($request->toDto());

        return ProductResource::collection($products)->response();
    }

    public function store(CreateProductRequest $request): JsonResponse
    {
        $product = $this->createProductAction->execute($request->toDto());

        return ProductResource::make($product)->response()->setStatusCode(Response::HTTP_CREATED);
    }
}
