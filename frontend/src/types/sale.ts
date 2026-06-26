import type { OrderItemPayload } from '@/types/order';
import type { Product } from '@/types/product';

export type SaleStatus = 'Active' | 'Cancelled';

export interface Sale {
  id: string;
  customerName: string;
  status: SaleStatus;
  totalAmount: number;
  profit: number;
  createdAt: string;
  updatedAt: string;
  items: SaleItem[];
}

export interface SaleItem {
  id: string;
  quantity: number;
  unitPrice: number;
  totalAmount: number;
  createdAt: string;
  updatedAt: string;
  product: Product;
}

export interface CreateSalePayload {
  customer: string;
  products: OrderItemPayload[];
}

export interface SaleFormData {
  customerName: string;
}
