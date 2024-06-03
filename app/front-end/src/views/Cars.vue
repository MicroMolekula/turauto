<template>
  <v-data-iterator :items="cars" :items-per-page="itemsPerPage" v-slot="{items, page, pageCount, prevPage, nextPage}">
      <div class="d-inline-flex mb-4">
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
      </div>
      <v-row>
        <v-col v-for="item in items" col="11" xl="3" sm="4">
          <CarCards :price="item.raw.price" :title="item.raw.title" :img="item.raw.img"></CarCards>
        </v-col>
      </v-row>
  </v-data-iterator>
</template>

<script setup>
import CarCards from "@/components/cards/CarCards.vue";
import { ref } from "vue";

let cars = [];
let itemsPerPage = ref(8);

for (let i = 0; i < 20; i++) {
  let el = {
    price: 4000,
    title: "PEUGEOT TRAVELLER",
    img: "/car.jpg"
  };
  cars.push(el);
}
</script>

<style scoped>
* {
  width: 100%;
  border: none;
}
</style>
