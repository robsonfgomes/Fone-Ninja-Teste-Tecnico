import { api } from './api';
import type { CreateSalePayload, SaleResult, CancelledSaleResult } from '@/types/sale';

export const salesService = {
  create: (payload: CreateSalePayload): Promise<SaleResult> =>
    api.post<{ data: SaleResult }>('/vendas', payload).then((r) => r.data.data),

  cancel: (id: string): Promise<CancelledSaleResult> =>
    api.patch<{ data: CancelledSaleResult }>(`/vendas/${id}`, { status: 'cancelled' }).then((r) => r.data.data),
};
