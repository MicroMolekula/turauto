<template>
  <div>
    <v-app>
      <AppBar></AppBar>
      <v-main class="d-flex">
        <div class="mt-10 w-1/3 m-auto h-5/6 color-card rounded-lg">
          <div class="text-xl text-center mt-5 mb-5">Регистрация</div>
          <InputName class="w-11/12 mt-2" label="Фамилия"></InputName>
          <InputName class="w-11/12 mt-2" label="Имя"></InputName>
          <InputName class="w-11/12 mt-2" label="Отчество*"></InputName>
          <div class="flex w-11/12 mt-2 m-auto">
            <InputName class="w-1/2" label="Email"></InputName>
            <InputPasport class="ml-2 w-1/2"></InputPasport>
          </div>
          <div class="flex w-11/12 mt-2 m-auto">
            <InputCategoryLic class="w-1/3"></InputCategoryLic>
            <InputNumberLic class="w-1/3 ml-2"></InputNumberLic>
            <InputDateLic class="w-1/3 ml-2"></InputDateLic>
          </div>
          <InputPhone class="mt-2 w-11/12 m-auto"></InputPhone>
          <InputPassword class="mt-2 w-11/12 m-auto"></InputPassword>
          <div class="w-80 m-auto rounded-lg mt-10">
            <v-btn class="bg-white w-100 m-0" variant="outlined">
              Зарегистрироваться
            </v-btn>
          </div>
        </div>
      </v-main>
    </v-app>
  </div>
</template>

<script setup>
import AppBar from "./AppBar.vue";
import InputName from "@/components/inputs/InputName.vue";
import InputPasport from "@/components/inputs/InputPasport.vue";
import InputCategoryLic from "@/components/inputs/InputCategoryLic.vue";
import InputNumberLic from "@/components/inputs/InputNumberLic.vue";
import InputDateLic from "@/components/inputs/InputDateLic.vue";
import InputPhone from "@/components/inputs/InputPhone.vue";
import InputPassword from "@/components/inputs/InputPassword.vue";
</script>

<style scoped>
.color-card {
  background-color: rgba(54, 169, 178, 0.8);
}
</style>
