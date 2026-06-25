import { api } from './api';
import type { CreateSalePayload, SaleResult, CancelledSaleResult } from '@/types/sale';

export const salesService = {
  async create(payload: CreateSalePayload): Promise<SaleResult> {
    const response = await api.post<{ data: SaleResult }>('/vendas', payload);
    return response.data.data;
  },

  async cancel(id: string): Promise<CancelledSaleResult> {
    const response = await api.patch<{ data: CancelledSaleResult }>(`/vendas/${id}`, { status: 'cancelled' });
    return response.data.data;
  },
};
