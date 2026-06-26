import { describe, it, expect } from 'vitest';
import { formatCurrency } from '@/utils/format';

describe('formatCurrency', () => {
  it('returns em dash for null', () => {
    expect(formatCurrency(null)).toBe('—');
  });

  it('formats zero as R$ 0,00', () => {
    expect(formatCurrency(0)).toBe('R$ 0,00');
  });

  it('formats a positive value', () => {
    expect(formatCurrency(1234.56)).toBe('R$ 1.234,56');
  });

  it('formats a negative value (loss)', () => {
    expect(formatCurrency(-50)).toBe('-R$ 50,00');
  });
});
