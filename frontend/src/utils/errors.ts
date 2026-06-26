export function extractErrorMessage(error: unknown): string {
  const e = error as { response?: { status?: number; data?: { errors?: Record<string, string[]>; message?: string } } };

  if (e.response?.status === 422 && e.response?.data?.errors) {
    return Object.values(e.response.data.errors).flat().join('\n');
  }

  return e.response?.data?.message ?? 'Erro inesperado. Tente novamente.';
}
