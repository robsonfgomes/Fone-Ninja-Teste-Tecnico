export interface OrderItem {
  id: string;
  quantity: number;
  unitPrice: number;
}

export interface ProductOrderItem {
  productId: string;
  quantity: number;
  unitPrice: string;
}
