import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount } from '@vue/test-utils';
import AppModal from '@/components/AppModal.vue';

const mockModalInstance = {
  show: vi.fn(),
  hide: vi.fn(),
  dispose: vi.fn(),
};

vi.mock('bootstrap', () => ({
  Modal: vi.fn(function () { return mockModalInstance; }),
}));

describe('AppModal', () => {
  beforeEach(() => vi.clearAllMocks());

  it('renders the title prop in the modal header', () => {
    const wrapper = mount(AppModal, { props: { title: 'Meu Modal' } });
    expect(wrapper.find('.modal-title').text()).toBe('Meu Modal');
  });

  it('renders the #header slot when provided', () => {
    const wrapper = mount(AppModal, {
      props: { title: 'fallback' },
      slots: { header: '<span class="custom-header">Personalizado</span>' },
    });
    expect(wrapper.find('.custom-header').text()).toBe('Personalizado');
    expect(wrapper.find('.modal-title').exists()).toBe(false);
  });

  it('renders the #body slot content', () => {
    const wrapper = mount(AppModal, {
      props: { title: '' },
      slots: { body: '<p class="body-content">Conteúdo</p>' },
    });
    expect(wrapper.find('.modal-body .body-content').text()).toBe('Conteúdo');
  });

  it('renders the #footer slot when provided', () => {
    const wrapper = mount(AppModal, {
      props: { title: '' },
      slots: { footer: '<button class="custom-footer">OK</button>' },
    });
    expect(wrapper.find('.modal-footer .custom-footer').text()).toBe('OK');
  });

  it('renders a default close button in the footer when #footer slot is not provided', () => {
    const wrapper = mount(AppModal, { props: { title: '' } });
    expect(wrapper.find('.modal-footer button').exists()).toBe(true);
  });

  it('applies modal-lg class when size is lg', () => {
    const wrapper = mount(AppModal, { props: { title: '', size: 'lg' } });
    expect(wrapper.find('.modal-dialog').classes()).toContain('modal-lg');
  });

  it('does not apply a size class when size is empty', () => {
    const wrapper = mount(AppModal, { props: { title: '', size: '' } });
    const classes = wrapper.find('.modal-dialog').classes();
    expect(classes).not.toContain('modal-lg');
    expect(classes).not.toContain('modal-sm');
  });

  it('calls bootstrap Modal show() when show() is called', async () => {
    const wrapper = mount(AppModal, { props: { title: '' } });
    await (wrapper.vm as unknown as { show(): void }).show();
    expect(mockModalInstance.show).toHaveBeenCalledOnce();
  });

  it('calls bootstrap Modal hide() when hide() is called', async () => {
    const wrapper = mount(AppModal, { props: { title: '' } });
    await (wrapper.vm as unknown as { hide(): void }).hide();
    expect(mockModalInstance.hide).toHaveBeenCalledOnce();
  });

  it('emits hidden event when Bootstrap fires hidden.bs.modal', async () => {
    const wrapper = mount(AppModal, { props: { title: '' } });
    const modalEl = wrapper.find('.modal').element;
    modalEl.dispatchEvent(new Event('hidden.bs.modal'));
    expect(wrapper.emitted('hidden')).toHaveLength(1);
  });
});
