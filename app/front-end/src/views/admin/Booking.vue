<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import axios from 'axios';
import { FilterMatchMode } from 'primevue/api';
import sourceUrl from '@/config';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const filters = ref({});

function getData() {
    axios.get(sourceUrl() + '/booking/').then(function (response) {
        datas.value = response.data;
    });
}

function newData(obj) {
    axios({
        method: 'post',
        url: sourceUrl() + '/booking/new',
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
        url: sourceUrl() + '/booking/' + id + '/edit',
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
        url: sourceUrl() + '/booking/' + id + '/delete'
    })
        .then(function (response) {
            console.log(response.data);
            dataDialog.value = false;
        })
        .catch(function (error) {
            console.log(error);
        });
}

function getClients() {
    axios.get(sourceUrl() + '/client').then((response) => {
        let tmp = response.data;
        tmp.forEach((el) => {
            clients.value.push({ name: `${el.surname} ${el.name[0]}. ${el.middlename[0]}.`, code: el.id });
        });
    });
}

function getManagers() {
    axios.get(sourceUrl() + '/manager').then((response) => {
        let tmp = response.data;
        tmp.forEach((el) => {
            if (el.role !== 'Старший менеджер') {
                managers.value.push({ name: `${el.surname} ${el.name[0]}. ${el.middlename[0]}.`, code: el.id });
            }
        });
    });
}

function getCars() {
    axios.get(sourceUrl() + '/car').then((response) => {
        let tmp = response.data;
        tmp.forEach((el) => {
            if (el.status == 2) {
                cars.value.push({ name: `${el.mark} ${el.model}, ${el.date.split('-')[0]}`, code: el.id });
            }
        });
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

function getBookings(id){
    axios.get(sourceUrl() + '/manager/' + id + '/bookings')
    .then((response) => {
        datas.value = response.data
    });
}

onMounted(() => {
    if(localStorage.getItem('user_role') == 'admin'){
        getData();
    } else if(localStorage.getItem('user_role') == 'manager') {
        getBookings(localStorage.getItem('user_id'));
    }
    getClients();
    getManagers();
    getCars();
    getStnServ();
});

onBeforeMount(() => {
    initFilters();
});

let datas = ref();
let data = ref({});
const dataDialog = ref(false);
const editDataDialog = ref(false);
const deleteDataDialog = ref(false);
const submitted = ref(false);
const stations_service = ref();
const filtered_station_service = ref();
const filtered_clients = ref();
const filtered_managers = ref();
const filtered_cars = ref();
const adminRole = localStorage.getItem('user_role') == 'admin';
let status = ref([
    { name: 'Отменен', code: 0 },
    { name: 'Выполняется', code: 1 },
    { name: 'Выполнен', code: 2 }
]);
let clients = ref([]);
let managers = ref([]);
let cars = ref([]);

const openNew = () => {
    data.value = {};
    submitted.value = false;
    dataDialog.value = true;
};

const hideDialog = () => {
    dataDialog.value = false;
    submitted.value = false;
};

const hideEditDialog = () => {
    editDataDialog.value = false;
    submitted.value = false;
};

const editData = (editData) => {
    data.value = {
        booking_id: editData.id,
        status: editData.status
    };
    editDataDialog.value = true;
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
    toast.add({ severity: 'success', summary: 'Успешно', detail: 'Заказ удален', life: 3000 });
};

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};

const saveEditData = () => {
    submitted.value = true;
    updateData(data.value, data.value.booking_id);
    editDataDialog.value = false;
    data.value = {};
    console.log(data.value);
};

const saveData = () => {
    data.value.car = data.value.car.code;
    data.value.client = data.value.client.code;
    newData(data.value);
    dataDialog.value = false;
    data.value = {};
    console.log(data.value);
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

const searchStn = (event) => {
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

const searchClient = (event) => {
    setTimeout(() => {
        if (!event.query.trim().length) {
            filtered_clients.value = [...clients.value];
        } else {
            filtered_clients.value = clients.value.filter((clt) => {
                return clt.name.toLowerCase().startsWith(event.query.toLowerCase());
            });
        }
    }, 100);
};

const searchManager = (event) => {
    setTimeout(() => {
        if (!event.query.trim().length) {
            filtered_managers.value = [...managers.value];
        } else {
            filtered_managers.value = managers.value.filter((mng) => {
                return mng.name.toLowerCase().startsWith(event.query.toLowerCase());
            });
        }
    }, 100);
};

const searchCar = (event) => {
    setTimeout(() => {
        if (!event.query.trim().length) {
            filtered_cars.value = [...cars.value];
        } else {
            filtered_cars.value = cars.value.filter((car) => {
                return car.name.toLowerCase().startsWith(event.query.toLowerCase());
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
    <div v-if="datas == undefined" style="text-align: center">
        <i class="pi pi-spin pi-spinner" style="font-size: 4rem; margin-top: 16rem"></i>
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
                </Toolbar>
                <!--Данные-->
                <DataTable :value="datas" :rows="10" tableStyle="min-width: 50rem">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Заказы</h5>
                        </div>
                    </template>
                    <Column field="client" header="Клиент" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Клиент</span>
                            <p v-tooltip.bottom="slotProps.data.client.email">{{ `${slotProps.data.client.surname} ${slotProps.data.client.name[0]}. ${slotProps.data.client.middlename[0]}.` }}</p>
                        </template>
                    </Column>
                    <Column field="car" header="Автомобиль" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Автомобиль</span>
                            <p v-tooltip.bottom="slotProps.data.car.state_number">{{ `${slotProps.data.car.mark} ${slotProps.data.car.model}, ${slotProps.data.car.date.split('-')[0]}` }}</p>
                        </template>
                    </Column>
                    <Column field="station_service" header="Пункт обслуживания" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Пункт обслуживания</span>
                            <p>{{ slotProps.data.station_service }}</p>
                        </template>
                    </Column>
                    <Column field="date_begin" header="Дата и время начала" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Дата и время начала заказа</span>
                            <p>{{ slotProps.data.date_begin }}</p>
                        </template>
                    </Column>
                    <Column field="date_end" header="Дата и время конца" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Дата и время конца заказа</span>
                            <p>{{ slotProps.data.date_end }}</p>
                        </template>
                    </Column>
                    <Column field="cost" header="Стоимость" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Стоимость</span>
                            <p>{{ `${slotProps.data.cost}₽` }}</p>
                        </template>
                    </Column>
                    <Column field="status" header="Статус" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Статус</span>
                            <p>{{ status[slotProps.data.status].name }}</p>
                        </template>
                    </Column>
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps" class="flex" style="align-items: end;">
                            <div class="flex" style="align-items: center;">
                                <Button v-if="slotProps.data.status == 1" icon="pi pi-pencil" class="mr-2" severity="success" rounded text @click="editData(slotProps.data)" />
                                <Button v-if="adminRole" icon="pi pi-trash" class="mt-2" severity="danger" rounded text @click="confirmDeleteData(slotProps.data)" />
                            </div>
                        </template>
                    </Column>
                </DataTable>
                <!---->

                <!--Диалоги-->
                <Dialog v-model:visible="dataDialog" :style="{ width: '450px' }" header="Новый заказ" :modal="true" class="p-fluid">
                    <div class="field">
                        <label for="client">Клиент</label>
                        <AutoComplete v-model="data.client" dropdown optionLabel="name" optionValue="code" :suggestions="filtered_clients" @complete="searchClient" />
                        <small class="p-invalid" v-if="submitted && !data.client">Клиент обязательно.</small>
                    </div>
                    <div class="field">
                        <label for="car">Автомобиль</label>
                        <AutoComplete v-model="data.car" dropdown optionLabel="name" optionValue="code" :suggestions="filtered_cars" @complete="searchCar" />
                        <small class="p-invalid" v-if="submitted && !data.car">Вы не указали Автомобиль.</small>
                    </div>
                    <div class="field">
                        <label for="car">Количество дней аренды</label>
                        <InputNumber v-model="data.count_day" inputId="horizontal-buttons" showButtons buttonLayout="horizontal" :step="1"></InputNumber>
                        <small class="p-invalid" v-if="submitted && !data.count_day">Вы не указали Количество дней аренды.</small>
                    </div>
                    <template #footer>
                        <Button label="Отмена" icon="pi pi-times" text="" @click="hideDialog" />
                        <Button label="Сохранить" icon="pi pi-check" text="" @click="saveData" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="editDataDialog" :style="{ width: '450px' }" header="Изменение статуса заказа" :modal="true" class="p-fluid">
                    <div class="field">
                        <label for="status">Статус</label>
                        <Dropdown v-model="data.status" :options="status" optionLabel="name" optionValue="code" placeholder="Выберите статус" class="w-full md:w-14rem" />
                        <small class="p-invalid" v-if="submitted && !data.status">Вы не указали Статус.</small>
                    </div>
                    <template #footer>
                        <Button label="Отмена" icon="pi pi-times" text="" @click="hideEditDialog" />
                        <Button label="Сохранить" icon="pi pi-check" text="" @click="saveEditData" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteDataDialog" :style="{ width: '450px' }" header="Подтверждение" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="data"
                            >Вы уверены, что хотите удалить пункт обслуживания по адресу <b>{{ data.mark + ' ' + data.model }}</b
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

<style scoped></style>
