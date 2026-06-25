import type { OrderItem } from '@/types/order';

export interface PurchaseOrder {
  id: string;
  supplierName: string;
  totalAmount: number;
  createdAt: string;
  updatedAt: string;
}

export interface CreatePurchasePayload {
  supplier: string;
  products: OrderItem[];
}

export interface PurchaseFormData {
  supplierName: string;
}
