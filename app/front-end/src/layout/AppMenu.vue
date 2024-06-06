<script setup>
import { ref } from 'vue';

import AppMenuItem from './AppMenuItem.vue';

const model = ref([
    {
        label: 'Главная',
        items: [
            { label: 'Заказы', icon: 'pi pi-fw pi-book', to: '/mng/booking' },
            { label: 'Mенеджеры', icon: 'pi pi-fw pi-user', to: '/mng/manager' },
            { label: 'Пункты обслуживания', icon: 'pi pi-fw pi-building', to: '/mng/station_service' },
            { label: 'Клиенты', icon: 'pi pi-fw pi-user', to: '/mng/client' },
            { label: 'Автомобили', icon: 'pi pi-fw pi-car', to: '/mng/car' },
            { label: 'Классы Автомобилей', icon: 'pi pi-fw pi-sitemap', to: '/mng/car_class' },
            { label: 'Дополнительные опции', icon: 'pi pi-fw pi-plus', to: '/mng/add_service' },
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
