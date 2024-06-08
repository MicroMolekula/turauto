import { createRouter, createWebHistory } from 'vue-router';
import AppLayout from '@/layout/AppLayout.vue';
import Dashboard from '../views/admin/Dashboard.vue';
import Booking from '@/views/admin/Booking.vue';
import Cars from '@/views/admin/Cars.vue';
import CarClass from '@/views/admin/CarClass.vue';
import Clients from '@/views/admin/Clients.vue';
import StationService from '@/views/admin/StationService.vue';
import AddService from '@/views/admin/AddService.vue';
import Auth from '@/views/Auth.vue';
import Main from '@/views/Main.vue';
import Registration from '@/views/Registration.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/home',
            component: AppLayout,
            children: [
                {
                    path: '/manager',
                    name: 'dashboard',
                    component: Dashboard,
                },
                {
                    path: '/booking',
                    name: 'booking',
                    component: Booking,
                },
                {
                    path: '/client',
                    name: 'client',
                    component: Clients,
                },
                {
                    path: '/car_class',
                    name: 'car_class',
                    component: CarClass,
                },
                {
                    path: '/car',
                    name: 'car',
                    component: Cars,
                },
                {
                    path: '/station_service',
                    name: 'station_service',
                    component: StationService,
                },
                {
                    path: '/add_service',
                    name: 'add_service',
                    component: AddService,
                },
            ]
        },
        {
            path: '/auth',
            name: 'auth',
            component: Auth
        },
        {
            path: '/reg',
            name: 'reg',
            component: Registration
        },
        {
            path: '/',
            name: 'main',
            component: Main
        },
        {
            path: '/pages/notfound',
            name: 'notfound',
            component: () => import('@/views/pages/NotFound.vue')
        },
    ]
});

export default router;
