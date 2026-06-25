import { api } from './api';
import type { Product } from '@/types/product';
import type { PaginatedResponse } from '@/types/pagination';

export const productsService = {
  async list(page = 1): Promise<PaginatedResponse<Product>> {
    const response = await api.get<PaginatedResponse<Product>>('/produtos', {
      params: { page },
    });

    return response.data;
  },
};
