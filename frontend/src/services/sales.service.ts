import { api } from './api';
import type { Sale, CreateSalePayload, SaleResult, CancelledSaleResult } from '@/types/sale';

import type { PaginatedResponse } from '@/types/pagination';

export const salesService = {
  async list(page = 1): Promise<PaginatedResponse<Sale>> {
    const response = await api.get<PaginatedResponse<Sale>>('/vendas', {
      params: { page },
    });
    return response.data;
  },

  async create(payload: CreateSalePayload): Promise<SaleResult> {
    const body = {
      customer: payload.customer,
      products: payload.products.map(p => ({
        id: p.id,
        quantity: p.quantity,
        unit_price: p.unitPrice,
      })),
    };
    const response = await api.post<{ data: SaleResult }>('/vendas', body);
    return response.data.data;
  },

  async cancel(id: string): Promise<CancelledSaleResult> {
    const response = await api.patch<{ data: CancelledSaleResult }>(`/vendas/${id}`, { status: 'cancelled' });
    return response.data.data;
  },
};
