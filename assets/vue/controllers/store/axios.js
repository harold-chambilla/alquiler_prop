// src/axios.js
import axios from 'axios';
import { useAuthStore } from './auth';

const axiosInstance = axios.create({
  baseURL: '/', // Asegúrate de cambiar esto por tu URL base real
});

axiosInstance.interceptors.request.use(config => {
  const authStore = useAuthStore();
  const token = authStore.token;
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
}, error => {
  return Promise.reject(error);
});

export default axiosInstance;
