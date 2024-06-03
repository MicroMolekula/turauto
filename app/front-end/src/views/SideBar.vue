<template>
    <div>
        <v-navigation-drawer max-height="300" :width="widthSideBar" color="rgba(54, 169, 178, 0.8)">
            <v-list-item class="ml-0 mt-3 text-center pl-0 text-xl" :height="10">Заказ Авто</v-list-item>
            <InputData class="w-auto"></InputData>
        </v-navigation-drawer>
    </div>
</template>

<script setup>
import InputData from "@/views/InputData.vue"
import {ref} from 'vue'

const props = defineProps({
    width: Number
});

let widthSideBar = ref(props.width);
</script>

<style scoped>
</style>