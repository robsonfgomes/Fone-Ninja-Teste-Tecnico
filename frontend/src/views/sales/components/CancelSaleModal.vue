<script setup lang="ts">
import { ref } from 'vue';
import AppModal from '@/components/AppModal.vue';
import AppButton from '@/components/AppButton.vue';
import { salesService } from '@/services/sales.service';
import { useToastStore } from '@/stores/toast.store';
import type { Sale } from '@/types/sale';

const props = defineProps<{
  sale: Sale | null;
}>();

const emit = defineEmits<{ cancelled: [] }>();

const toast = useToastStore();
const modal = ref<InstanceType<typeof AppModal>>();
const isCancelling = ref(false);

async function handleConfirm() {
  if (!props.sale) return;

  isCancelling.value = true;
  try {
    await salesService.cancel(props.sale.id, 'Cancelled');
    toast.add('Venda cancelada com sucesso!', 'success');
    emit('cancelled');
    modal.value!.hide();
  } catch {
    // interceptor handles the toast
  } finally {
    isCancelling.value = false;
  }
}

function show() {
  modal.value!.show();
}

function hide() {
  modal.value!.hide();
}

defineExpose({ show, hide });
</script>

<template>
  <AppModal ref="modal" title="Cancelar Venda">
    <template #body>
      <p v-if="sale">
        Deseja cancelar a venda de <strong>{{ sale.customerName }}</strong>?
        Essa ação não pode ser desfeita.
      </p>
    </template>

    <template #footer>
      <AppButton variant="secondary" :disabled="isCancelling" @click="modal!.hide()">
        Voltar
      </AppButton>
      <AppButton variant="success" :loading="isCancelling" @click="handleConfirm">
        Confirmar
      </AppButton>
    </template>
  </AppModal>
</template>
