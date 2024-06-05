<template>
  <div>
    <v-app>
      <AppBar></AppBar>
      <v-main class="d-flex">
        <div class="w-1/3 m-auto h-3/6 color-card rounded-lg pos">
            <div class="text-xl text-center mt-5 mb-5">Вход</div>
            <div>
                <InputName class="w-11/12 m-auto mt-10" label="Email"></InputName>
                <InputPassword class="w-11/12 m-auto mt-10"></InputPassword>
            </div>
            <div class="w-80 m-auto rounded-lg mt-10 flex-col justify-end">
                <v-btn class="bg-white w-100 m-0" variant="outlined">
                    Войти
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
import InputPassword from "@/components/inputs/InputPassword.vue";
</script>

<style scoped>
.color-card {
  background-color: rgba(54, 169, 178, 0.8);
}
.pos {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
</style>