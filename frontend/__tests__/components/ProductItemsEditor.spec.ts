import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import ProductItemsEditor from '@/components/ProductItemsEditor.vue';
import type { Product } from '@/types/product';
import type { ProductItemEditor } from '@/types/order';

const mockProducts: Product[] = [
  { id: '1', name: 'iPhone', sellingPrice: 999, currentStock: 5, averageCost: null, createdAt: '', updatedAt: '' },
  { id: '2', name: 'Samsung', sellingPrice: 799, currentStock: 3, averageCost: null, createdAt: '', updatedAt: '' },
];

describe('ProductItemsEditor', () => {
  it('renders the "Adicionar produto" button', () => {
    const wrapper = mount(ProductItemsEditor, {
      props: { modelValue: [], products: mockProducts },
    });
    expect(wrapper.find('button').text()).toContain('Adicionar produto');
  });

  it('adds a row with default values when the add button is clicked', async () => {
    const items: ProductItemEditor[] = [];
    const wrapper = mount(ProductItemsEditor, {
      props: { modelValue: items, products: mockProducts },
    });
    await wrapper.find('button').trigger('click');
    expect(items).toHaveLength(1);
    expect(items[0]).toEqual({ productId: '', quantity: '', unitPrice: '' });
  });

  it('removes the first row when its trash button is clicked (2 rows)', async () => {
    const items: ProductItemEditor[] = [
      { productId: '1', quantity: '1', unitPrice: '999' },
      { productId: '2', quantity: '2', unitPrice: '799' },
    ];
    const wrapper = mount(ProductItemsEditor, {
      props: { modelValue: items, products: mockProducts },
    });
    await wrapper.findAll('.btn-outline-danger')[0].trigger('click');
    expect(items).toHaveLength(1);
    expect(items[0].productId).toBe('2');
  });

  it('disables the trash button when there is only one row', () => {
    const wrapper = mount(ProductItemsEditor, {
      props: {
        modelValue: [{ productId: '1', quantity: '1', unitPrice: '999' }],
        products: mockProducts,
      },
    });
    expect(wrapper.find('.btn-outline-danger').attributes('disabled')).toBeDefined();
  });

  it('excludes already-selected products from other rows dropdowns', () => {
    const wrapper = mount(ProductItemsEditor, {
      props: {
        modelValue: [
          { productId: '1', quantity: '1', unitPrice: '' },
          { productId: '', quantity: '1', unitPrice: '' },
        ],
        products: mockProducts,
      },
    });
    const selects = wrapper.findAll('select');
    const secondRowOptionValues = selects[1].findAll('option').map(o => o.attributes('value'));
    expect(secondRowOptionValues).not.toContain('1');
    expect(secondRowOptionValues).toContain('2');
  });

  it('does not auto-fill unitPrice when a product is selected', async () => {
    const items: ProductItemEditor[] = [{ productId: '', quantity: '', unitPrice: '' }];
    const wrapper = mount(ProductItemsEditor, {
      props: { modelValue: items, products: mockProducts },
    });
    await wrapper.find('select').setValue('1');
    expect(items[0].unitPrice).toBe('');
  });

  it('does not pre-fill unitPrice when autoFillPrice is false', async () => {
    const items: ProductItemEditor[] = [{ productId: '', quantity: '', unitPrice: '' }];
    const wrapper = mount(ProductItemsEditor, {
      props: { modelValue: items, products: mockProducts },
    });
    await wrapper.find('select').setValue('1');
    expect(items[0].unitPrice).toBe('');
  });

  it('disables the add button when all products are already selected', () => {
    const wrapper = mount(ProductItemsEditor, {
      props: {
        modelValue: [
          { productId: '1', quantity: '1', unitPrice: '999' },
          { productId: '2', quantity: '1', unitPrice: '799' },
        ],
        products: mockProducts,
      },
    });
    expect(wrapper.find('button').attributes('disabled')).toBeDefined();
  });
});
