import { describe, it, expect, vi, beforeEach } from 'vitest';
import { usePurchasesListing } from '@/composables/usePurchasesListing';
import { purchasesService } from '@/services/purchases.service';
import type { PaginatedResponse } from '@/types/pagination';
import type { PurchaseOrder } from '@/types/purchase';

vi.mock('@/services/purchases.service');

const mockPurchase: PurchaseOrder = {
  id: '1',
  supplierName: 'Fornecedor ABC',
  totalAmount: 500,
  createdAt: '25/06/2026 09:00:00',
  updatedAt: '25/06/2026 09:00:00',
  items: [],
};

const mockResponse: PaginatedResponse<PurchaseOrder> = {
  data: [mockPurchase],
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

describe('usePurchasesListing', () => {
  beforeEach(() => {
    vi.mocked(purchasesService.list).mockResolvedValue(mockResponse);
  });

  it('populates purchases and meta after fetchPurchases', async () => {
    const { purchases, meta, fetchPurchases } = usePurchasesListing();
    await fetchPurchases();
    expect(purchases.value).toEqual([mockPurchase]);
    expect(meta.value).toEqual(mockResponse.meta);
  });

  it('passes page 1 by default', async () => {
    const { fetchPurchases } = usePurchasesListing();
    await fetchPurchases();
    expect(purchasesService.list).toHaveBeenCalledWith(1);
  });

  it('passes the given page number to the service', async () => {
    const { fetchPurchases } = usePurchasesListing();
    await fetchPurchases(3);
    expect(purchasesService.list).toHaveBeenCalledWith(3);
  });
});
