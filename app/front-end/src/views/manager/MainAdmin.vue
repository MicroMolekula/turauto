<template>
    <div>
        <v-app>
            <v-layout>
                <SideBarAdmin></SideBarAdmin>
                <AppBarAdmin></AppBarAdmin>
                <v-main>
                    <ClientCRUD></ClientCRUD>
                </v-main>
            </v-layout>
        </v-app>
    </div>
</template>

<script setup>
import AppBarAdmin from "./AppBarAdmin.vue";
import SideBarAdmin from "./SideBarAdmin.vue";
import StationServiceCRUD from "./StationServiceCRUD.vue";
import ManagerCRUD from "./ManagerCRUD.vue";
import ClientCRUD from "./ClientCRUD.vue";
</script>

<style scoped>

</style>