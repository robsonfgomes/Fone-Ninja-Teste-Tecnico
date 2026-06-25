export interface Product {
  id: string;
  name: string;
  sellingPrice: number;
  currentStock: number;
  averageCost: number | null;
  createdAt: string;
  updatedAt: string;
}

export interface CreateProductPayload {
  name: string;
  selling_price: number;
  initial_stock?: number;
}

export interface ProductFormData {
  name: string;
  sellingPrice: string;
}
