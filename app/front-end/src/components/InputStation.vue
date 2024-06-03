<template>
    <div class="w-80 bg-white flex justify-center m-auto rounded-lg mt-5">
        <v-autocomplete
        label="Пункт обслуживания"
        variant="underlined"
        max-width="230"
        :items="['California', 'Colorado', 'Florida', 'Georgia', 'Texas', 'Wyoming']"
        ></v-autocomplete>
    </div>
</template>

<script setup>

</script>

<style lang="scss" scoped>

</style>