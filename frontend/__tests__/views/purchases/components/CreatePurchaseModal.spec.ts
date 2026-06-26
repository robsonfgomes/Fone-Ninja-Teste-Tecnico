import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import { setActivePinia, createPinia } from 'pinia';
import CreatePurchaseModal from '@/views/purchases/components/CreatePurchaseModal.vue';
import { useToastStore } from '@/stores/toast.store';
import { productsService } from '@/services/products.service';
import { purchasesService } from '@/services/purchases.service';
import type { PurchaseOrder } from '@/types/purchase';

const mockModalInstance = {
  show: vi.fn(),
  hide: vi.fn(),
  dispose: vi.fn(),
};

vi.mock('bootstrap', () => ({
  Modal: vi.fn(function () { return mockModalInstance; }),
}));

vi.mock('@/services/products.service');
vi.mock('@/services/purchases.service');

const mockProducts = [
  { id: '1', name: 'iPhone', sellingPrice: 999, currentStock: 5, averageCost: null, createdAt: '', updatedAt: '' },
];

const mockListResponse = {
  data: mockProducts,
  meta: { current_page: 1, last_page: 1, from: 1, to: 1, total: 1, per_page: 100, links: [] },
};

const mockPurchaseResult: PurchaseOrder = {
  id: 'abc-123',
  supplierName: 'Fornecedor ABC',
  totalAmount: 999,
  createdAt: '',
  updatedAt: '',
  items: [],
};

describe('CreatePurchaseModal', () => {
  beforeEach(() => {
    setActivePinia(createPinia());
    vi.clearAllMocks();
    vi.mocked(productsService.list).mockResolvedValue(mockListResponse);
  });

  it('fetches products with per_page=100 and delegates show() to AppModal', async () => {
    const wrapper = mount(CreatePurchaseModal);
    await (wrapper.vm as unknown as { show(): void }).show();
    await flushPromises();
    expect(productsService.list).toHaveBeenCalledWith(1, 100);
    expect(mockModalInstance.show).toHaveBeenCalledOnce();
  });

  it('does not call purchasesService.create when supplier name is empty', async () => {
    const wrapper = mount(CreatePurchaseModal);
    await (wrapper.vm as unknown as { show(): void }).show();
    await flushPromises();
    await wrapper.find('button.btn-success').trigger('click');
    expect(purchasesService.create).not.toHaveBeenCalled();
  });

  it('does not call purchasesService.create when supplier name has fewer than 3 characters', async () => {
    const wrapper = mount(CreatePurchaseModal);
    await (wrapper.vm as unknown as { show(): void }).show();
    await flushPromises();
    await wrapper.find('input[name="supplierName"]').setValue('AB');
    await wrapper.find('button.btn-success').trigger('click');
    expect(purchasesService.create).not.toHaveBeenCalled();
  });

  it('calls purchasesService.create with correct payload when form is valid', async () => {
    vi.mocked(purchasesService.create).mockResolvedValue(mockPurchaseResult);
    const wrapper = mount(CreatePurchaseModal);
    await (wrapper.vm as unknown as { show(): void }).show();
    await flushPromises();

    await wrapper.find('input[name="supplierName"]').setValue('Fornecedor ABC');
    await wrapper.find('select').setValue('1');
    await wrapper.find('input[min="0.01"]').setValue('999');
    await wrapper.find('button.btn-success').trigger('click');
    await flushPromises();

    expect(purchasesService.create).toHaveBeenCalledWith({
      supplier: 'Fornecedor ABC',
      products: [{ id: '1', quantity: 1, unitPrice: 999 }],
    });
  });

  it('hides the modal after successful submit', async () => {
    vi.mocked(purchasesService.create).mockResolvedValue(mockPurchaseResult);
    const wrapper = mount(CreatePurchaseModal);
    await (wrapper.vm as unknown as { show(): void }).show();
    await flushPromises();

    await wrapper.find('input[name="supplierName"]').setValue('Fornecedor ABC');
    await wrapper.find('select').setValue('1');
    await wrapper.find('input[min="0.01"]').setValue('999');
    await wrapper.find('button.btn-success').trigger('click');
    await flushPromises();

    expect(mockModalInstance.hide).toHaveBeenCalledOnce();
  });

  it('emits "created" after successful submit', async () => {
    vi.mocked(purchasesService.create).mockResolvedValue(mockPurchaseResult);
    const wrapper = mount(CreatePurchaseModal);
    await (wrapper.vm as unknown as { show(): void }).show();
    await flushPromises();

    await wrapper.find('input[name="supplierName"]').setValue('Fornecedor ABC');
    await wrapper.find('select').setValue('1');
    await wrapper.find('input[min="0.01"]').setValue('999');
    await wrapper.find('button.btn-success').trigger('click');
    await flushPromises();

    expect(wrapper.emitted('created')).toHaveLength(1);
  });

  it('shows a success toast after successful submit', async () => {
    vi.mocked(purchasesService.create).mockResolvedValue(mockPurchaseResult);
    const wrapper = mount(CreatePurchaseModal);
    const toast = useToastStore();
    vi.spyOn(toast, 'add');
    await (wrapper.vm as unknown as { show(): void }).show();
    await flushPromises();

    await wrapper.find('input[name="supplierName"]').setValue('Fornecedor ABC');
    await wrapper.find('select').setValue('1');
    await wrapper.find('input[min="0.01"]').setValue('999');
    await wrapper.find('button.btn-success').trigger('click');
    await flushPromises();

    expect(toast.add).toHaveBeenCalledWith('Compra cadastrada com sucesso!', 'success');
  });

  it('shows an error toast when purchasesService.create fails', async () => {
    vi.mocked(purchasesService.create).mockRejectedValue(new Error('Network error'));
    const wrapper = mount(CreatePurchaseModal);
    const toast = useToastStore();
    vi.spyOn(toast, 'add');
    await (wrapper.vm as unknown as { show(): void }).show();
    await flushPromises();

    await wrapper.find('input[name="supplierName"]').setValue('Fornecedor ABC');
    await wrapper.find('select').setValue('1');
    await wrapper.find('input[min="0.01"]').setValue('999');
    await wrapper.find('button.btn-success').trigger('click');
    await flushPromises();

    expect(toast.add).toHaveBeenCalledWith('Erro ao cadastrar compra.', 'danger');
  });

  it('resets the form when the modal emits hidden', async () => {
    const wrapper = mount(CreatePurchaseModal);
    await (wrapper.vm as unknown as { show(): void }).show();
    await flushPromises();

    await wrapper.find('input[name="supplierName"]').setValue('Fornecedor ABC');
    wrapper.find('.modal').element.dispatchEvent(new Event('hidden.bs.modal'));
    await wrapper.vm.$nextTick();

    const input = wrapper.find('input[name="supplierName"]').element as HTMLInputElement;
    expect(input.value).toBe('');
  });

  it('hides the modal when the Cancelar button is clicked', async () => {
    const wrapper = mount(CreatePurchaseModal);
    await wrapper.find('button.btn-secondary').trigger('click');
    expect(mockModalInstance.hide).toHaveBeenCalledOnce();
  });

  it('shows an error toast when products fail to load on show()', async () => {
    vi.mocked(productsService.list).mockRejectedValueOnce(new Error('Network error'));
    const wrapper = mount(CreatePurchaseModal);
    const toast = useToastStore();
    vi.spyOn(toast, 'add');

    await (wrapper.vm as unknown as { show(): void }).show();
    await flushPromises();

    expect(toast.add).toHaveBeenCalledWith('Erro ao carregar produtos.', 'danger');
    expect(mockModalInstance.show).not.toHaveBeenCalled();
  });
});
