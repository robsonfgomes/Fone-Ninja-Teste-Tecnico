import { api } from './api';
import type { Product, CreateProductPayload } from '@/types/product';
import type { PaginatedResponse, CollectionResponse, PaginationOptions } from '@/types/pagination';

export const productsService = {
  async list(options?: PaginationOptions): Promise<PaginatedResponse<Product>> {
    const response = await api.get<PaginatedResponse<Product>>('/produtos', {
      params: options,
    });

    return response.data;
  },

  async listAll(): Promise<CollectionResponse<Product>> {
    const response = await api.get<CollectionResponse<Product>>('/produtos', {
      params: { isToPaginate: false },
    });

    return response.data;
  },

  async create(payload: CreateProductPayload): Promise<Product> {
    const response = await api.post<{ data: Product }>('/produtos', payload);
    return response.data.data;
  },
};
