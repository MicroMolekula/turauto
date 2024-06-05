<template>
  <v-app class="flex-row w-100 hidden">
    <v-layout>
      <SideBar :width="400"></SideBar>
      <AppBar></AppBar>
      <v-main class="ml-5 mt-5" style="min-height: 300px">
        <div class="text-start text-2xl ml-4">
            {{ `${user.surname} ${user.name} ${user.middlename}` }}
        </div>
        <v-container>
          <v-data-iterator :items="items" :page="page">
            <template v-slot:default="{ items }">
              <template v-for="(item, i) in items" :key="i">
                <v-card v-bind="item.raw"></v-card>
                <br />
              </template>
            </template>
            <template v-slot:header="{page, pageCount, prevPage, nextPage}">
                <v-btn
                    :disabled="page === 1"
                    class="me-2"
                    icon="mdi-arrow-left"
                    size="small"
                    variant="tonal"
                    @click="prevPage"
                ></v-btn>
                <v-btn
                    :disabled="page === pageCount"
                    icon="mdi-arrow-right"
                    size="small"
                    variant="tonal"
                    @click="nextPage"
                ></v-btn>
              </template>
          </v-data-iterator>
        </v-container>
      </v-main>
    </v-layout>
  </v-app>
</template>

<script setup>
import { RouterLink, RouterView } from "vue-router";
import { ref } from "vue";
import SideBar from "@/views/client/SideBar.vue";
import AppBar from "@/views/client/AppBar.vue";

const page = ref(1);
let user = ref({
    surname: "Красиков",
    name: "Иван",
    middlename: "Александрович"
});
const items = Array.from({ length: 15 }, (k, v) => ({
  title: "Item " + v + 1,
  text: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, ratione debitis quis est labore voluptatibus! Eaque cupiditate minima, at placeat totam, magni doloremque veniam neque porro libero rerum unde voluptatem!",
}));
</script>

<style scoped></style>
