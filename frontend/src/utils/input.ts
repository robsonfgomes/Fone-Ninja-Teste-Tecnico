export function parseNumericInput(value: string): number | null {
  return value !== '' ? parseFloat(value) : null;
}
