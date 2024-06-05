<template>
    <div>
        <div class="w-80 bg-white flex justify-center m-auto rounded-lg mt-5">
        <v-autocomplete
        label="Автомобиль"
        variant="underlined"
        max-width="230"
        :items="['California', 'Colorado', 'Florida', 'Georgia', 'Texas', 'Wyoming']"
        ></v-autocomplete>
    </div>
    </div>
</template>

<script setup>
    
</script>

<style scoped>

</style>