<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import axios from 'axios';
import { FilterMatchMode } from 'primevue/api';
import sourceUrl from '@/config';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const filters = ref({});

function getData() {
    axios.get(sourceUrl() + '/manager/').then(function (response) {
        datas.value = response.data;
        datas.value = datas.value.map((el) => {
            let str = el.passport_details;
            let parts = [
                str.slice(0, 2),
                str.slice(2, 4), 
                str.slice(4) 
            ];
            el.passport_details = parts.join(' ');
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
        url: sourceUrl() + '/manager/' + id + '/edit',
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
        url: sourceUrl() + '/manager/' + id + '/delete'
    })
        .then(function (response) {
            console.log(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
}

function getStnServ() {
    axios.get(sourceUrl() + '/station_service').then(function (response) {
        stations_service.value = response.data;
        let tmp = [];
        stations_service.value.forEach((element) => {
            tmp.push(element.address);
        });
        stations_service.value = tmp;
    });
}

onMounted(() => {
    getData();
    getStnServ();
});

onBeforeMount(() => {
    initFilters();
});

let datas = ref();
let data = ref({});
const dataDialog = ref(false);
const deleteDataDialog = ref(false);
const submitted = ref(false);
const stations_service = ref();
const filtered_station_service = ref();

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
            datas.value[findIndexById(data.value.id)] = data.value;
            let response = Object.assign({}, data.value);
            response.passport_details = response.passport_details.replace(/\s/g, '');
            updateData(response, response.id);
            toast.add({ severity: 'success', summary: 'Успешно', detail: 'Данные менеджера измененны', life: 3000 });
        } else {
            datas.value.push(data.value);
            let response = Object.assign({}, data.value);
            response.passport_details = response.passport_details.replace(/\s/g, '');
            newData(response);
            toast.add({ severity: 'success', summary: 'Успешно', detail: 'Менеджер добавлен', life: 3000 });
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
</script>

<template>
    <div v-if="datas == undefined" style="text-align: center;">
        <i class="pi pi-spin pi-spinner" style="font-size: 4rem; margin-top: 16rem;"></i>
    </div>
    <div class="grid" v-if="datas !== undefined">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <div class="my-2">
                            <Button label="Новый" icon="pi pi-plus" class="mr-2" severity="success" @click="openNew" />
                        </div>
                    </template>

                    <template v-slot:end>
                        <Button label="Excel" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>
                <!--Данные-->
                <DataTable :value="datas" :rows="10" tableStyle="min-width: 50rem">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Менеджеры</h5>
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
                    <Column field="middlename" header="Отчество" :sortable="true" >
                        <template #body="slotProps">
                            <span class="p-column-title">Отчество</span>
                            {{ slotProps.data.middlename }}
                        </template>
                    </Column>
                    <Column field="email" header="Email" :sortable="true" >
                        <template #body="slotProps">
                            <span class="p-column-title">Email</span>
                            {{ slotProps.data.email }}
                        </template>
                    </Column>
                    <Column field="station_service" header="Пункт обслуживания" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Пункт обслуживания</span>
                            {{ slotProps.data.station_service }}
                        </template>
                    </Column>
                    <Column field="passport_details" header="Паспортные данные" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Паспортные данные</span>
                            {{ slotProps.data.passport_details }}
                        </template>
                    </Column>
                    <Column field="role" header="Должность" :sortable="true" >
                        <template #body="slotProps">
                            <span class="p-column-title">Должность</span>
                            {{ slotProps.data.role }}
                        </template>
                    </Column>
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="mr-2" severity="success" rounded text @click="editData(slotProps.data)" />
                            <Button icon="pi pi-trash" class="mt-2" severity="danger" rounded text @click="confirmDeleteData(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>
                <!---->

                <!--Диалог изменений-->
                <Dialog v-model:visible="dataDialog" :style="{ width: '450px' }" header="Информация о менеджере" :modal="true" class="p-fluid">
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
                        <label for="station_service">Пункт обслуживания</label>
                        <AutoComplete v-model="data.station_service" dropdown :suggestions="filtered_station_service" @complete="search" />

                        <small class="p-invalid" v-if="submitted && !data.station_service">Вы не выбрали пункт обслуживания.</small>
                    </div>
                    <div class="field">
                        <label for="passport_details">Паспортные данные</label>
                        <InputMask id="passport_details" v-model="data.passport_details" mask="99 99 999999" placeholder="00 00 000000" required="true" autofocus :invalid="submitted && !data.passport_details" />
                        <small class="p-invalid" v-if="submitted && !data.passport_details">Вы не указали Паспортные данные.</small>
                    </div>
                    <div class="field" v-if="!data.id">
                        <label for="password">Пароль</label>
                        <Password v-model="data.password" toggleMask required="true" autofocus :invalid="submitted && !data.password" :feedback="false"/>
                        <small class="p-invalid" v-if="submitted && !data.password">Вы не указали пароль.</small>
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
                            >Вы уверены, что хотите удалить менеджера <b>{{ data.surname }}</b
                            >?</span
                        >
                    </div>
                    <template #footer>
                        <Button label="Нет" icon="pi pi-times" text @click="deleteDataDialog = false" />
                        <Button label="Да" icon="pi pi-check" text @click="deleteData" />
                    </template>
                    <template>

                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>
