import { api } from './api';
import type { Sale, CreateSalePayload } from '@/types/sale';
import type { PaginatedResponse, PaginationOptions } from '@/types/pagination';

export const salesService = {
  async list(options?: PaginationOptions): Promise<PaginatedResponse<Sale>> {
    const response = await api.get<PaginatedResponse<Sale>>('/vendas', {
      params: options,
    });

    return response.data;
  },

  async create(payload: CreateSalePayload): Promise<Sale> {
    const response = await api.post<{ data: Sale }>('/vendas', payload);
    return response.data.data;
  },

  async cancel(id: string): Promise<void> {
    await api.patch(`/vendas/${id}`, { status: 'Cancelled' });
  },
};
