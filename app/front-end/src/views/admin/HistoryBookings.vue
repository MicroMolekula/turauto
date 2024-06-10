<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import axios from 'axios';
import { FilterMatchMode } from 'primevue/api';
import sourceUrl from '@/config';
import { toRaw } from 'vue';
import * as XLSX from 'xlsx';

const filters = ref({});


function getClients() {
    axios.get(sourceUrl() + '/client').then((response) => {
        let tmp = response.data;
        tmp.forEach((el) => {
            clients.value.push({ name: `${el.surname} ${el.name[0]}. ${el.middlename[0]}.`, code: el.id });
        });
    });
}


function getBookings(id){
    axios.get(sourceUrl() + '/client/' + id + '/bookings')
    .then((response) => {
        datas.value = response.data
    });
}


onMounted(() => {
    getBookings(localStorage.getItem('user_id'));
    getClients();
});

onBeforeMount(() => {
    initFilters();
});

let datas = ref();
const filtered_clients = ref();
const adminRole = localStorage.getItem('user_role') == 'admin';
const clt_id = ref();
let status = ref([
    { name: 'Отменен', code: 0 },
    { name: 'Выполняется', code: 1 },
    { name: 'Выполнен', code: 2 }
]);


const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};


</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <!--Данные-->
                <DataTable :value="datas" :rows="10" tableStyle="min-width: 50rem">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Заказы</h5>
                        </div>
                    </template>
                    <Column field="car" header="Автомобиль" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Автомобиль</span>
                            <p v-tooltip.bottom="slotProps.data.car.state_number">{{ `${slotProps.data.car.mark} ${slotProps.data.car.model}, ${slotProps.data.car.date.split('-')[0]}` }}</p>
                        </template>
                    </Column>
                    <Column field="manager" header="Менеджер" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Менеджер</span>
                            <p v-tooltip.bottom="slotProps.data.manager.email">{{ `${slotProps.data.manager.surname} ${slotProps.data.manager.name[0]}. ${slotProps.data.manager.middlename[0]}.` }}</p>
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
                </DataTable>
                <!---->
            </div>
        </div>
    </div>
</template>

<style scoped></style>
