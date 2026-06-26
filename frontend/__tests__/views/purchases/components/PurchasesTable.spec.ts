import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import PurchasesTable from '@/views/purchases/components/PurchasesTable.vue';
import type { PurchaseOrder } from '@/types/purchase';

const mockPurchase: PurchaseOrder = {
  id: '1',
  supplierName: 'Fornecedor ABC',
  totalAmount: 7000,
  createdAt: '26/06/2026 10:00:00',
  updatedAt: '26/06/2026 10:00:00',
  items: [],
};

describe('PurchasesTable', () => {
  it('shows empty state when purchases is empty', () => {
    const wrapper = mount(PurchasesTable, { props: { purchases: [] } });
    expect(wrapper.text()).toContain('Nenhuma compra registrada.');
  });

  it('shows the view-items button on every row', () => {
    const wrapper = mount(PurchasesTable, { props: { purchases: [mockPurchase] } });
    expect(wrapper.find('tbody tr').find('button.btn-info').exists()).toBe(true);
  });

  it('emits view-items with the purchase when the view button is clicked', async () => {
    const wrapper = mount(PurchasesTable, { props: { purchases: [mockPurchase] } });
    await wrapper.find('button.btn-info').trigger('click');
    expect(wrapper.emitted('view-items')).toBeTruthy();
    expect(wrapper.emitted('view-items')![0]).toEqual([mockPurchase]);
  });
});
