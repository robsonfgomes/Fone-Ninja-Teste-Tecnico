# Design: Endpoint de Registro de Compra

**Data:** 2026-06-24  
**Rota:** `POST /api/compras`

---

## Contexto

Criação do endpoint de registro de compras seguindo o padrão já adotado para Produtos:  
`Controller → Action → DTO ← Request`, com `Resource` para a resposta.

---

## Payload de Entrada

```json
{
  "supplier": "Fornecedor X",
  "products": [
    { "id": "uuid", "quantity": 50, "unit_price": 20.00 },
    { "id": "uuid", "quantity": 30, "unit_price": 10.00 }
  ]
}
```

---

## Regras de Negócio

### Atualização de Estoque
- Cada produto listado tem seu `current_stock` incrementado pela `quantity` informada.
- Operação: `current_stock += quantity`.

### Custo Médio Ponderado
- Recalculado **apenas em compras** (entradas de mercadoria).
- Vendas e cancelamentos de venda apenas movimentam quantidade — o custo médio não muda.
- Fórmula:
  ```
  new_average_cost = (current_stock * (average_cost ?? 0) + quantity * unit_price)
                     / (current_stock + quantity)
  ```
- Se `average_cost` for `null` (produto sem histórico de compra), o custo anterior contribui com 0.

---

## Arquitetura

```
POST /api/compras
  └── PurchaseOrderController::store()
        └── CreatePurchaseOrderRequest::toDto() → CreatePurchaseOrderDto
              └── CreatePurchaseOrderAction::execute()
                    ├── DB::transaction()
                    │     ├── PurchaseOrder::create()
                    │     └── foreach item:
                    │           ├── Product::findOrFail(productId)
                    │           ├── PurchaseOrderItem::create()
                    │           ├── UpdateProductAverageCostAction::execute(product, quantity, unitPrice)
                    │           └── UpdateProductStockAction::execute(product, quantity)
                    └── return PurchaseOrder->load('items')
  └── PurchaseOrderResource → { purchaseOrderId, totalAmount, createdAt, updatedAt }
```

### Ordem das Actions por Item
A `UpdateProductAverageCostAction` é executada **antes** da `UpdateProductStockAction` porque a fórmula de custo médio usa o `current_stock` anterior à entrada da nova quantidade.

---

## Actions

### `CreatePurchaseOrderAction`
Orquestra o fluxo completo dentro de uma `DB::transaction`. Injeta as duas sub-actions.

### `UpdateProductAverageCostAction`
- Exclusiva do fluxo de compra.
- Recalcula e persiste `average_cost` no produto.
- Executa antes da atualização de estoque.

### `UpdateProductStockAction`
- Genérica e reutilizável.
- Incrementa `current_stock` com `+quantity`.
- Reutilizada em: compra (`+`), venda (`-`), cancelamento de venda (`+`).

---

## DTOs

### `CreatePurchaseOrderDto`
| Campo | Tipo |
|-------|------|
| `supplier` | `string` |
| `items` | `PurchaseOrderItemDto[]` |

### `PurchaseOrderItemDto`
| Campo | Tipo |
|-------|------|
| `productId` | `string` |
| `quantity` | `int` |
| `unitPrice` | `string` |

---

## Validação (`CreatePurchaseOrderRequest`)

| Campo | Regras |
|-------|--------|
| `supplier` | `required`, `string`, `min:3` |
| `products` | `required`, `array`, `min:1` |
| `products.*.id` | `required`, `uuid`, `exists:products,id` |
| `products.*.quantity` | `required`, `integer`, `min:1` |
| `products.*.unit_price` | `required`, `numeric`, `gt:0` |

---

## Resposta (HTTP 201)

```json
{
  "data": {
    "purchaseOrderId": "019ef767-34a9-72b2-ba45-b5d513dbaa46",
    "totalAmount": 1300.00,
    "createdAt": "24/06/2026 10:30:00",
    "updatedAt": "24/06/2026 10:30:00"
  }
}
```

`totalAmount` é calculado no `PurchaseOrderResource` a partir do relacionamento `items` carregado:  
`SUM(item.quantity * item.unit_price)`.

---

## Arquivos a Criar/Atualizar

| Ação | Arquivo |
|------|---------|
| Criar | `app/Dtos/PurchaseOrder/CreatePurchaseOrderDto.php` |
| Criar | `app/Dtos/PurchaseOrder/PurchaseOrderItemDto.php` |
| Criar | `app/Http/Requests/PurchaseOrder/CreatePurchaseOrderRequest.php` |
| Criar | `app/Actions/PurchaseOrder/CreatePurchaseOrderAction.php` |
| Criar | `app/Actions/Product/UpdateProductStockAction.php` |
| Criar | `app/Actions/Product/UpdateProductAverageCostAction.php` |
| Criar | `app/Http/Resources/PurchaseOrder/PurchaseOrderResource.php` |
| Criar | `app/Http/Controllers/PurchaseOrderController.php` |
| Atualizar | `app/Models/PurchaseOrder/PurchaseOrder.php` (add `items()` relationship) |
| Atualizar | `app/Models/PurchaseOrder/PurchaseOrderItem.php` (add `product()` relationship) |
| Atualizar | `routes/api.php` (add `compras` route) |

---

## Contexto para Fluxos Futuros

| Fluxo | Estoque | Custo Médio |
|-------|---------|-------------|
| Compra | `UpdateProductStockAction` (`+quantity`) | `UpdateProductAverageCostAction` (recalcula) |
| Venda | `UpdateProductStockAction` (`-quantity`) | Não muda |
| Cancelar venda | `UpdateProductStockAction` (`+quantity`) | Não muda |
