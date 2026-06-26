import type { OrderItem } from '@/types/order';

export type SaleStatus = 'Active' | 'Cancelled';

export interface Sale {
  id: string;
  customerName: string;
  status: SaleStatus;
  totalAmount: number;
  profit: number;
  createdAt: string;
  updatedAt: string;
}

export interface CreateSalePayload {
  customer: string;
  products: OrderItem[];
}
