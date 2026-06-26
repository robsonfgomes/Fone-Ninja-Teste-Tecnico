import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount } from '@vue/test-utils';
import PurchaseItemsModal from '@/views/purchases/components/PurchaseItemsModal.vue';
import type { PurchaseOrder } from '@/types/purchase';

const mockModalInstance = {
  show: vi.fn(),
  hide: vi.fn(),
  dispose: vi.fn(),
};

vi.mock('bootstrap', () => ({
  Modal: vi.fn(function () { return mockModalInstance; }),
}));

const mockPurchase: PurchaseOrder = {
  id: '1',
  supplierName: 'Fornecedor ABC',
  totalAmount: 7000,
  createdAt: '26/06/2026 10:00:00',
  updatedAt: '26/06/2026 10:00:00',
  items: [
    {
      id: 'item-1',
      quantity: 2,
      unitPrice: 2000,
      totalAmount: 4000,
      createdAt: '26/06/2026 10:00:00',
      updatedAt: '26/06/2026 10:00:00',
      product: {
        id: 'prod-1',
        name: 'iPhone 14',
        sellingPrice: 4000,
        currentStock: 10,
        averageCost: 2000,
        createdAt: '',
        updatedAt: '',
      },
    },
    {
      id: 'item-2',
      quantity: 1,
      unitPrice: 3000,
      totalAmount: 3000,
      createdAt: '26/06/2026 10:00:00',
      updatedAt: '26/06/2026 10:00:00',
      product: {
        id: 'prod-2',
        name: 'Galaxy S23',
        sellingPrice: 3500,
        currentStock: 5,
        averageCost: 3000,
        createdAt: '',
        updatedAt: '',
      },
    },
  ],
};

describe('PurchaseItemsModal', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('delegates show() to AppModal', async () => {
    const wrapper = mount(PurchaseItemsModal, { props: { purchase: mockPurchase } });
    await (wrapper.vm as unknown as { show(): void }).show();
    expect(mockModalInstance.show).toHaveBeenCalledOnce();
  });

  it('delegates hide() to AppModal', async () => {
    const wrapper = mount(PurchaseItemsModal, { props: { purchase: mockPurchase } });
    await (wrapper.vm as unknown as { hide(): void }).hide();
    expect(mockModalInstance.hide).toHaveBeenCalledOnce();
  });

  it('displays the supplier name in the modal title', () => {
    const wrapper = mount(PurchaseItemsModal, { props: { purchase: mockPurchase } });
    expect(wrapper.text()).toContain('Fornecedor ABC');
  });

  it('renders one row per item', () => {
    const wrapper = mount(PurchaseItemsModal, { props: { purchase: mockPurchase } });
    expect(wrapper.findAll('tbody tr')).toHaveLength(2);
  });

  it('renders product names in item rows', () => {
    const wrapper = mount(PurchaseItemsModal, { props: { purchase: mockPurchase } });
    expect(wrapper.text()).toContain('iPhone 14');
    expect(wrapper.text()).toContain('Galaxy S23');
  });

  it('renders a tfoot totals row with the purchase total amount', () => {
    const wrapper = mount(PurchaseItemsModal, { props: { purchase: mockPurchase } });
    expect(wrapper.find('tfoot').exists()).toBe(true);
    expect(wrapper.find('tfoot').text()).toContain('Total');
  });

  it('does not render a Lucro column', () => {
    const wrapper = mount(PurchaseItemsModal, { props: { purchase: mockPurchase } });
    expect(wrapper.text()).not.toContain('Lucro');
  });

  it('does not render tbody when purchase is null', () => {
    const wrapper = mount(PurchaseItemsModal, { props: { purchase: null } });
    expect(wrapper.find('tbody').exists()).toBe(false);
  });
});
