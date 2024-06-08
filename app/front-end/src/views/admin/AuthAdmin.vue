<script setup>
import { ref, computed } from 'vue';
import sourceUrl from '@/config';
import axios from 'axios';


const email = ref('');
const password = ref('');


const logIn = () => {
    axios({
        method: 'post',
        url: sourceUrl() + '/manager/auth',
        data: {email: email.value, password: password.value}
    }).then((response) => {
        console.log(response.data);
    });
    axios.get(sourceUrl() + '/test2').then((response) => {console.log(response.data)});
};

</script>

<template>
    <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <i class="pi pi-car mr-2" style="font-size:4rem; margin-bottom: 2rem;"></i>
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <div class="text-900 text-3xl font-medium mb-3">Вход для менеджеров</div>
                        <span class="text-600 font-medium">Войдите, чтобы продолжить</span>
                    </div>
                    <div>
                        <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
                        <InputText id="email1" type="text" placeholder="Email адрес" class="w-full md:w-30rem mb-5" style="padding: 1rem" v-model="email" />

                        <label for="password1" class="block text-900 font-medium text-xl mb-2">Пароль</label>
                        <Password id="password1" v-model="password" placeholder="Пароль" :feedback="false" :toggleMask="true" class="w-full mb-6" inputClass="w-full" :inputStyle="{ padding: '1rem' }"></Password>

                        <Button label="Войти" class="w-full p-3 text-xl" @click="logIn"></Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.pi-eye {
    transform: scale(1.6);
    margin-right: 1rem;
}

.pi-eye-slash {
    transform: scale(1.6);
    margin-right: 1rem;
}
</style>
