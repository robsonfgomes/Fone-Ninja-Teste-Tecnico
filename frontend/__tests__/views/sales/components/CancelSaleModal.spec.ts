import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import { setActivePinia, createPinia } from 'pinia';
import CancelSaleModal from '@/views/sales/components/CancelSaleModal.vue';
import { salesService } from '@/services/sales.service';
import { useToastStore } from '@/stores/toast.store';
import type { Sale } from '@/types/sale';

const mockModalInstance = {
  show: vi.fn(),
  hide: vi.fn(),
  dispose: vi.fn(),
};

vi.mock('bootstrap', () => ({
  Modal: vi.fn(function () { return mockModalInstance; }),
}));

vi.mock('@/services/sales.service');

const mockSale: Sale = {
  id: '99',
  customerName: 'João Silva',
  status: 'Active',
  totalAmount: 500,
  profit: 100,
  createdAt: '26/06/2026 10:00:00',
  updatedAt: '26/06/2026 10:00:00',
  items: [],
};

describe('CancelSaleModal', () => {
  beforeEach(() => {
    setActivePinia(createPinia());
    vi.clearAllMocks();
  });

  it('delegates show() to AppModal', async () => {
    const wrapper = mount(CancelSaleModal, { props: { sale: mockSale } });
    await (wrapper.vm as unknown as { show(): void }).show();
    expect(mockModalInstance.show).toHaveBeenCalledOnce();
  });

  it('delegates hide() to AppModal', async () => {
    const wrapper = mount(CancelSaleModal, { props: { sale: mockSale } });
    await (wrapper.vm as unknown as { hide(): void }).hide();
    expect(mockModalInstance.hide).toHaveBeenCalledOnce();
  });

  it('displays the customer name in the confirmation message', () => {
    const wrapper = mount(CancelSaleModal, { props: { sale: mockSale } });
    expect(wrapper.text()).toContain('João Silva');
  });

  it('hides the modal when the Voltar button is clicked', async () => {
    const wrapper = mount(CancelSaleModal, { props: { sale: mockSale } });
    await wrapper.find('button.btn-secondary').trigger('click');
    expect(mockModalInstance.hide).toHaveBeenCalledOnce();
  });

  it('calls salesService.cancel with the sale id when Confirmar is clicked', async () => {
    vi.mocked(salesService.cancel).mockResolvedValue(undefined);
    const wrapper = mount(CancelSaleModal, { props: { sale: mockSale } });

    await wrapper.find('button.btn-success').trigger('click');
    await flushPromises();

    expect(salesService.cancel).toHaveBeenCalledWith('99', 'Cancelled');
  });

  it('emits cancelled after a successful cancel', async () => {
    vi.mocked(salesService.cancel).mockResolvedValue(undefined);
    const wrapper = mount(CancelSaleModal, { props: { sale: mockSale } });

    await wrapper.find('button.btn-success').trigger('click');
    await flushPromises();

    expect(wrapper.emitted('cancelled')).toBeTruthy();
  });

  it('hides the modal after a successful cancel', async () => {
    vi.mocked(salesService.cancel).mockResolvedValue(undefined);
    const wrapper = mount(CancelSaleModal, { props: { sale: mockSale } });

    await wrapper.find('button.btn-success').trigger('click');
    await flushPromises();

    expect(mockModalInstance.hide).toHaveBeenCalledOnce();
  });

  it('shows a success toast after a successful cancel', async () => {
    vi.mocked(salesService.cancel).mockResolvedValue(undefined);
    const wrapper = mount(CancelSaleModal, { props: { sale: mockSale } });
    const toast = useToastStore();
    vi.spyOn(toast, 'add');

    await wrapper.find('button.btn-success').trigger('click');
    await flushPromises();

    expect(toast.add).toHaveBeenCalledWith('Venda cancelada com sucesso!', 'success');
  });

  it('renders no customer name when sale is null', () => {
    const wrapper = mount(CancelSaleModal, { props: { sale: null } });
    expect(wrapper.find('p').exists()).toBe(false);
  });
});
