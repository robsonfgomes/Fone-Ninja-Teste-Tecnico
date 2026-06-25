import { api } from './api';
import type { PurchaseOrder, CreatePurchasePayload } from '@/types/purchase';
import type { PaginatedResponse } from '@/types/pagination';

export const purchasesService = {
  async list(page = 1): Promise<PaginatedResponse<PurchaseOrder>> {
    const response = await api.get<PaginatedResponse<PurchaseOrder>>('/compras', {
      params: { page },
    });
    return response.data;
  },

  async create(payload: CreatePurchasePayload): Promise<PurchaseOrder> {
    const response = await api.post<{ data: PurchaseOrder }>('/compras', payload);
    return response.data.data;
  },
};
