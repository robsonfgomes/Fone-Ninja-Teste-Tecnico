export interface SaleItem {
  id: string;
  quantity: number;
  unit_price: number;
}

export interface CreateSalePayload {
  customer: string;
  products: SaleItem[];
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
