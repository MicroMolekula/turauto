<script setup>
import { useRouter, useRoute } from 'vue-router';
import { ref, onMounted } from 'vue';
import sourceUrl from '@/config';
import axios from 'axios';

function getCategDrvLic() {
    axios.get(sourceUrl() + '/utils/category_drv_lic').then((response) => {
        categorys_drv_lic.value = response.data.data;
    });
}

function newData(obj) {
    axios({
        method: 'post',
        url: sourceUrl() + '/client/new',
        data: obj
    })
        .then(function (response) {
            if(response.data.status == 'ok'){
                localStorage.setItem('user_role', response.data.role);
                localStorage.setItem('user_id', response.data.id);
                router.push('/home');
            } else {
                error_email.value = response.data.message;
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}

onMounted(() => {
    getCategDrvLic();
});

const data = ref({
    surname: '',
    name: '',
    middlename: '',
    email: '',
    passport_details: '',
    num_drv_lic: '',
    category_drv_lic: '',
    date_drv_lic: '',
    phone: '',
    password: ''
});
let categorys_drv_lic = ref();

const router = useRouter();
const error_email = ref('');

const signIn = () => {
    data.value.date_drv_lic = typeof data.value.date_drv_lic === 'object' ? toISO(data.value.date_drv_lic.toLocaleDateString()) : data.value.date_drv_lic;
    let response = Object.assign({}, data.value);
    response.passport_details = response.passport_details.replace(/\s/g, '');
    response.num_drv_lic = response.num_drv_lic.replace(/\s/g, '');
    response.category_drv_lic = response.category_drv_lic.join(' ');
    response.phone = strPhoneToNumber(response.phone);
    newData(response);
};

const toISO = (el) => {
    return `${el.split('.')[2]}-${el.split('.')[1]}-${el.split('.')[0]}`;
};

const strPhoneToNumber = (el) => {
    let cleanedNumber = el.replace(/\D/g, '');
    let number = parseInt(cleanedNumber, 10);
    return number.toString();
};
</script>

<template>
    <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <div class="text-900 text-3xl font-medium mb-3">Регистрация</div>
                        <InlineMessage severity="error" v-if="error_email !== ''">{{ error_email }}</InlineMessage>
                    </div>
                    <div>
                        <label for="surname" class="block text-900 text-xl font-medium mb-2">Фамилия</label>
                        <InputText id="surname" type="text" placeholder="Фамилия" class="w-full mb-3" style="padding: 0.6rem" v-model="data.surname" />

                        <label for="name" class="block text-900 text-xl font-medium mb-2">Имя</label>
                        <InputText id="name" type="text" placeholder="Имя" class="w-full mb-3" style="padding: 0.6rem" v-model="data.name" />

                        <label for="middlename" class="block text-900 text-xl font-medium mb-2">Отчество</label>
                        <InputText id="middlename" type="text" placeholder="Отчество" class="w-full mb-3" style="padding: 0.6rem" v-model="data.middlename" />

                        <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
                        <InputText id="email1" type="text" placeholder="Email адрес" class="w-full mb-3" style="padding: 0.6rem" v-model="data.email" />

                        <label for="passport_details" class="block text-900 text-xl font-medium mb-2">Паспортные данные</label>
                        <InputMask id="passport_details" v-model="data.passport_details" mask="99 99 999999" placeholder="00 00 000000" required="true" class="w-full mb-3" style="padding: 0.6rem" />

                        <label for="email1" class="block text-900 text-xl font-medium mb-2">Данные Водительского удостоверения</label>
                        <InputGroup class="w-full mb-3">
                            <Calendar v-model.trim="data.date_drv_lic" :dateFormat="'yy-mm-dd'" showButtonBar required="true" placeholder="Дата получения" />
                            <MultiSelect v-model="data.category_drv_lic" :options="categorys_drv_lic" placeholder="Категории ВУ" :maxSelectedLabels="3" style="width: 3rem" />
                            <InputMask id="num_drv_lic" v-model="data.num_drv_lic" mask="99 99 999999" placeholder="Номер ВУ" required="true" />
                        </InputGroup>

                        <label for="phone" class="block text-900 text-xl font-medium mb-2">Номер телефона</label>
                        <InputMask id="phone" v-model="data.phone" mask="(999) 999 99-99" placeholder="(000) 000 00-00" required="true" class="w-full mb-3" style="padding: 0.6rem" />

                        <label for="password1" class="block text-900 font-medium text-xl mb-2">Пароль</label>
                        <Password id="password1" v-model="data.password" placeholder="Пароль" :feedback="false" :toggleMask="true" class="w-full mb-6" inputClass="w-full" :inputStyle="{ padding: '0.6rem' }"></Password>

                        <Button label="Зарегистрироваться" class="w-full p-3 text-xl" @click="signIn"></Button>
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
