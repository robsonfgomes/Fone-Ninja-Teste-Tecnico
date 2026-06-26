import { describe, it, expect, vi, beforeEach } from 'vitest';
import { productsService } from '@/services/products.service';
import { api } from '@/services/api';

vi.mock('@/services/api', () => ({
  api: {
    get: vi.fn(),
    post: vi.fn(),
  },
}));

const mockProduct = {
  id: '1',
  name: 'iPhone',
  sellingPrice: 999,
  currentStock: 0,
  averageCost: null,
  createdAt: '25/06/2026 00:00:00',
  updatedAt: '25/06/2026 00:00:00',
};

describe('productsService.create', () => {
  beforeEach(() => vi.clearAllMocks());

  it('posts to /produtos with the payload and returns the created product', async () => {
    vi.mocked(api.post).mockResolvedValue({ data: { data: mockProduct } });

    const result = await productsService.create({
      name: 'iPhone',
      selling_price: 999,
      initial_stock: 0,
    });

    expect(api.post).toHaveBeenCalledWith('/produtos', {
      name: 'iPhone',
      selling_price: 999,
      initial_stock: 0,
    });
    expect(result).toEqual(mockProduct);
  });
});

describe('productsService.list', () => {
  beforeEach(() => vi.clearAllMocks());

  it('calls GET /produtos with page and perPage params', async () => {
    const mockResponse = {
      data: [],
      meta: {
        current_page: 1, last_page: 1, from: 1, to: 0,
        total: 0, per_page: 100, links: [],
      },
    };
    vi.mocked(api.get).mockResolvedValue({ data: mockResponse });

    await productsService.list({ page: 1, perPage: 100 });

    expect(api.get).toHaveBeenCalledWith('/produtos', {
      params: { page: 1, perPage: 100 },
    });
  });
});
