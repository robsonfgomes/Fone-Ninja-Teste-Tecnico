import { describe, it, expect, vi, beforeEach } from 'vitest';
import { setActivePinia, createPinia } from 'pinia';
import { useSalesStore } from '../sales.store';
import { salesService } from '@/services/sales.service';
import type { PaginatedResponse } from '@/types/pagination';
import type { Sale } from '@/types/sale';

vi.mock('@/services/sales.service');

const mockSale: Sale = {
  id: '1',
  customerName: 'João Silva',
  status: 'Active',
  totalAmount: 150,
  profit: 50,
  createdAt: '25/06/2026 10:00:00',
  updatedAt: '25/06/2026 10:00:00',
};

const mockResponse: PaginatedResponse<Sale> = {
  data: [mockSale],
  meta: {
    current_page: 1,
    last_page: 2,
    from: 1,
    to: 10,
    total: 20,
    per_page: 10,
    links: [],
  },
};

describe('useSalesStore', () => {
  beforeEach(() => {
    setActivePinia(createPinia());
    vi.mocked(salesService.list).mockResolvedValue(mockResponse);
  });

  it('populates sales and meta after fetchSales', async () => {
    const store = useSalesStore();
    await store.fetchSales();
    expect(store.sales).toEqual([mockSale]);
    expect(store.meta).toEqual(mockResponse.meta);
  });

  it('passes page 1 by default', async () => {
    const store = useSalesStore();
    await store.fetchSales();
    expect(salesService.list).toHaveBeenCalledWith(1);
  });

  it('passes the given page number to the service', async () => {
    const store = useSalesStore();
    await store.fetchSales(3);
    expect(salesService.list).toHaveBeenCalledWith(3);
  });
});
