import { describe, it, expect, vi, beforeEach } from 'vitest';
import { useSalesListing } from '@/composables/useSalesListing';
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
  items: [],
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

describe('useSalesListing', () => {
  beforeEach(() => {
    vi.mocked(salesService.list).mockResolvedValue(mockResponse);
  });

  it('populates sales and meta after fetchSales', async () => {
    const { sales, meta, fetchSales } = useSalesListing();
    await fetchSales();
    expect(sales.value).toEqual([mockSale]);
    expect(meta.value).toEqual(mockResponse.meta);
  });

  it('passes page 1 by default', async () => {
    const { fetchSales } = useSalesListing();
    await fetchSales();
    expect(salesService.list).toHaveBeenCalledWith({ page: 1 });
  });

  it('passes the given page number to the service', async () => {
    const { fetchSales } = useSalesListing();
    await fetchSales(3);
    expect(salesService.list).toHaveBeenCalledWith({ page: 3 });
  });
});
