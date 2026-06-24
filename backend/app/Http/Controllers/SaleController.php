<?php

namespace App\Http\Controllers;

use App\Actions\Sale\CancelSaleAction;
use App\Actions\Sale\CreateSaleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sale\CreateSaleRequest;
use App\Http\Requests\Sale\UpdateSaleRequest;
use App\Http\Resources\Sale\CancelledSaleResource;
use App\Http\Resources\Sale\SaleResource;
use App\Models\Sale\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SaleController extends Controller
{
    public function __construct(
        private readonly CreateSaleAction $createSaleAction,
        private readonly CancelSaleAction $cancelSaleAction,
    ) {}

    public function store(CreateSaleRequest $request): JsonResponse
    {
        $result = $this->createSaleAction->execute($request->toDto());

        return SaleResource::make($result)->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateSaleRequest $request, Sale $venda): JsonResponse
    {
        $result = $this->cancelSaleAction->execute($venda);

        return CancelledSaleResource::make($result)->response()->setStatusCode(Response::HTTP_OK);
    }
}
