<template>
  <div>
    <v-app-bar>
      <v-app-bar-title></v-app-bar-title>

      <template v-slot:append>
        <v-btn class="mr-3">Зарегистрироваться</v-btn>
        <v-btn class="mr-3">Войти</v-btn>
        <v-btn icon="mdi-account-box"></v-btn>
      </template>
    </v-app-bar>
  </div>
</template>

<script setup></script>

<style scoped></style>
