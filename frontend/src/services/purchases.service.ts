import { api } from './api';
import type { CreatePurchasePayload, PurchaseOrderResult } from '@/types/purchase';

export const purchasesService = {
  create: (payload: CreatePurchasePayload): Promise<PurchaseOrderResult> =>
    api.post<{ data: PurchaseOrderResult }>('/compras', payload).then((r) => r.data.data),
};
