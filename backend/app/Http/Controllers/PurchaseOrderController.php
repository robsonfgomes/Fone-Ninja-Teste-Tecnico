<?php

namespace App\Http\Controllers;

use App\Actions\PurchaseOrder\CreatePurchaseOrderAction;
use App\Actions\PurchaseOrder\FilterPurchaseOrdersAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrder\CreatePurchaseOrderRequest;
use App\Http\Requests\PurchaseOrder\FilterPurchaseOrdersRequest;
use App\Http\Resources\PurchaseOrder\PurchaseOrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PurchaseOrderController extends Controller
{
    public function __construct(
        private readonly CreatePurchaseOrderAction $createPurchaseOrderAction,
        private readonly FilterPurchaseOrdersAction $filterPurchaseOrdersAction,
    ) {}

    public function index(FilterPurchaseOrdersRequest $request): JsonResponse
    {
        $orders = $this->filterPurchaseOrdersAction->execute($request->toDto());

        return PurchaseOrderResource::collection($orders)->response();
    }

    public function store(CreatePurchaseOrderRequest $request): JsonResponse
    {
        $order = $this->createPurchaseOrderAction->execute($request->toDto());

        return PurchaseOrderResource::make($order)->response()->setStatusCode(Response::HTTP_CREATED);
    }
}
