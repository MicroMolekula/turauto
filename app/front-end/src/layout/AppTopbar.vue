<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useLayout } from '@/layout/composables/layout';
import { useRouter } from 'vue-router';
import axios from 'axios';
import sourceUrl from '@/config';

const router = useRouter();

if(localStorage.getItem('user_id') == '' || localStorage.getItem('user_id') == undefined) {
    router.push('/');
}


onMounted(() => {
    getUserData();
});

const { layoutConfig, onMenuToggle } = useLayout();

const outsideClickListener = ref(null);
const topbarMenuActive = ref(false);
const op = ref();
const user_data = ref({
    surname: '',
    name: '',
    middlename: ''
});

function getUserData(){
    if(localStorage.getItem('user_role') == 'client'){
        axios.get(sourceUrl() + '/client/' + localStorage.getItem('user_id').toString() + '/show')
        .then((response) => {
            user_data.value.surname = response.data.surname;
            user_data.value.name = response.data.name;
            user_data.value.middlename = response.data.middlename;
        });
    }
    if(localStorage.getItem('user_role') == 'admin' || localStorage.getItem('user_role') == 'manager'){
        axios.get(sourceUrl() + '/manager/' + localStorage.getItem('user_id').toString() + '/show')
        .then((response) => {
            user_data.value.surname = response.data.surname;
            user_data.value.name = response.data.name;
            user_data.value.middlename = response.data.middlename;
        });
    }
}


onMounted(() => {
    bindOutsideClickListener();
});

onBeforeUnmount(() => {
    unbindOutsideClickListener();
});


const onTopBarMenuButton = () => {
    topbarMenuActive.value = !topbarMenuActive.value;
};
const onSettingsClick = () => {
    topbarMenuActive.value = false;
    router.push('/documentation');
};
const topbarMenuClasses = computed(() => {
    return {
        'layout-topbar-menu-mobile-active': topbarMenuActive.value
    };
});

const bindOutsideClickListener = () => {
    if (!outsideClickListener.value) {
        outsideClickListener.value = (event) => {
            if (isOutsideClicked(event)) {
                topbarMenuActive.value = false;
            }
        };
        document.addEventListener('click', outsideClickListener.value);
    }
};
const unbindOutsideClickListener = () => {
    if (outsideClickListener.value) {
        document.removeEventListener('click', outsideClickListener);
        outsideClickListener.value = null;
    }
};
const isOutsideClicked = (event) => {
    if (!topbarMenuActive.value) return;

    const sidebarEl = document.querySelector('.layout-topbar-menu');
    const topbarEl = document.querySelector('.layout-topbar-menu-button');

    return !(sidebarEl.isSameNode(event.target) || sidebarEl.contains(event.target) || topbarEl.isSameNode(event.target) || topbarEl.contains(event.target));
};

const toggle = (event) => {
    op.value.toggle(event);
};

const logout = () => {
    localStorage.setItem('user_role', '');
    localStorage.setItem('user_id', '');
    router.push('/'); 
};
</script>

<template>
    <div class="layout-topbar">
        <router-link to="/" class="layout-topbar-logo">
            <i class="pi pi-car mr-2" style="font-size:1.5rem"></i>
            <span>TurAuto</span>
        </router-link>

        <button class="p-link layout-menu-button layout-topbar-button" @click="onMenuToggle()">
            <i class="pi pi-bars"></i>
        </button>

        <button class="p-link layout-topbar-menu-button layout-topbar-button" @click="onTopBarMenuButton()">
            <i class="pi pi-ellipsis-v"></i>
        </button>

        <div class="layout-topbar-menu" :class="topbarMenuClasses">
            <div class="mr-3 flex" style="align-items: center; font-size: 1.3rem;">{{ user_data.surname + " " + user_data.name }}</div>
            <Button icon="pi pi-user" @click="toggle" severity="secondary"></Button>
        </div>

        <OverlayPanel ref="op">
                <Button label="Выйти из аккаунта" severity="danger" @click="logout"></Button>
        </OverlayPanel>
    </div>
</template>

<style lang="scss" scoped></style>
