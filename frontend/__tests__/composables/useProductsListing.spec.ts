import { describe, it, expect, vi, beforeEach } from 'vitest';
import { useProductsListing } from '@/composables/useProductsListing';
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

describe('useProductsListing', () => {
  beforeEach(() => {
    vi.mocked(productsService.list).mockResolvedValue(mockResponse);
  });

  it('populates products and meta after fetchProducts', async () => {
    const { products, meta, fetchProducts } = useProductsListing();
    await fetchProducts();
    expect(products.value).toEqual([mockProduct]);
    expect(meta.value).toEqual(mockResponse.meta);
  });

  it('passes page 1 by default', async () => {
    const { fetchProducts } = useProductsListing();
    await fetchProducts();
    expect(productsService.list).toHaveBeenCalledWith({ page: 1 });
  });

  it('passes the given page number to the service', async () => {
    const { fetchProducts } = useProductsListing();
    await fetchProducts(3);
    expect(productsService.list).toHaveBeenCalledWith({ page: 3 });
  });
});
