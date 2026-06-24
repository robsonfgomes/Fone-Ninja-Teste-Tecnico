import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import AppPagination from '../AppPagination.vue';
import type { PaginationMeta } from '@/types/pagination';

function makeMeta(overrides: Partial<PaginationMeta> = {}): PaginationMeta {
  return {
    current_page: 1,
    last_page: 3,
    from: 1,
    to: 10,
    total: 30,
    per_page: 10,
    links: [
      { url: null, label: '&laquo; Previous', page: null, active: false },
      { url: '/api/produtos?page=1', label: '1', page: 1, active: true },
      { url: '/api/produtos?page=2', label: '2', page: 2, active: false },
      { url: '/api/produtos?page=3', label: '3', page: 3, active: false },
      { url: '/api/produtos?page=2', label: 'Next &raquo;', page: 2, active: false },
    ],
    ...overrides,
  };
}

describe('AppPagination', () => {
  it('renders nothing when last_page is 1', () => {
    const wrapper = mount(AppPagination, {
      props: { meta: makeMeta({ last_page: 1, total: 5 }) },
    });
    expect(wrapper.find('nav').exists()).toBe(false);
  });

  it('renders 3 page number buttons', () => {
    const wrapper = mount(AppPagination, { props: { meta: makeMeta() } });
    const buttons = wrapper.findAll('.page-item:not(:first-child):not(:last-child) .page-link');
    expect(buttons).toHaveLength(3);
    expect(buttons[0].text()).toBe('1');
    expect(buttons[1].text()).toBe('2');
    expect(buttons[2].text()).toBe('3');
  });

  it('marks the active page with the active class', () => {
    const wrapper = mount(AppPagination, {
      props: { meta: makeMeta({ current_page: 2 }) },
    });
    const items = wrapper.findAll('.page-item');
    // items: [prev, 1, 2, 3, next] — index 2 is page 2
    expect(items[2].classes()).toContain('active');
  });

  it('disables prev button on first page', () => {
    const wrapper = mount(AppPagination, { props: { meta: makeMeta({ current_page: 1 }) } });
    const prevItem = wrapper.find('.page-item:first-child');
    expect(prevItem.classes()).toContain('disabled');
    expect(prevItem.find('button').attributes('disabled')).toBeDefined();
  });

  it('disables next button on last page', () => {
    const wrapper = mount(AppPagination, {
      props: { meta: makeMeta({ current_page: 3, last_page: 3 }) },
    });
    const nextItem = wrapper.find('.page-item:last-child');
    expect(nextItem.classes()).toContain('disabled');
    expect(nextItem.find('button').attributes('disabled')).toBeDefined();
  });

  it('emits page-change with the page number when clicking a page button', async () => {
    const wrapper = mount(AppPagination, { props: { meta: makeMeta() } });
    const pageButtons = wrapper.findAll('.page-item:not(:first-child):not(:last-child) .page-link');
    await pageButtons[1].trigger('click'); // page 2
    expect(wrapper.emitted('page-change')).toEqual([[2]]);
  });

  it('emits page-change with previous page when clicking prev', async () => {
    const wrapper = mount(AppPagination, {
      props: { meta: makeMeta({ current_page: 2 }) },
    });
    await wrapper.find('.page-item:first-child button').trigger('click');
    expect(wrapper.emitted('page-change')).toEqual([[1]]);
  });

  it('emits page-change with next page when clicking next', async () => {
    const wrapper = mount(AppPagination, { props: { meta: makeMeta() } });
    await wrapper.find('.page-item:last-child button').trigger('click');
    expect(wrapper.emitted('page-change')).toEqual([[2]]);
  });
});
