<template>
  <div>
    <v-card  max-width="344">
      <v-img height="200px" :src="img" cover></v-img>

      <v-card-title> {{ title }} </v-card-title>

      <v-card-subtitle> {{ getStatus(status) }} </v-card-subtitle>
      <v-expand-transition>
        <div class="text-color w-80 m-auto rounded-lg">
          <v-card-text>
            От {{ price }} рублей/сутки
          </v-card-text>
        </div>
        <div class="text-color w-80 m-auto rounded-lg">
          <v-card-text>
            От {{ price }} рублей/сутки
          </v-card-text>
        </div>
      </v-expand-transition>

      <v-card-actions>
        <v-btn :disabled="status !== 2" color="rgba(54, 169, 178, 0.8)" text="Заказать"></v-btn>

        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    title: String,
    price: Number,
    img: String,
    status: Number,
});


let title = ref(props.title)
let price = ref(props.price);
let img = ref(props.img);
let status = ref(props.status);

function getStatus(status){
  if (status === 2) {
    return "Машина доступна";
  } 
  if(status === 1){ 
    return "Машина в аренде";
  }
  if(status === 3) {
    return "Машина в ремонте";
  }
}
</script>

<style scoped>
    .text-color {
        background-color: rgba(54, 169, 178, 0.8);
    }
</style>
