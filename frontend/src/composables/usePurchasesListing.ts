import { ref } from 'vue';
import { purchasesService } from '@/services/purchases.service';
import type { PurchaseOrder } from '@/types/purchase';
import type { PaginationMeta, PaginationOptions } from '@/types/pagination';

export function usePurchasesListing() {
  const purchases = ref<PurchaseOrder[]>([]);
  const meta = ref<PaginationMeta | null>(null);

  async function fetchPurchases(page = 1): Promise<void> {
    const options: PaginationOptions = { page };
    const response = await purchasesService.list(options);
    purchases.value = response.data;
    meta.value = response.meta;
  }

  return { purchases, meta, fetchPurchases };
}
