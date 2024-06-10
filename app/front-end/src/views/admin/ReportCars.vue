<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import axios from 'axios';
import { FilterMatchMode } from 'primevue/api';
import sourceUrl from '@/config';
import { useToast } from 'primevue/usetoast';
import { toRaw } from 'vue';
import * as XLSX from 'xlsx';

const toast = useToast();

const filters = ref({});

function getData() {
    axios.get(sourceUrl() + '/car/report').then(function (response) {
        datas.value = response.data;
    });
}

function getXLSX() {
    let response = [];
    datas.value.forEach((element) => {
        let tmp = new Object();
        tmp['Автомобиль'] = `${element.car_make} ${element.car_model}`;
        tmp['Количество заказов'] = `${element.count}`;
        tmp['Общая сумма заказов'] = `${element.sum}`;
        response.push(tmp);
    });
    response = toRaw(response);
    const worksheet = XLSX.utils.json_to_sheet(response);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Отчет по заказам и автомобилям');
    XLSX.writeFile(workbook, 'Отчет по заказам и автомобилям.xlsx', { compression: true });
}

onMounted(() => {
    getData();
});

onBeforeMount(() => {
    initFilters();
});

let datas = ref();
const adminRole = localStorage.getItem('user_role') == 'admin';

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};
</script>

<template>
    <div v-if="datas == undefined" style="text-align: center">
        <i class="pi pi-spin pi-spinner" style="font-size: 4rem; margin-top: 16rem"></i>
    </div>
    <div class="grid" v-if="datas !== undefined">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4" v-if="adminRole">
                    <template v-slot:start>
                        <Button label="Excel" icon="pi pi-upload" severity="help" @click="getXLSX" />
                    </template>
                </Toolbar>
                <!--Данные-->
                <DataTable :value="datas" :rows="10" tableStyle="min-width: 50rem">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Отчет по заказам и автомобилям</h5>
                        </div>
                    </template>
                    <Column field="car" header="Автомобиль" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">ФИО сотрудника</span>
                            <p v-tooltip.bottom="slotProps.data.car_vin">{{ `${slotProps.data.car_make} ${slotProps.data.car_model}` }}</p>
                        </template>
                    </Column>
                    <Column field="count" header="Количество заказов" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Количество заказов</span>
                            {{ slotProps.data.count }}
                        </template>
                    </Column>
                    <Column field="sum" header="Общая стоимость заказов" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Общая стоимость заказов</span>
                            {{ slotProps.data.sum + '₽' }}
                        </template>
                    </Column>
                </DataTable>
                <!---->
            </div>
        </div>
    </div>
</template>
