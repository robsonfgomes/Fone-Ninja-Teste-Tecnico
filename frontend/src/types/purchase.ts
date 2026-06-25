export interface PurchaseOrder {
  id: string;
  supplierName: string;
  totalAmount: number;
  createdAt: string;
  updatedAt: string;
}

export interface PurchaseItem {
  id: string;
  quantity: number;
  unit_price: number;
}

export interface CreatePurchasePayload {
  supplier: string;
  products: PurchaseItem[];
}

export interface PurchaseOrderResult {
  purchaseOrderId: string;
  totalAmount: number;
  createdAt: string;
  updatedAt: string;
}

export interface ProductOrderItem {
  productId: string;
  quantity: number;
  unitPrice: string;
}

export interface PurchaseFormData {
  supplierName: string;
}
