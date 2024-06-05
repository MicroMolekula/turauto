<template>
    <div>
      <v-app-bar>
        <v-app-bar-title></v-app-bar-title>
  
        <template v-slot:append>
          <RouterLink to="/reg"><v-btn class="mr-3">Зарегистрироваться</v-btn></RouterLink>
          <RouterLink to="/auth"><v-btn class="mr-3">Войти</v-btn></RouterLink>
          <RouterLink to="/profile"><v-btn icon="mdi-account-box"></v-btn></RouterLink>
        </template>
      </v-app-bar>
    </div>
  </template>
  
  <script setup>
  import { RouterLink, RouterView } from 'vue-router'
  </script>
  
  <style scoped></style>
  