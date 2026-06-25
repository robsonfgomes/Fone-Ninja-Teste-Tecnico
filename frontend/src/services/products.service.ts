import { api } from './api';
import type { Product, CreateProductPayload } from '@/types/product';
import type { PaginatedResponse } from '@/types/pagination';

export const productsService = {
  async list(page = 1): Promise<PaginatedResponse<Product>> {
    const response = await api.get<PaginatedResponse<Product>>('/produtos', {
      params: { page },
    });

    return response.data;
  },

  async create(payload: CreateProductPayload): Promise<Product> {
    const response = await api.post<{ data: Product }>('/produtos', payload);
    return response.data.data;
  },
};
