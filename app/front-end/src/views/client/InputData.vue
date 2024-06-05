<template>
    <div class="flex-col">
        <InputDate title="Дата начала аренды"></InputDate>
        <InputDate title="Дата конца аренды"></InputDate>
        <InputStation></InputStation>
        <InputCar></InputCar>
        <InputSubmit></InputSubmit>
    </div>
</template>

<script setup>
import InputDate from '@/components/inputs/InputDate.vue';
import InputStation from '@/components/inputs/InputStation.vue';
import InputCar from '@/components/inputs/InputCar.vue';
import InputSubmit from '@/components/inputs/InputSubmit.vue';
</script>

<style scoped>
</style>