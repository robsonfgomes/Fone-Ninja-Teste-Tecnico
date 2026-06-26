import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount } from '@vue/test-utils';
import SaleItemsModal from '@/views/sales/components/SaleItemsModal.vue';
import type { Sale } from '@/types/sale';

const mockModalInstance = {
  show: vi.fn(),
  hide: vi.fn(),
  dispose: vi.fn(),
};

vi.mock('bootstrap', () => ({
  Modal: vi.fn(function () { return mockModalInstance; }),
}));

const mockSale: Sale = {
  id: '1',
  customerName: 'João Silva',
  status: 'Active',
  totalAmount: 11500,
  profit: 1600,
  createdAt: '26/06/2026 10:00:00',
  updatedAt: '26/06/2026 10:00:00',
  items: [
    {
      id: 'item-1',
      quantity: 2,
      unitPrice: 4000,
      totalAmount: 8000,
      profit: 1200,
      createdAt: '26/06/2026 10:00:00',
      updatedAt: '26/06/2026 10:00:00',
      product: {
        id: 'prod-1',
        name: 'iPhone 14',
        sellingPrice: 4000,
        currentStock: 10,
        averageCost: 3400,
        createdAt: '',
        updatedAt: '',
      },
    },
    {
      id: 'item-2',
      quantity: 1,
      unitPrice: 3500,
      totalAmount: 3500,
      profit: 400,
      createdAt: '26/06/2026 10:00:00',
      updatedAt: '26/06/2026 10:00:00',
      product: {
        id: 'prod-2',
        name: 'Galaxy S23',
        sellingPrice: 3500,
        currentStock: 5,
        averageCost: 3100,
        createdAt: '',
        updatedAt: '',
      },
    },
  ],
};

describe('SaleItemsModal', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('delegates show() to AppModal', async () => {
    const wrapper = mount(SaleItemsModal, { props: { sale: mockSale } });
    await (wrapper.vm as unknown as { show(): void }).show();
    expect(mockModalInstance.show).toHaveBeenCalledOnce();
  });

  it('delegates hide() to AppModal', async () => {
    const wrapper = mount(SaleItemsModal, { props: { sale: mockSale } });
    await (wrapper.vm as unknown as { hide(): void }).hide();
    expect(mockModalInstance.hide).toHaveBeenCalledOnce();
  });

  it('displays the customer name in the modal title', () => {
    const wrapper = mount(SaleItemsModal, { props: { sale: mockSale } });
    expect(wrapper.text()).toContain('João Silva');
  });

  it('renders one row per item', () => {
    const wrapper = mount(SaleItemsModal, { props: { sale: mockSale } });
    expect(wrapper.findAll('tbody tr')).toHaveLength(2);
  });

  it('renders product names in item rows', () => {
    const wrapper = mount(SaleItemsModal, { props: { sale: mockSale } });
    expect(wrapper.text()).toContain('iPhone 14');
    expect(wrapper.text()).toContain('Galaxy S23');
  });

  it('renders a tfoot totals row with summed quantity', () => {
    const wrapper = mount(SaleItemsModal, { props: { sale: mockSale } });
    expect(wrapper.find('tfoot').exists()).toBe(true);
    expect(wrapper.find('tfoot').text()).toContain('3'); // 2 + 1
  });

  it('applies text-success to profit total when positive', () => {
    const wrapper = mount(SaleItemsModal, { props: { sale: mockSale } });
    expect(wrapper.find('tfoot td:last-child').classes()).toContain('text-success');
  });

  it('applies text-danger to profit total when negative', () => {
    const negSale: Sale = {
      ...mockSale,
      items: [{ ...mockSale.items[0], profit: -500 }],
    };
    const wrapper = mount(SaleItemsModal, { props: { sale: negSale } });
    expect(wrapper.find('tfoot td:last-child').classes()).toContain('text-danger');
  });

  it('does not render tbody when sale is null', () => {
    const wrapper = mount(SaleItemsModal, { props: { sale: null } });
    expect(wrapper.find('tbody').exists()).toBe(false);
  });
});
