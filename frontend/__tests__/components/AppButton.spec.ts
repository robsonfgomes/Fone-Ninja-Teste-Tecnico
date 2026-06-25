import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import AppButton from '@/components/AppButton.vue';

describe('AppButton', () => {
  it('renders slot content', () => {
    const wrapper = mount(AppButton, { slots: { default: 'Salvar' } });
    expect(wrapper.text()).toContain('Salvar');
  });

  it('applies btn-primary class by default', () => {
    const wrapper = mount(AppButton);
    expect(wrapper.find('button').classes()).toContain('btn-primary');
  });

  it('applies btn-{variant} class when variant is provided', () => {
    const wrapper = mount(AppButton, { props: { variant: 'danger' } });
    expect(wrapper.find('button').classes()).toContain('btn-danger');
    expect(wrapper.find('button').classes()).not.toContain('btn-primary');
  });

  it('applies btn-sm when size is sm', () => {
    const wrapper = mount(AppButton, { props: { size: 'sm' } });
    expect(wrapper.find('button').classes()).toContain('btn-sm');
  });

  it('applies btn-lg when size is lg', () => {
    const wrapper = mount(AppButton, { props: { size: 'lg' } });
    expect(wrapper.find('button').classes()).toContain('btn-lg');
  });

  it('does not apply a size class when size is empty string', () => {
    const wrapper = mount(AppButton, { props: { size: '' } });
    expect(wrapper.find('button').classes()).not.toContain('btn-sm');
    expect(wrapper.find('button').classes()).not.toContain('btn-lg');
  });

  it('sets type attribute to button by default', () => {
    const wrapper = mount(AppButton);
    expect(wrapper.find('button').attributes('type')).toBe('button');
  });

  it('sets type attribute when provided', () => {
    const wrapper = mount(AppButton, { props: { type: 'submit' } });
    expect(wrapper.find('button').attributes('type')).toBe('submit');
  });

  it('disables the button when disabled is true', () => {
    const wrapper = mount(AppButton, { props: { disabled: true } });
    expect(wrapper.find('button').element.disabled).toBe(true);
  });

  it('shows a spinner and disables the button when loading is true', () => {
    const wrapper = mount(AppButton, { props: { loading: true } });
    expect(wrapper.find('.spinner-border').exists()).toBe(true);
    expect(wrapper.find('button').element.disabled).toBe(true);
  });

  it('does not show a spinner when loading is false', () => {
    const wrapper = mount(AppButton, { props: { loading: false } });
    expect(wrapper.find('.spinner-border').exists()).toBe(false);
  });
});
