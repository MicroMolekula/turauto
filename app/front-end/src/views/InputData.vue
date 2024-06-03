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
import InputDate from '@/components/InputDate.vue';
import InputStation from '@/components/InputStation.vue';
import InputCar from '@/components/InputCar.vue';
import InputSubmit from '@/components/InputSubmit.vue';
</script>

<style scoped>
</style>