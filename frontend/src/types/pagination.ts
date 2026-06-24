export interface PaginationLink {
  url: string | null;
  label: string;
  page: number | null;
  active: boolean;
}

export interface PaginationMeta {
  current_page: number;
  last_page: number;
  from: number;
  to: number;
  total: number;
  per_page: number;
  links: PaginationLink[];
}

export interface PaginatedResponse<T> {
  data: T[];
  meta: PaginationMeta;
}
