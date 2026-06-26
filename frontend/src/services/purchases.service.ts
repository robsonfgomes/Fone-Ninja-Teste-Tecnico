import { api } from './api';
import type { PurchaseOrder, CreatePurchasePayload } from '@/types/purchase';
import type { PaginatedResponse, PaginationOptions } from '@/types/pagination';

export const purchasesService = {
  async list(options?: PaginationOptions): Promise<PaginatedResponse<PurchaseOrder>> {
    const response = await api.get<PaginatedResponse<PurchaseOrder>>('/compras', {
      params: options,
    });

    return response.data;
  },

  async create(payload: CreatePurchasePayload): Promise<PurchaseOrder> {
    const response = await api.post<{ data: PurchaseOrder }>('/compras', payload);
    return response.data.data;
  },
};
