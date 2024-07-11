<template>
    <!--<div>
      <div v-if="userInfo">
        <h1>User Information</h1>
        <p>{{ userInfo.name }}</p>
      </div>
      <p v-if="error">{{ error }}</p>
    </div>
    <div></div>-->
        
    <div v-if="!authStore.isAuthenticated">
      <input v-model="credentials.username" type="text" placeholder="Username">
      <input v-model="credentials.password" type="password" placeholder="Password">
      <button @click="handleLogin">Login</button>
      <p v-if="error">{{ error }}</p>
    </div>
    <div v-else>
      <p>You are logged in!</p>
      <button @click="authStore.logout">Logout</button>
    </div>

    <div>
    <h1>Perfil del Usuario</h1>
    <div v-if="userInfo">
      <p>ID: {{ userInfo.id }}</p>
      <p>Email: {{ userInfo.email }}</p>
    </div>
    <p v-if="error">{{ error }}</p>
  </div>
  </template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from './store/axios';
import { useAuthStore } from '../controllers/store/auth';

const userInfo = ref(null);
const error = ref(null);

const fetchUserInfo = async () => {
  try {
    const response = await axios.get('/api/profile'); // Ajusta la ruta según tu API
    userInfo.value = response.data;
    console.log('userrr', userInfo.value);
  } catch (err) {
    error.value = err.message;
    console.error('Error fetching user info:', err.response.data);
  }
};

// Ejecutar fetchUserInfo al cargar el componente, o basado en otros triggers
fetchUserInfo();

const authStore = useAuthStore();
const credentials = ref({
  username: '',
  password: ''
});

const handleLogin = async () => {
  try {
    await authStore.login(credentials.value);
  } catch (err) {
    error.value = 'Authentication failed: ' + err.message;
  }
};

// Verificar autenticación al cargar el componente
authStore.checkAuthentication();

onMounted(() => {
  authStore.checkAuthentication();
  if (!authStore.isAuthenticated) {
    // Manejar usuario no autenticado, por ejemplo redirigir al login
    console.log("Usuario no autenticado, por ejemplo redirigiri")
  }
});
</script>


