import { ref } from 'vue';
import { salesService } from '@/services/sales.service';
import type { Sale } from '@/types/sale';
import type { PaginationMeta } from '@/types/pagination';

export function useSalesListing() {
  const sales = ref<Sale[]>([]);
  const meta = ref<PaginationMeta | null>(null);

  async function fetchSales(page = 1): Promise<void> {
    const response = await salesService.list(page);
    sales.value = response.data;
    meta.value = response.meta;
  }

  return { sales, meta, fetchSales };
}
