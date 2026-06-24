import { api } from './api';
import type { Product, CreateProductPayload } from '@/types/product';

export const productsService = {
  list: (): Promise<Product[]> =>
    api.get<{ data: Product[] }>('/produtos').then((r) => r.data.data),

  create: (payload: CreateProductPayload): Promise<Product> =>
    api.post<{ data: Product }>('/produtos', payload).then((r) => r.data.data),
};
