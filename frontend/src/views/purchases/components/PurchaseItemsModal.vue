<script setup lang="ts">
import { computed, ref } from 'vue';
import AppModal from '@/components/AppModal.vue';
import { formatCurrency } from '@/utils/format';
import type { PurchaseOrder } from '@/types/purchase';

const props = defineProps<{
  purchase: PurchaseOrder | null;
}>();

const modal = ref<InstanceType<typeof AppModal>>();

const totalQuantity = computed(() =>
  props.purchase?.items.reduce((sum, item) => sum + item.quantity, 0) ?? 0
);
const totalAmount = computed(() =>
  props.purchase?.items.reduce((sum, item) => sum + item.totalAmount, 0) ?? 0
);

function show() { modal.value!.show(); }
function hide() { modal.value!.hide(); }

defineExpose({ show, hide });
</script>

<template>
  <AppModal
    ref="modal"
    :title="purchase ? `Itens da compra — ${purchase.supplierName}` : 'Itens da compra'"
    size="lg"
  >
    <template #body>
      <div v-if="purchase && purchase.items.length > 0" class="table-responsive">
        <table class="table table-bordered table-sm mb-0">
          <thead class="table-light">
            <tr>
              <th class="text-center">#</th>
              <th>Produto</th>
              <th class="text-center">Quantidade</th>
              <th class="text-end">Preço Unitário</th>
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in purchase.items" :key="item.id">
              <td class="text-center">{{ index + 1 }}</td>
              <td>{{ item.product.name }}</td>
              <td class="text-center">{{ item.quantity }}</td>
              <td class="text-end">{{ formatCurrency(item.unitPrice) }}</td>
              <td class="text-end">{{ formatCurrency(item.totalAmount) }}</td>
            </tr>
          </tbody>
          <tfoot class="table-light fw-bold">
            <tr>
              <td colspan="2" class="text-end">Total</td>
              <td class="text-center">{{ totalQuantity }}</td>
              <td></td>
              <td class="text-end">{{ formatCurrency(totalAmount) }}</td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div v-else class="text-center text-muted py-3">
        Nenhum item encontrado.
      </div>
    </template>
  </AppModal>
</template>
