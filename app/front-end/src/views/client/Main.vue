<script setup>
import { RouterLink, RouterView } from 'vue-router'
import {ref} from 'vue'
import SideBar from "@/views/client/SideBar.vue"
import Cars from "@/views/client/Cars.vue"
import AppBar from "@/views/client/AppBar.vue"
</script>

<template>
    <v-app class="flex-row w-100 hidden">
      <v-layout>
        <SideBar :width="400"></SideBar>
        <AppBar></AppBar>
        <v-main  style="min-height: 300px;" class="d-flex justify-center">
            <Cars class="m-5"></Cars>
        </v-main>
      </v-layout>
    </v-app>
</template>

<style scoped>
 
</style>
