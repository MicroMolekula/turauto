<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import axios from 'axios';
import { FilterMatchMode } from 'primevue/api';
import sourceUrl from '@/config';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const filters = ref({});

function editNumberDoc(el) {
    let str = el;
    let parts = [str.slice(0, 2), str.slice(2, 4), str.slice(4)];
    el = parts.join(' ');
    return el;
}

function getData() {
    axios.get(sourceUrl() + '/client/').then(function (response) {
        datas.value = response.data;
        datas.value = datas.value.map((el) => {
            el.passport_details = editNumberDoc(el.passport_details);
            el.num_drv_lic = editNumberDoc(el.num_drv_lic);
            el.category_drv_lic = el.category_drv_lic.split(' ');
            el.phone = phoneToStr(el.phone);
            return el;
        });
    });
}

function newData(obj) {
    axios({
        method: 'post',
        url: sourceUrl() + '/manager/new',
        data: obj
    })
        .then(function (response) {
            console.log(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
}

function updateData(obj, id) {
    axios({
        method: 'put',
        url: sourceUrl() + '/client/' + id + '/edit',
        data: obj
    })
        .then(function (response) {
            console.log(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
}

function deleteDataAx(id) {
    axios({
        method: 'delete',
        url: sourceUrl() + '/client/' + id + '/delete'
    })
        .then(function (response) {
            console.log(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
}

function getCategDrvLic() {
    axios.get(sourceUrl() + '/utils/category_drv_lic').then((response) => {
        categorys_drv_lic.value = response.data.data;
    });
}

onMounted(() => {
    getData();
    getCategDrvLic();
});

onBeforeMount(() => {
    initFilters();
});

let datas = ref();
let data = ref({});
const dataDialog = ref(false);
const deleteDataDialog = ref(false);
const submitted = ref(false);
let categorys_drv_lic = ref();
const adminRole = localStorage.getItem('user_role') == 'admin';

const openNew = () => {
    data.value = {};
    submitted.value = false;
    dataDialog.value = true;
};

const hideDialog = () => {
    dataDialog.value = false;
    submitted.value = false;
};

const editData = (editData) => {
    data.value = { ...editData };
    dataDialog.value = true;
};

const confirmDeleteData = (editData) => {
    data.value = editData;
    deleteDataDialog.value = true;
};

const deleteData = () => {
    let id = data.value.id;
    deleteDataDialog.value = false;
    datas.value.splice(findIndexById(id), 1);
    deleteDataAx(id);
    console.log(id);
    data.value = {};
    toast.add({ severity: 'success', summary: 'Успешно', detail: 'Менеджер удален', life: 3000 });
};

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};

const saveData = () => {
    submitted.value = true;
    if (data.value.surname && data.value.name.trim()) {
        if (data.value.id) {
            console.log(data.value);
            data.value.date_drv_lic = typeof data.value.date_drv_lic === 'object' ? toISO(data.value.date_drv_lic.toLocaleDateString()) : data.value.date_drv_lic;
            console.log(data.value);
            datas.value[findIndexById(data.value.id)] = data.value;
            console.log(data.value);
            let response = Object.assign({}, data.value);
            response.passport_details = response.passport_details.replace(/\s/g, '');
            response.num_drv_lic = response.num_drv_lic.replace(/\s/g, '');
            response.category_drv_lic = response.category_drv_lic.join(' ');
            response.phone = strPhoneToNumber(response.phone);
            updateData(response, response.id);
            toast.add({ severity: 'success', summary: 'Успешно', detail: 'Данные клиента измененны', life: 3000 });
        } else {
            datas.value.push(data.value);
            let response = Object.assign({}, data.value);
            response.passport_details = response.passport_details.replace(/\s/g, '');
            response.num_drv_lic = response.num_drv_lic.replace(/\s/g, '');
            response.category_drv_lic = response.category_drv_lic.join(' ');
            newData(response);
            toast.add({ severity: 'success', summary: 'Успешно', detail: 'Клиент добавлен', life: 3000 });
        }
        dataDialog.value = false;
        data.value = {};
    }
};

const findIndexById = (id) => {
    let index = -1;
    for (let i = 0; i < datas.value.length; i++) {
        if (datas.value[i].id === id) {
            index = i;
            break;
        }
    }
    return index;
};

const search = (event) => {
    setTimeout(() => {
        if (!event.query.trim().length) {
            filtered_station_service.value = [...stations_service.value];
        } else {
            filtered_station_service.value = stations_service.value.filter((stn) => {
                return stn.toLowerCase().startsWith(event.query.toLowerCase());
            });
        }
    }, 100);
};

const categoryToStr = (el) => {
    return el.join(', ');
};

const toISO = (el) => {
    return `${el.split('.')[2]}-${el.split('.')[1]}-${el.split('.')[0]}`;
};

const phoneToStr = (el) => {
    return `(${el.slice(0, 3)}) ${el.slice(3, 6)} ${el.slice(6, 8)}-${el.slice(8)}`;
};

const strPhoneToNumber = (el) => {
    let cleanedNumber = el.replace(/\D/g, '');
    let number = parseInt(cleanedNumber, 10);
    return number.toString();
};
</script>

<template>
    <div v-if="datas == undefined" style="text-align: center">
        <i class="pi pi-spin pi-spinner" style="font-size: 4rem; margin-top: 16rem"></i>
    </div>
    <div class="grid" v-if="datas !== undefined">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <div class="my-2">
                            <Button v-if="false" label="Новый" icon="pi pi-plus" class="mr-2" severity="success" @click="openNew" />
                        </div>
                    </template>
                </Toolbar>
                <!--Данные-->
                <DataTable :value="datas" :rows="10" tableStyle="min-width: 50rem">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Клиенты</h5>
                        </div>
                    </template>
                    <Column field="surname" header="Фамилия" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Фамилия</span>
                            {{ slotProps.data.surname }}
                        </template>
                    </Column>
                    <Column field="name" header="Имя" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Имя</span>
                            {{ slotProps.data.name }}
                        </template>
                    </Column>
                    <Column field="middlename" header="Отчество" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Отчество</span>
                            {{ slotProps.data.middlename }}
                        </template>
                    </Column>
                    <Column field="email" header="Email" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Email</span>
                            {{ slotProps.data.email }}
                        </template>
                    </Column>
                    <Column field="phone" header="Номер телефона" :sortable="true" headerStyle="min-width:12rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Номер телефона</span>
                            {{'+7'+ " " + slotProps.data.phone }}
                        </template>
                    </Column>
                    <Column field="passport_details" header="Паспортные данные" :sortable="true" headerStyle="min-width:6rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Паспортные данные</span>
                            {{ slotProps.data.passport_details }}
                        </template>
                    </Column>
                    <Column field="num_drv_lic" header="Номер ВУ" :sortable="true" headerStyle="min-width:12rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Номер ВУ</span>
                            {{ slotProps.data.num_drv_lic }}
                        </template>
                    </Column>
                    <Column field="category_drv_lic" header="Категории ВУ" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Категории ВУ</span>
                            {{ categoryToStr(slotProps.data.category_drv_lic) }}
                        </template>
                    </Column>
                    <Column field="date_drv_lic" header="Дата выдачи ВУ" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Дата выдачи ВУ</span>
                            {{ slotProps.data.date_drv_lic }}
                        </template>
                    </Column>
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button v-if="adminRole" icon="pi pi-pencil" class="mr-2" severity="success" rounded text @click="editData(slotProps.data)" />
                            <Button v-if="adminRole" icon="pi pi-trash" class="mt-2" severity="danger" rounded text @click="confirmDeleteData(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>
                <!---->

                <!--Диалог изменений-->
                <Dialog v-model:visible="dataDialog" :style="{ width: '450px' }" header="Информация о клиенте" :modal="true" class="p-fluid">
                    <div class="field">
                        <label for="surname">Фамилия</label>
                        <InputText id="surname" v-model.trim="data.surname" required="true" autofocus :invalid="submitted && !data.surname" />
                        <small class="p-invalid" v-if="submitted && !data.surname">Фамилия обязательно.</small>
                    </div>
                    <div class="field">
                        <label for="name">Имя</label>
                        <InputText id="name" v-model.trim="data.name" required="true" autofocus :invalid="submitted && !data.name" />
                        <small class="p-invalid" v-if="submitted && !data.name">Имя обязательно.</small>
                    </div>
                    <div class="field">
                        <label for="middlename">Отчество</label>
                        <InputText id="middlename" v-model.trim="data.middlename" required="true" autofocus :invalid="submitted && !data.middlename" />
                        <small class="p-invalid" v-if="submitted && !data.middlename">Отчество обязательно.</small>
                    </div>
                    <div class="field" v-if="!data.id">
                        <label for="email">Email</label>
                        <InputText id="email" v-model.trim="data.email" required="true" autofocus :invalid="submitted && !data.email" />
                        <small class="p-invalid" v-if="submitted && !data.email">Вы не указали Email.</small>
                    </div>
                    <div class="field">
                        <label for="phone">Номер телефона</label>
                        <InputMask id="phone" v-model="data.phone" mask="(999) 999 99-99" placeholder="(000) 000 00-00" required="true" autofocus :invalid="submitted && !data.phone" />
                        <small class="p-invalid" v-if="submitted && !data.phone">Вы не указали Номер телефона.</small>
                    </div>
                    <div class="field">
                        <label for="passport_details">Паспортные данные</label>
                        <InputMask id="passport_details" v-model="data.passport_details" mask="99 99 999999" placeholder="00 00 000000" required="true" autofocus :invalid="submitted && !data.passport_details" />
                        <small class="p-invalid" v-if="submitted && !data.passport_details">Вы не указали Паспортные данные.</small>
                    </div>
                    <div class="field">
                        <label for="num_drv_lic">Номер ВУ</label>
                        <InputMask id="num_drv_lic" v-model="data.num_drv_lic" mask="99 99 999999" placeholder="00 00 000000" required="true" autofocus :invalid="submitted && !data.num_drv_lic" />
                        <small class="p-invalid" v-if="submitted && !data.num_drv_lic">Вы не указали Номер ВУ.</small>
                    </div>
                    <div class="field">
                        <label for="category_drv_lic">Категории ВУ</label>
                        <MultiSelect v-model="data.category_drv_lic" :options="categorys_drv_lic" placeholder="Категории ВУ" :maxSelectedLabels="3" class="w-full md:w-20rem" />
                        <small class="p-invalid" v-if="submitted && !data.category_drv_lic">Вы не указали Категории ВУ.</small>
                    </div>
                    <div class="field">
                        <label for="date_drv_lic">Дата выдачи ВУ</label>
                        <!-- <InputText id="date_drv_lic" v-model.trim="data.date_drv_lic" required="true" autofocus :invalid="submitted && !data.date_drv_lic" /> -->
                        <Calendar v-model.trim="data.date_drv_lic" :dateFormat="'yy-mm-dd'" showButtonBar showIcon required="true" autofocus :invalid="submitted && !data.date_drv_lic" />
                        <small class="p-invalid" v-if="submitted && !data.date_drv_lic">Вы не указали Дату выдачи ВУ.</small>
                    </div>

                    <template #footer>
                        <Button label="Отмена" icon="pi pi-times" text="" @click="hideDialog" />
                        <Button label="Сохранить" icon="pi pi-check" text="" @click="saveData" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteDataDialog" :style="{ width: '450px' }" header="Подтверждение" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="data"
                            >Вы уверены, что хотите удалить клиента <b>{{ data.surname }}</b
                            >?</span
                        >
                    </div>
                    <template #footer>
                        <Button label="Нет" icon="pi pi-times" text @click="deleteDataDialog = false" />
                        <Button label="Да" icon="pi pi-check" text @click="deleteData" />
                    </template>
                    <template> </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>
