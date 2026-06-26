import { ref } from 'vue';
import { salesService } from '@/services/sales.service';
import type { Sale } from '@/types/sale';
import type { PaginationMeta, PaginationOptions } from '@/types/pagination';

export function useSalesListing() {
  const sales = ref<Sale[]>([]);
  const meta = ref<PaginationMeta | null>(null);

  async function fetchSales(page: number = 1): Promise<void> {
    const options: PaginationOptions = { page };
    const response = await salesService.list(options);
    sales.value = response.data;
    meta.value = response.meta;
  }

  return { sales, meta, fetchSales };
}
