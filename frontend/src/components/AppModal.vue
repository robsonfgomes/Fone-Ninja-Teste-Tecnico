<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Modal } from 'bootstrap';

defineProps<{
  title?: string;
  size?: 'sm' | 'lg' | 'xl' | '';
}>();

const emit = defineEmits<{ hidden: [] }>();

const modalEl = ref<HTMLElement>();
let bootstrapModal: Modal;

onMounted(() => {
  bootstrapModal = new Modal(modalEl.value!);
  modalEl.value!.addEventListener('hidden.bs.modal', () => emit('hidden'));
});

onBeforeUnmount(() => {
  bootstrapModal?.dispose();
});

function show() {
  bootstrapModal.show();
}

function hide() {
  bootstrapModal.hide();
}

defineExpose({ show, hide });
</script>

<template>
  <div ref="modalEl" class="modal fade" tabindex="-1">
    <div class="modal-dialog" :class="size ? `modal-${size}` : ''">
      <div class="modal-content">
        <div class="modal-header">
          <slot name="header">
            <h5 class="modal-title">{{ title }}</h5>
          </slot>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <slot name="body" />
        </div>
        <div class="modal-footer">
          <slot name="footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          </slot>
        </div>
      </div>
    </div>
  </div>
</template>
