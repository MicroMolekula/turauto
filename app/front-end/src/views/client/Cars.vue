<template>
  <div>
    <v-data-iterator
      :items="cars"
      :items-per-page="cars.length"
      v-slot="{ items }"
    >
      <v-row>
        <v-col v-for="item in items" cols="12" sm="4" xl="3">
          <CarCards
            :price="item.raw.price"
            :title="item.raw.title"
            :img="sourceUrl + item.raw.img"
            :status="item.raw.status"
          ></CarCards>
        </v-col>
      </v-row>
    </v-data-iterator>
  </div>
</template>

<script setup>
import CarCards from "@/components/cards/CarCards.vue";
import { ref } from "vue";
import axios from "axios";

let sourceUrl = "http://localhost";

let cars = ref([]);

axios.get(sourceUrl + "/car").then(function (response) {
  cars.value = response.data;
});
</script>

<style scoped>
* {
  width: 100%;
  border: none;
}
</style>
