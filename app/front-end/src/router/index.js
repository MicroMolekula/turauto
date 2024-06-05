import { createRouter, createWebHistory } from 'vue-router'
import App from '@/App.vue'
import Registration from "@/views/client/Registration.vue";
import Main from "@/views/client/Main.vue";
import Auth from "@/views/client/Auth.vue";
import PageProfile from '@/views/client/PageProfile.vue';
import AuthAdmin from "@/views/manager/AuthAdmin.vue";
import MainAdmin from "@/views/manager/MainAdmin.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      component: Main
    },
    {
      path: "/reg",
      component: Registration
    },
    {
      path: "/auth",
      component: Auth
    }, 
    {
      path: "/profile",
      component: PageProfile
    },
    {
      path: "/mng/auth",
      component: AuthAdmin
    },
    {
      path: "/mng",
      component: MainAdmin
    }
  ]
})

export default router
