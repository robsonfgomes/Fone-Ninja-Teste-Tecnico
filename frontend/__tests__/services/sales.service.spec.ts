import { describe, it, expect, vi, beforeEach } from 'vitest';
import { salesService } from '@/services/sales.service';
import { api } from '@/services/api';

vi.mock('@/services/api', () => ({
  api: {
    get: vi.fn(),
    post: vi.fn(),
    patch: vi.fn(),
  },
}));

describe('salesService.cancel', () => {
  beforeEach(() => vi.clearAllMocks());

  it('calls PATCH /vendas/{id} with status Cancelled', async () => {
    vi.mocked(api.patch).mockResolvedValue({});

    await salesService.cancel('42', 'Cancelled');

    expect(api.patch).toHaveBeenCalledWith('/vendas/42', { status: 'Cancelled' });
  });
});
