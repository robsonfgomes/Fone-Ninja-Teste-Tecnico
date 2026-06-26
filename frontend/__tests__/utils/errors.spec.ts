import { describe, it, expect } from 'vitest';
import { extractErrorMessage } from '@/utils/errors';

describe('extractErrorMessage', () => {
  it('returns joined validation messages for 422 with errors', () => {
    const error = {
      response: {
        status: 422,
        data: {
          errors: {
            'products.0.quantity': ['Produto "Fone X": estoque insuficiente. Disponível: 2.'],
            'products.1.quantity': ['Produto "Cabo Y": estoque insuficiente. Disponível: 0.'],
          },
        },
      },
    };

    const result = extractErrorMessage(error);

    expect(result).toBe(
      'Produto "Fone X": estoque insuficiente. Disponível: 2.\nProduto "Cabo Y": estoque insuficiente. Disponível: 0.',
    );
  });

  it('returns the data.message for non-422 errors', () => {
    const error = {
      response: {
        status: 500,
        data: { message: 'Internal Server Error' },
      },
    };

    expect(extractErrorMessage(error)).toBe('Internal Server Error');
  });

  it('returns fallback message when response is missing', () => {
    expect(extractErrorMessage({})).toBe('Erro inesperado. Tente novamente.');
  });

  it('returns fallback message when response has no data', () => {
    const error = { response: { status: 503 } };

    expect(extractErrorMessage(error)).toBe('Erro inesperado. Tente novamente.');
  });

  it('falls through to message when 422 has no errors field', () => {
    const error = {
      response: {
        status: 422,
        data: { message: 'Unprocessable Entity' },
      },
    };

    expect(extractErrorMessage(error)).toBe('Unprocessable Entity');
  });
});
