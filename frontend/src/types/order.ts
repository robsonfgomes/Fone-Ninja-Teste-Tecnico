export interface OrderItemPayload {
  id: string;
  quantity: number;
  unitPrice: number;
}

export interface ProductItemEditor {
  productId: string;
  quantity: string;
  unitPrice: string;
}
