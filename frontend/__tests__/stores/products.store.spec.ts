import { describe, it, expect, vi, beforeEach } from 'vitest';
import { setActivePinia, createPinia } from 'pinia';
import { useProductsStore } from '@/stores/products.store';
import { productsService } from '@/services/products.service';
import type { PaginatedResponse } from '@/types/pagination';
import type { Product } from '@/types/product';

vi.mock('@/services/products.service');

const mockProduct: Product = {
  id: '1',
  name: 'Produto Teste',
  sellingPrice: 100,
  currentStock: 10,
  averageCost: 50,
  createdAt: '01/01/2026 00:00:00',
  updatedAt: '01/01/2026 00:00:00',
};

const mockResponse: PaginatedResponse<Product> = {
  data: [mockProduct],
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

describe('useProductsStore', () => {
  beforeEach(() => {
    setActivePinia(createPinia());
    vi.mocked(productsService.list).mockResolvedValue(mockResponse);
  });

  it('populates products and meta after fetchProducts', async () => {
    const store = useProductsStore();
    await store.fetchProducts();
    expect(store.products).toEqual([mockProduct]);
    expect(store.meta).toEqual(mockResponse.meta);
  });

  it('passes page 1 by default', async () => {
    const store = useProductsStore();
    await store.fetchProducts();
    expect(productsService.list).toHaveBeenCalledWith(1);
  });

  it('passes the given page number to the service', async () => {
    const store = useProductsStore();
    await store.fetchProducts(3);
    expect(productsService.list).toHaveBeenCalledWith(3);
  });
});
