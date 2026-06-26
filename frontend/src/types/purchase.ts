import type { OrderItemPayload } from '@/types/order';
import type { Product } from '@/types/product';

export interface PurchaseOrder {
  id: string;
  supplierName: string;
  totalAmount: number;
  createdAt: string;
  updatedAt: string;
}

export interface PurchaseOrderItem {
  id: string;
  quantity: number;
  unitPrice: number;
  totalAmount: number;
  createdAt: string;
  updatedAt: string;
  product: Product;
}

export interface CreatePurchasePayload {
  supplier: string;
  products: OrderItemPayload[];
}

export interface PurchaseFormData {
  supplierName: string;
}
