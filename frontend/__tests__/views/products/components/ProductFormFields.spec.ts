import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import ProductFormFields from '@/views/products/components/ProductFormFields.vue';
import type { ProductFormData } from '@/types/product';

const defaultModel: ProductFormData = { name: '', sellingPrice: null };

describe('ProductFormFields', () => {
  it('renders the name input', () => {
    const wrapper = mount(ProductFormFields, {
      props: { modelValue: defaultModel },
    });
    expect(wrapper.find('input[name="name"]').exists()).toBe(true);
  });

  it('renders the sellingPrice input', () => {
    const wrapper = mount(ProductFormFields, {
      props: { modelValue: defaultModel },
    });
    expect(wrapper.find('input[name="sellingPrice"]').exists()).toBe(true);
  });

  it('name input has required and minlength="3" attributes', () => {
    const wrapper = mount(ProductFormFields, {
      props: { modelValue: defaultModel },
    });
    const input = wrapper.find('input[name="name"]');
    expect(input.attributes('required')).toBeDefined();
    expect(input.attributes('minlength')).toBe('3');
  });

  it('sellingPrice input has required, type="number", min="0.01" and step="0.01" attributes', () => {
    const wrapper = mount(ProductFormFields, {
      props: { modelValue: defaultModel },
    });
    const input = wrapper.find('input[name="sellingPrice"]');
    expect(input.attributes('required')).toBeDefined();
    expect(input.attributes('type')).toBe('number');
    expect(input.attributes('min')).toBe('0.01');
    expect(input.attributes('step')).toBe('0.01');
  });

  it('emits update:modelValue with updated name when name input changes', async () => {
    const wrapper = mount(ProductFormFields, {
      props: { modelValue: { name: '', sellingPrice: null } },
    });
    await wrapper.find('input[name="name"]').setValue('iPhone');
    const emitted = wrapper.emitted('update:modelValue') as ProductFormData[][];
    expect(emitted[0]![0]).toEqual({ name: 'iPhone', sellingPrice: null });
  });

  it('emits update:modelValue with updated sellingPrice when price input changes', async () => {
    const wrapper = mount(ProductFormFields, {
      props: { modelValue: { name: 'iPhone', sellingPrice: null } },
    });
    await wrapper.find('input[name="sellingPrice"]').setValue('999.99');
    const emitted = wrapper.emitted('update:modelValue') as ProductFormData[][];
    expect(emitted[0]![0]).toEqual({ name: 'iPhone', sellingPrice: 999.99 });
  });

  it('emits null for sellingPrice when price input is cleared', async () => {
    const wrapper = mount(ProductFormFields, {
      props: { modelValue: { name: 'iPhone', sellingPrice: 999 } },
    });
    await wrapper.find('input[name="sellingPrice"]').setValue('');
    const emitted = wrapper.emitted('update:modelValue') as ProductFormData[][];
    expect(emitted[0]![0]).toEqual({ name: 'iPhone', sellingPrice: null });
  });

  it('reflects the modelValue prop in the name input', () => {
    const wrapper = mount(ProductFormFields, {
      props: { modelValue: { name: 'Produto X', sellingPrice: null } },
    });
    const input = wrapper.find('input[name="name"]').element as HTMLInputElement;
    expect(input.value).toBe('Produto X');
  });

  it('reflects the modelValue prop in the sellingPrice input', () => {
    const wrapper = mount(ProductFormFields, {
      props: { modelValue: { name: '', sellingPrice: 49.9 } },
    });
    const input = wrapper.find('input[name="sellingPrice"]').element as HTMLInputElement;
    expect(input.value).toBe('49.9');
  });
});
