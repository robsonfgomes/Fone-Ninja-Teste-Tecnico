import { api } from './api';
import type { CreatePurchasePayload, PurchaseOrderResult } from '@/types/purchase';

export const purchasesService = {
  async create(payload: CreatePurchasePayload): Promise<PurchaseOrderResult> {
    const response = await api.post<{ data: PurchaseOrderResult }>('/compras', payload);
    return response.data.data;
  },
};
