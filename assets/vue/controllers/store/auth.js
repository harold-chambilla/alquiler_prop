// src/stores/auth.js
import { defineStore } from 'pinia';
import axios from './axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: null,
    isAuthenticated: false
  }),
  actions: {
    async login(credentials) {
        try {
          const response = await axios.post('/api/login_check', credentials)
          this.token = response.data.token;
          this.isAuthenticated = true;
          localStorage.setItem('authToken', this.token); // Almacenar el token en localStorage
        } catch (error) {
          console.error('Error al iniciar sesión:', error);
        }
    },
    logout() {
      this.token = null;
      this.isAuthenticated = false;
      localStorage.removeItem('authToken'); // Remover el token de localStorage
    },
    checkAuthentication() {
      const token = localStorage.getItem('authToken');
      if (token) {
        this.token = token;
        this.isAuthenticated = true;
      } else {
        this.isAuthenticated = false;
      }
    }
  }
});
