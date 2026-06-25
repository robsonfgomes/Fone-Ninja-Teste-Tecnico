import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import { setActivePinia, createPinia } from 'pinia';
import CreateProductModal from '@/views/products/components/CreateProductModal.vue';
import { useProductsStore } from '@/stores/products.store';
import { useToastStore } from '@/stores/toast.store';
import { productsService } from '@/services/products.service';

const mockModalInstance = {
  show: vi.fn(),
  hide: vi.fn(),
  dispose: vi.fn(),
};

vi.mock('bootstrap', () => ({
  Modal: vi.fn(function () { return mockModalInstance; }),
}));

vi.mock('@/services/products.service');

const mockProduct = {
  id: '1',
  name: 'iPhone',
  sellingPrice: 999,
  currentStock: 0,
  averageCost: null,
  createdAt: '',
  updatedAt: '',
};

const mockListResponse = {
  data: [],
  meta: {
    current_page: 1,
    last_page: 1,
    from: 1,
    to: 0,
    total: 0,
    per_page: 10,
    links: [],
  },
};

describe('CreateProductModal', () => {
  beforeEach(() => {
    setActivePinia(createPinia());
    vi.clearAllMocks();
    vi.mocked(productsService.list).mockResolvedValue(mockListResponse);
  });

  it('delegates show() to the AppModal', async () => {
    const wrapper = mount(CreateProductModal);
    await (wrapper.vm as unknown as { show(): void }).show();
    expect(mockModalInstance.show).toHaveBeenCalledOnce();
  });

  it('does not call createProduct when the name field is empty', async () => {
    const wrapper = mount(CreateProductModal);

    await wrapper.find('button.btn-success').trigger('click');

    expect(productsService.create).not.toHaveBeenCalled();
  });

  it('does not call createProduct when name has fewer than 3 characters', async () => {
    const wrapper = mount(CreateProductModal);

    await wrapper.find('input[name="name"]').setValue('AB');
    await wrapper.find('input[name="sellingPrice"]').setValue('99');
    await wrapper.find('button.btn-success').trigger('click');

    expect(productsService.create).not.toHaveBeenCalled();
  });

  it('calls store.createProduct with correct payload when form is valid', async () => {
    vi.mocked(productsService.create).mockResolvedValue(mockProduct);
    const wrapper = mount(CreateProductModal);

    await wrapper.find('input[name="name"]').setValue('iPhone');
    await wrapper.find('input[name="sellingPrice"]').setValue('999');
    await wrapper.find('button.btn-success').trigger('click');
    await flushPromises();

    expect(productsService.create).toHaveBeenCalledWith({
      name: 'iPhone',
      selling_price: 999,
      initial_stock: 0,
    });
  });

  it('hides the modal after a successful submit', async () => {
    vi.mocked(productsService.create).mockResolvedValue(mockProduct);
    const wrapper = mount(CreateProductModal);

    await wrapper.find('input[name="name"]').setValue('iPhone');
    await wrapper.find('input[name="sellingPrice"]').setValue('999');
    await wrapper.find('button.btn-success').trigger('click');
    await flushPromises();

    expect(mockModalInstance.hide).toHaveBeenCalledOnce();
  });

  it('shows a success toast after a successful submit', async () => {
    vi.mocked(productsService.create).mockResolvedValue(mockProduct);
    const wrapper = mount(CreateProductModal);
    const toast = useToastStore();
    vi.spyOn(toast, 'add');

    await wrapper.find('input[name="name"]').setValue('iPhone');
    await wrapper.find('input[name="sellingPrice"]').setValue('999');
    await wrapper.find('button.btn-success').trigger('click');
    await flushPromises();

    expect(toast.add).toHaveBeenCalledWith('Produto cadastrado com sucesso!', 'success');
  });

  it('resets the form when the modal emits hidden', async () => {
    const wrapper = mount(CreateProductModal);

    await wrapper.find('input[name="name"]').setValue('iPhone');
    await wrapper.find('input[name="sellingPrice"]').setValue('999');

    // Simulate Bootstrap hiding the modal natively
    const modalEl = wrapper.find('.modal').element;
    modalEl.dispatchEvent(new Event('hidden.bs.modal'));
    await wrapper.vm.$nextTick();

    const nameInput = wrapper.find('input[name="name"]').element as HTMLInputElement;
    const priceInput = wrapper.find('input[name="sellingPrice"]').element as HTMLInputElement;
    expect(nameInput.value).toBe('');
    expect(priceInput.value).toBe('');
  });

  it('hides the modal when the Cancelar button is clicked', async () => {
    const wrapper = mount(CreateProductModal);
    await wrapper.find('button.btn-secondary').trigger('click');
    expect(mockModalInstance.hide).toHaveBeenCalledOnce();
  });
});
