<script setup>
import { ref } from 'vue';

import AppMenuItem from './AppMenuItem.vue';

const model = ref([
    {
        label: 'Главная',
        items: [
            { label: 'Заказы', icon: 'pi pi-fw pi-book', to: '/booking', 'visible': localStorage.getItem("user_role") != 'client' },
            { label: 'Mенеджеры', icon: 'pi pi-fw pi-user', to: '/manager', 'visible': localStorage.getItem("user_role") == 'admin'},
            { label: 'Пункты обслуживания', icon: 'pi pi-fw pi-building', to: '/station_service' },
            { label: 'Клиенты', icon: 'pi pi-fw pi-user', to: '/client', 'visible': localStorage.getItem("user_role") != 'client' },
            { label: 'Автомобили', icon: 'pi pi-fw pi-car', to: '/car' },
            { label: 'Классы Автомобилей', icon: 'pi pi-fw pi-sitemap', to: '/car_class', 'visible': localStorage.getItem("user_role") != 'client' },
            { label: 'Дополнительные опции', icon: 'pi pi-fw pi-plus', to: '/add_service', 'visible': localStorage.getItem("user_role") != 'client' },
            { label: 'История заказов', icon: 'pi pi-fw pi-history', to: '/history', 'visible': localStorage.getItem("user_role") == 'client' },
        ]
    },
    {
        label: 'Отчеты',
        items: [
            {label: 'Отчет по продажам сотрудников', icon: 'pi pi-credit-card', to: '/report_managers', visible: localStorage.getItem("user_role") == 'admin'},
            {label: 'Отчет по заказам клиента', icon: 'pi pi-history', to: '/report_clients', visible: localStorage.getItem("user_role") == 'admin'},
            {label: 'Отчет по заказам и автомобилям', icon: 'pi pi-chart-bar', to: '/report_cars', visible: localStorage.getItem("user_role") == 'admin'},
        ],
        visible: localStorage.getItem("user_role") == 'admin'
    }
    
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
