<template>
  <div class=" bg-white m-auto rounded-lg pl-2 pr-2">
    <div class="flex justify-between">
      <v-text-field variant="underlined" :label="label"></v-text-field>
    </div>
  </div>
</template>

<script setup>
import {ref} from "vue";
const props = defineProps({
  label: String,
});

let label = ref(props.label);
</script>

<style lang="scss" scoped></style>
