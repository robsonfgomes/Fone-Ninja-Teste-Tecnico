import axios from 'axios';
import { useLoadingStore } from '@/stores/loading.store';
import { useToastStore } from '@/stores/toast.store';
import { extractErrorMessage } from '@/utils/errors';

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
    useToastStore().add(extractErrorMessage(error), 'danger');
    return Promise.reject(error);
  },
);
