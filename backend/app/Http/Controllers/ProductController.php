<?php

namespace App\Http\Controllers;

use App\Actions\Product\CreateProductAction;
use App\Actions\Product\ListProductsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(
        private readonly CreateProductAction $createProductAction,
        private readonly ListProductsAction $listProductsAction,
    ) {}

    public function index(): JsonResponse
    {
        $products = $this->listProductsAction->execute();

        return ProductResource::collection($products)->response();
    }

    public function store(CreateProductRequest $request): JsonResponse
    {
        $product = $this->createProductAction->execute($request->toDto());

        return ProductResource::make($product)->response()->setStatusCode(Response::HTTP_CREATED);
    }
}
