<template>
    <div class="w-80 bg-white m-auto rounded-lg mt-5 p-3">
        <div class="text-center">
            {{ title }}
        </div>
        <div class="flex justify-between">
            <v-date-input prepend-icon="" class="mr-10" max-width="300" variant="underlined" label="Дата"></v-date-input>
            <v-text-field variant="underlined" label="Время" v-mask="'##:##'"></v-text-field>
            
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue'

const props = defineProps({
    title: String
});

let title = props.title;
</script>

<style scoped>
</style>