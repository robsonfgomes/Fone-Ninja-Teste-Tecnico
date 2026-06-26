import axios from 'axios';
import { useLoadingStore } from '@/stores/loading.store';
import { useToastStore } from '@/stores/toast.store';

export const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

api.interceptors.request.use((config) => {
  useLoadingStore().start();
  return config;
});

api.interceptors.response.use(
  (response) => {
    useLoadingStore().stop();
    return response;
  },
  (error) => {
    useLoadingStore().stop();
    if (error.response?.status !== 422) {
      const message = error.response?.data?.message ?? 'Erro inesperado. Tente novamente.';
      useToastStore().add(message, 'error');
    }
    return Promise.reject(error);
  },
);
