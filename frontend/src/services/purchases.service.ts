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
    const body = {
      supplier: payload.supplier,
      products: payload.products.map(p => ({
        id: p.id,
        quantity: p.quantity,
        unit_price: p.unitPrice,
      })),
    };
    const response = await api.post<{ data: PurchaseOrder }>('/compras', body);
    return response.data.data;
  },
};
