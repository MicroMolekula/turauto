<script setup>
import { RouterLink, RouterView, useRouter } from 'vue-router';
import Auth from './Auth.vue';

</script>

<template>
    <div class="surface-0 flex justify-content-center" style="width: 100%">
        <div id="home" class="landing-wrapper overflow-hidden" style="width: 100%">
            <div class="py-4 px-4 mx-0 md:mx-6 lg:mx-8 lg:px-8 flex align-items-center justify-content-between relative lg:static mb-3">
                <a class="flex align-items-center" href="#"> <i class="pi pi-car mr-2 text-900 font-medium line-height-3 mr-3" style="font-size: 3rem"></i><span class="text-900 font-medium text-2xl line-height-3 mr-8">TurAuto</span> </a>
                <a class="cursor-pointer block lg:hidden text-700 p-ripple" v-ripple v-styleclass="{ selector: '@next', enterClass: 'hidden', leaveToClass: 'hidden', hideOnOutsideClick: true }">
                    <i class="pi pi-bars text-4xl"></i>
                </a>
                <div class="align-items-center surface-0 flex-grow-1 justify-content-between hidden lg:flex absolute lg:static w-full left-0 px-6 lg:px-0 z-2" style="top: 120px">
                    <ul class="list-none p-0 m-0 flex lg:align-items-center select-none flex-column lg:flex-row cursor-pointer"></ul>
                    <div class="flex justify-content-between lg:block border-top-1 lg:border-top-none surface-border py-3 lg:py-0 mt-3 lg:mt-0">
                        <RouterLink to="/auth"><Button label="Войти" class="p-button-text p-button-rounded border-none font-light line-height-2 text-blue-500"></Button></RouterLink>
                        <RouterLink to="/reg"><Button label="Зарегистироваться" class="p-button-rounded border-none ml-5 font-light text-white line-height-2 bg-blue-500"></Button></RouterLink>
                    </div>
                </div>
            </div>

            <div
                id="hero"
                class="flex flex-column pt-4 px-4 lg:px-8 overflow-hidden"
                style="background: linear-gradient(0deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.2)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, rgb(238, 239, 175) 0%, rgb(195, 227, 250) 100%); clip-path: ellipse(150% 87% at 93% 13%); margin-bottom: 10rem;"
            >
                <div class="mx-4 md:mx-8 mt-0 md:mt-4">
                    <h1 class="text-6xl font-bold text-gray-900 line-height-2"><span class="font-light block">Аренда автомобилей</span>в Липецке</h1>
                    <p class="font-normal text-2xl line-height-3 md:mt-3 text-gray-700">Крути мир на колесах – арендуй, езжай, наслаждайся!</p>
                </div>
                <div class="flex justify-content-center md:justify-content-end">
                    <img src="/demo/images/main_car.png" alt="Hero Image" style="width: 60rem" />
                </div>
            </div>

            <div class="layout-footer">
                <span class="font-medium ml-2">© 2024 TurAuto</span>
            </div>
        </div>
    </div>
</template>

<!-- <style scoped>
#hero {
    background: linear-gradient(0deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.2)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, #eeefaf 0%, #c3e3fa 100%);
    height: 700px;
    overflow: hidden;
}

@media screen and (min-width: 768px) {
    #hero {
        -webkit-clip-path: ellipse(150% 87% at 93% 13%);
        clip-path: ellipse(150% 87% at 93% 13%);
        height: 530px;
    }
}

@media screen and (min-width: 1300px) {
    #hero > img {
        position: absolute;
    }

    #hero > div > p {
        max-width: 450px;
    }
}

@media screen and (max-width: 1300px) {
    #hero {
        height: 600px;
    }

    #hero > img {
        position: static;
        transform: scale(1);
        margin-left: auto;
    }

    #hero > div {
        width: 100%;
    }

    #hero > div > p {
        width: 100%;
        max-width: 100%;
    }
}
</style> -->
