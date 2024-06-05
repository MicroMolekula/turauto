<template>
    <div class=" bg-white m-auto rounded-lg pl-2 pr-2">
      <div class="flex justify-between">
        <v-text-field 
        variant="underlined" 
        label="Пароль"
        :type="show1 ? 'text' : 'password'"
        @click:append="show1 = !show1"
        :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
        >
        </v-text-field>
      </div>
    </div>
</template>

<script setup>
import {ref} from "vue"

let show1 = ref(false);
</script>

<style lang="scss" scoped>
</style>