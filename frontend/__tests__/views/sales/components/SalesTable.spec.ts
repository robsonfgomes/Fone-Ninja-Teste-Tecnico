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
};

const cancelledSale: Sale = {
  id: '2',
  customerName: 'Maria Souza',
  status: 'Cancelled',
  totalAmount: 200,
  profit: -50,
  createdAt: '26/06/2026 09:00:00',
  updatedAt: '26/06/2026 09:00:00',
};

describe('SalesTable', () => {
  it('shows the cancel button only for Active sales', () => {
    const wrapper = mount(SalesTable, {
      props: { sales: [activeSale, cancelledSale] },
    });

    const rows = wrapper.findAll('tbody tr');
    expect(rows[0].find('button.btn-danger').exists()).toBe(true);
    expect(rows[1].find('button.btn-danger').exists()).toBe(false);
  });

  it('emits cancel-sale with the sale when the cancel button is clicked', async () => {
    const wrapper = mount(SalesTable, {
      props: { sales: [activeSale] },
    });

    await wrapper.find('button.btn-danger').trigger('click');

    expect(wrapper.emitted('cancel-sale')).toBeTruthy();
    expect(wrapper.emitted('cancel-sale')![0]).toEqual([activeSale]);
  });
});
