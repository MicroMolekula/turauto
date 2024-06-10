<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import axios from 'axios';
import { FilterMatchMode } from 'primevue/api';
import sourceUrl from '@/config';
import { useToast } from 'primevue/usetoast';
import * as XLSX from 'xlsx';
import { toRaw } from 'vue';



const filters = ref({});

function getData() {
    axios.get(sourceUrl() + '/manager/report').then(function (response) {
        datas.value = response.data;
    });
}

function getXLSX() {

    let response = [];
    datas.value.forEach(element => {
        let tmp = new Object();
        tmp["ФИО сотрудника"] = `${element.mng_surname} ${element.mng_name} ${element.mng_midlename}`;
        tmp["Количество заказов"] = `${element.count}`;
        tmp["Общая сумма заказов"] = `${element.sum}`;
        response.push(tmp);
    });
    response = toRaw(response);
    const worksheet = XLSX.utils.json_to_sheet(response);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Отчет по продажам сотрудников");
    XLSX.writeFile(workbook, "Отчет по продажам сотрудников.xlsx", { compression: true });

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
    <div v-if="datas == undefined" style="text-align: center;">
        <i class="pi pi-spin pi-spinner" style="font-size: 4rem; margin-top: 16rem;"></i>
    </div>
    <div class="grid" v-if="datas !== undefined">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4" v-if="adminRole">
                    <template v-slot:start>
                        <div class="my-2">
                            <Button label="Excel" icon="pi pi-upload" severity="help" @click="getXLSX" />
                        </div>
                    </template>
                </Toolbar>
                <!--Данные-->
                <DataTable :value="datas" :rows="10" tableStyle="min-width: 50rem">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Отчет по продажам сотрудников</h5>
                        </div>
                    </template>
                    <Column field="manager" header="ФИО сотрудника" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">ФИО сотрудника</span>
                            {{ `${slotProps.data.mng_surname} ${slotProps.data.mng_name} ${slotProps.data.mng_midlename}` }}
                        </template>
                    </Column>
                    <Column field="count" header="Количество заказов" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Количество заказов</span>
                            {{ slotProps.data.count }}
                        </template>
                    </Column>
                    <Column field="sum" header="Общая стоимость заказов" :sortable="true" >
                        <template #body="slotProps">
                            <span class="p-column-title">Время открытия</span>
                            {{ slotProps.data.sum + "₽" }}
                        </template>
                    </Column>
                </DataTable>
                <!---->
            </div>
        </div>
    </div>
</template>
