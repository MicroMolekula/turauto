import { createRouter, createWebHistory } from 'vue-router';
import AppLayout from '@/layout/AppLayout.vue';
import Dashboard from '../views/admin/Dashboard.vue';
import Booking from '@/views/admin/Booking.vue';
import Cars from '@/views/admin/Cars.vue';
import CarClass from '@/views/admin/CarClass.vue';
import Clients from '@/views/admin/Clients.vue';
import StationService from '@/views/admin/StationService.vue';
import AddService from '@/views/admin/AddService.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/mng',
            component: AppLayout,
            children: [
                {
                    path: '/mng/manager',
                    name: 'dashboard',
                    component: Dashboard,
                },
                {
                    path: '/mng/booking',
                    name: 'booking',
                    component: Booking,
                },
                {
                    path: '/mng/client',
                    name: 'client',
                    component: Clients,
                },
                {
                    path: '/mng/car_class',
                    name: 'car_class',
                    component: CarClass,
                },
                {
                    path: '/mng/car',
                    name: 'car',
                    component: Cars,
                },
                {
                    path: '/mng/station_service',
                    name: 'station_service',
                    component: StationService,
                },
                {
                    path: '/mng/add_service',
                    name: 'add_service',
                    component: AddService,
                },
            ]
        },
        {
            path: '/landing',
            name: 'landing',
            component: () => import('@/views/pages/Landing.vue')
        },
        {
            path: '/pages/notfound',
            name: 'notfound',
            component: () => import('@/views/pages/NotFound.vue')
        },

        {
            path: '/auth/login',
            name: 'login',
            component: () => import('@/views/pages/auth/Login.vue')
        },
        {
            path: '/auth/access',
            name: 'accessDenied',
            component: () => import('@/views/pages/auth/Access.vue')
        },
        {
            path: '/auth/error',
            name: 'error',
            component: () => import('@/views/pages/auth/Error.vue')
        }
    ]
});

export default router;
