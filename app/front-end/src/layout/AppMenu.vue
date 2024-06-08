<script setup>
import { ref } from 'vue';

import AppMenuItem from './AppMenuItem.vue';

const model = ref([
    {
        label: 'Главная',
        items: [
            { label: 'Заказы', icon: 'pi pi-fw pi-book', to: '/booking', 'visible': localStorage.getItem("user_role") != 'client' },
            { label: 'Mенеджеры', icon: 'pi pi-fw pi-user', to: '/manager', 'visible': localStorage.getItem("user_role") != 'client' },
            { label: 'Пункты обслуживания', icon: 'pi pi-fw pi-building', to: '/station_service' },
            { label: 'Клиенты', icon: 'pi pi-fw pi-user', to: '/client', 'visible': localStorage.getItem("user_role") != 'client' },
            { label: 'Автомобили', icon: 'pi pi-fw pi-car', to: '/car', 'visible': localStorage.getItem("user_role") != 'client' },
            { label: 'Классы Автомобилей', icon: 'pi pi-fw pi-sitemap', to: '/car_class', 'visible': localStorage.getItem("user_role") != 'client' },
            { label: 'Дополнительные опции', icon: 'pi pi-fw pi-plus', to: '/add_service', 'visible': localStorage.getItem("user_role") != 'client' },
        ]
    },
    
]);
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<style lang="scss" scoped></style>
