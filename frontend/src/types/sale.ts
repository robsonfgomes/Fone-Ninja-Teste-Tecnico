import type { OrderItem } from '@/types/order';

export interface Sale {
  id: string;
  customerName: string;
  status: 'Active' | 'Cancelled';
  totalAmount: number;
  profit: number;
  createdAt: string;
  updatedAt: string;
}

export interface CreateSalePayload {
  customer: string;
  products: OrderItem[];
}

export interface SaleResult {
  saleId: string;
  totalAmount: number;
  profit: number;
  createdAt: string;
  updatedAt: string;
}

export type SaleStatus = 'active' | 'cancelled';

export interface CancelledSaleResult {
  saleId: string;
  status: SaleStatus;
  createdAt: string;
  updatedAt: string;
}
