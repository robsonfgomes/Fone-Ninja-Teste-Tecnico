import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import PurchaseFormFields from '@/views/purchases/components/PurchaseFormFields.vue';
import type { PurchaseFormData } from '@/types/purchase';

const defaultModel: PurchaseFormData = { supplierName: '' };

describe('PurchaseFormFields', () => {
  it('renders the supplierName input', () => {
    const wrapper = mount(PurchaseFormFields, {
      props: { modelValue: defaultModel },
    });
    expect(wrapper.find('input[name="supplierName"]').exists()).toBe(true);
  });

  it('supplierName input has required, minlength="3" and maxlength="255" attributes', () => {
    const wrapper = mount(PurchaseFormFields, {
      props: { modelValue: defaultModel },
    });
    const input = wrapper.find('input[name="supplierName"]');
    expect(input.attributes('required')).toBeDefined();
    expect(input.attributes('minlength')).toBe('3');
    expect(input.attributes('maxlength')).toBe('255');
  });

  it('updates supplierName in the model when input changes', async () => {
    const modelValue: PurchaseFormData = { supplierName: '' };
    const wrapper = mount(PurchaseFormFields, { props: { modelValue } });
    await wrapper.find('input[name="supplierName"]').setValue('Fornecedor ABC');
    expect(modelValue.supplierName).toBe('Fornecedor ABC');
  });

  it('reflects the modelValue prop in the supplierName input', () => {
    const wrapper = mount(PurchaseFormFields, {
      props: { modelValue: { supplierName: 'Fornecedor XYZ' } },
    });
    const input = wrapper.find('input[name="supplierName"]').element as HTMLInputElement;
    expect(input.value).toBe('Fornecedor XYZ');
  });

  it('sets supplierName to empty string when input is cleared', async () => {
    const modelValue: PurchaseFormData = { supplierName: 'Fornecedor ABC' };
    const wrapper = mount(PurchaseFormFields, { props: { modelValue } });
    await wrapper.find('input[name="supplierName"]').setValue('');
    expect(modelValue.supplierName).toBe('');
  });
});
