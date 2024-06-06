<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import axios from 'axios';
import { FilterMatchMode } from 'primevue/api';
import sourceUrl from '@/config';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const filters = ref({});

function getData() {
    axios.get(sourceUrl() + '/station_service/').then(function (response) {
        datas.value = response.data;
        datas.value = datas.value.map((el) => {
            el.phone = phoneToStr(el.phone);
            return el;
        });
    });
}

function newData(obj) {
    axios({
        method: 'post',
        url: sourceUrl() + '/station_service/new',
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
        url: sourceUrl() + '/station_service/' + id + '/edit',
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
        url: sourceUrl() + '/station_service/' + id + '/delete'
    })
        .then(function (response) {
            console.log(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
}


onMounted(() => {
    getData();
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
    if (data.value.address.trim()) {
        if (data.value.id) {
            console.log(data.value)
            data.value.date_begin = typeof data.value.date_begin === 'object' ? data.value.date_begin.toLocaleTimeString() : data.value.date_begin;
            data.value.date_end = typeof data.value.date_end === 'object' ? data.value.date_end.toLocaleTimeString() : data.value.date_end;
            datas.value[findIndexById(data.value.id)] = data.value;
            let response = Object.assign({}, data.value);
            response.phone = strPhoneToNumber(response.phone);
            updateData(response, response.id);
            toast.add({ severity: 'success', summary: 'Успешно', detail: 'Данные пункта обслуживания изменнены', life: 3000 });
        } else {
            data.value.date_begin = typeof data.value.date_begin === 'object' ? data.value.date_begin.toLocaleTimeString() : data.value.date_begin;
            data.value.date_end = typeof data.value.date_end === 'object' ? data.value.date_end.toLocaleTimeString() : data.value.date_end;
            datas.value.push(data.value);
            let response = Object.assign({}, data.value);
            response.phone = strPhoneToNumber(response.phone);
            newData(response);
            toast.add({ severity: 'success', summary: 'Успешно', detail: 'Пункт обслуживания добавлен', life: 3000 });
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
                            <h5 class="m-0">Пункты обслуживания</h5>
                        </div>
                    </template>
                    <Column field="address" header="Адрес" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Адрес</span>
                            {{ slotProps.data.address }}
                        </template>
                    </Column>
                    <Column field="phone" header="Номер телефона" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Номер телефона</span>
                            {{ "+7" + " " + slotProps.data.phone }}
                        </template>
                    </Column>
                    <Column field="date_begin" header="Время открытия" :sortable="true" >
                        <template #body="slotProps">
                            <span class="p-column-title">Время открытия</span>
                            {{ slotProps.data.date_begin }}
                        </template>
                    </Column>
                    <Column field="date_end" header="Время закрытия" :sortable="true" >
                        <template #body="slotProps">
                            <span class="p-column-title">Время закрытия</span>
                            {{ slotProps.data.date_end }}
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
                        <label for="surname">Адрес</label>
                        <InputText id="address" v-model.trim="data.address" required="true" autofocus :invalid="submitted && !data.address" />
                        <small class="p-invalid" v-if="submitted && !data.address">Фамилия обязательно.</small>
                    </div>
                    <div class="field">
                        <label for="phone">Номер телефона</label>
                        <InputMask id="phone" v-model="data.phone" mask="(999) 999 99-99" placeholder="(000) 000 00-00" required="true" autofocus :invalid="submitted && !data.phone" />
                        <small class="p-invalid" v-if="submitted && !data.phone">Вы не указали Номер телефона.</small>
                    </div>
                    <div class="field">
                        <label for="date_begin">Время открытия</label>
                        <Calendar v-model="data.date_begin" showIcon iconDisplay="input" timeOnly required="true" autofocus :invalid="submitted && !data.date_begin" ></Calendar>
                        <small class="p-invalid" v-if="submitted && !data.date_begin">Вы не указали Время открытия.</small>
                    </div>
                    <div class="field">
                        <label for="date_end">Время закрытия</label>
                        <Calendar v-model="data.date_end" showIcon iconDisplay="input" timeOnly required="true" autofocus :invalid="submitted && !data.date_end" ></Calendar>
                        <small class="p-invalid" v-if="submitted && !data.date_end">Вы не указали Время закрытия.</small>
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
                            >Вы уверены, что хотите удалить пункт обслуживания по адресу <b>{{ data.mark + " " + data.model }}</b
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
