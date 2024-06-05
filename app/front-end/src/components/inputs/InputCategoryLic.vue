<template>
  <div class=" bg-white m-auto rounded-lg pl-2 pr-2">
    <v-select
      variant="underlined"
      label="Категории ВУ"
      :items="[
        'A',
        'B',
        'C',
        'BE',
        'CE',
        'M',
      ]"
      multiple
    ></v-select>
  </div>
</template>

<script setup></script>

<style scoped></style>
