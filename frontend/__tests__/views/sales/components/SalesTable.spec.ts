import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import SalesTable from '@/views/sales/components/SalesTable.vue';
import type { Sale } from '@/types/sale';

const activeSale: Sale = {
  id: '1',
  customerName: 'João Silva',
  status: 'Active',
  totalAmount: 500,
  profit: 100,
  createdAt: '26/06/2026 10:00:00',
  updatedAt: '26/06/2026 10:00:00',
  items: [],
};

const cancelledSale: Sale = {
  id: '2',
  customerName: 'Maria Souza',
  status: 'Cancelled',
  totalAmount: 200,
  profit: -50,
  createdAt: '26/06/2026 09:00:00',
  updatedAt: '26/06/2026 09:00:00',
  items: [],
};

describe('SalesTable', () => {
  it('shows the cancel button disabled only for Cancelled sales', () => {
    const wrapper = mount(SalesTable, {
      props: { sales: [activeSale, cancelledSale] },
    });

    const rows = wrapper.findAll('tbody tr');
    expect(rows[0].find('button.btn-danger').exists()).toBe(true);
    expect(rows[0].find('button.btn-danger').attributes('disabled')).toBeUndefined();
    expect(rows[1].find('button.btn-danger').exists()).toBe(true);
    expect(rows[1].find('button.btn-danger').attributes('disabled')).toBeDefined();
  });

  it('emits cancel-sale with the sale when the cancel button is clicked', async () => {
    const wrapper = mount(SalesTable, { props: { sales: [activeSale] } });
    await wrapper.find('button.btn-danger').trigger('click');
    expect(wrapper.emitted('cancel-sale')).toBeTruthy();
    expect(wrapper.emitted('cancel-sale')![0]).toEqual([activeSale]);
  });

  it('shows the view-items button on every row regardless of status', () => {
    const wrapper = mount(SalesTable, {
      props: { sales: [activeSale, cancelledSale] },
    });
    const rows = wrapper.findAll('tbody tr');
    expect(rows[0].find('button.btn-info').exists()).toBe(true);
    expect(rows[1].find('button.btn-info').exists()).toBe(true);
  });

  it('emits view-items with the sale when the view button is clicked', async () => {
    const wrapper = mount(SalesTable, { props: { sales: [activeSale] } });
    await wrapper.find('button.btn-info').trigger('click');
    expect(wrapper.emitted('view-items')).toBeTruthy();
    expect(wrapper.emitted('view-items')![0]).toEqual([activeSale]);
  });
});
