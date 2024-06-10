<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import axios from 'axios';
import { FilterMatchMode } from 'primevue/api';
import sourceUrl from '@/config';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const filters = ref({});

function getData() {
    axios.get(sourceUrl() + '/car_class/').then(function (response) {
        datas.value = response.data;
    });
}

function newData(obj) {
    axios({
        method: 'post',
        url: sourceUrl() + '/car_class/new',
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
        url: sourceUrl() + '/car_class/' + id + '/edit',
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
        url: sourceUrl() + '/car_class/' + id + '/delete'
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
    let title = data.value.title;
    deleteDataDialog.value = false;
    datas.value.splice(findIndexById(title), 1);
    deleteDataAx(title);
    data.value = {};
    toast.add({ severity: 'success', summary: 'Успешно', detail: 'Класс удален', life: 3000 });
};

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};

const saveData = () => {
    submitted.value = true;
    if (data.value.title.trim()) {
        if (checkEdit()) {
            console.log(data.value);
            datas.value[findIndexById(data.value.title)] = data.value;
            let response = Object.assign({}, data.value);
            updateData(response, response.title);
            toast.add({ severity: 'success', summary: 'Успешно', detail: 'Данные пункта обслуживания изменнены', life: 3000 });
        } else {
            datas.value.push(data.value);
            let response = Object.assign({}, data.value);
            newData(response);
            toast.add({ severity: 'success', summary: 'Успешно', detail: 'Пункт обслуживания добавлен', life: 3000 });
        }
        dataDialog.value = false;
        data.value = {};
    }
};

const findIndexById = (title) => {
    let index = -1;
    for (let i = 0; i < datas.value.length; i++) {
        if (datas.value[i].title === title) {
            index = i;
            break;
        }
    }
    return index;
};

const checkEdit = () => {
  return findIndexById(data.value.title) === -1 ? false : true;
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

const strYear = (el) => {
    if(el == 1){
      return ' год';
    }
    else if(el > 1 && el < 5){
      return  ' года';
    }
    return ' лет';
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
                <Toolbar class="mb-4" v-if="adminRole">
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
                            <h5 class="m-0">Классы автомобилей</h5>
                        </div>
                    </template>
                    <Column field="title" header="Класс" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Класс</span>
                            {{ slotProps.data.title }}
                        </template>
                    </Column>
                    <Column field="phone" header="Коэффициент цены" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Коэффициент цены</span>
                            {{ slotProps.data.coef }}
                        </template>
                    </Column>
                    <Column field="date_begin" header="Необходимый опыт вождения" :sortable="true" >
                        <template #body="slotProps">
                            <span class="p-column-title">Необходимый опыт вождения</span>
                            {{ slotProps.data.drv_exp + strYear(slotProps.data.drv_exp) }}
                        </template>
                    </Column>
                    <Column field="date_end" header="Необходимая категория ВУ" :sortable="true" >
                        <template #body="slotProps">
                            <span class="p-column-title">Необходимая категория ВУ</span>
                            {{ slotProps.data.category }}
                        </template>
                    </Column>
                  <Column field="date_end" header="Ограничения по пробегу" :sortable="true" >
                    <template #body="slotProps">
                      <span class="p-column-title">Ограничения по пробегу</span>
                      {{ slotProps.data.mileage_limit + ' км'}}
                    </template>
                  </Column>
                    <Column headerStyle="min-width:10rem;" v-if="adminRole">
                        <template #body="slotProps">
                            <Button  icon="pi pi-pencil" class="mr-2" severity="success" rounded text @click="editData(slotProps.data)" />
                            <Button icon="pi pi-trash" class="mt-2" severity="danger" rounded text @click="confirmDeleteData(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>
                <!---->

                <!--Диалог изменений-->
                <Dialog v-model:visible="dataDialog" :style="{ width: '450px' }" header="Информация о классах автомоблей" :modal="true" class="p-fluid">
                    <div class="field">
                        <label for="title">Класс</label>
                        <InputText :disabled="findIndexById(data.title) !== -1" id="title" v-model.trim="data.title" required="true" autofocus :invalid="submitted && !data.title" />
                        <small class="p-invalid" v-if="submitted && !data.title">Класс обязательно.</small>
                    </div>
                    <div class="field">
                        <label for="coef">Коэффициент цены</label>
                        <InputText id="coef" v-model="data.coef" required="true" autofocus :invalid="submitted && !data.coef" />
                        <small class="p-invalid" v-if="submitted && !data.coef">Вы не указали Коэффициент цены.</small>
                    </div>
                    <div class="field">
                        <label for="drv_exp">Необходимый опыт вождения (лет)</label>
                        <InputText v-model="data.drv_exp"  required="true" autofocus :invalid="submitted && !data.drv_exp" ></InputText>
                        <small class="p-invalid" v-if="submitted && !data.drv_exp">Вы не указали Необходимый опыт вождения.</small>
                    </div>
                    <div class="field">
                        <label for="category">Необходимая категория ВУ</label>
                        <InputText v-model="data.category" required="true" autofocus :invalid="submitted && !data.category" ></InputText>
                        <small class="p-invalid" v-if="submitted && !data.category">Вы не указали Необходмую категорию ВУ.</small>
                    </div>
                    <div class="field">
                      <label for="mileage_limit">Ограничения по пробегу (км)</label>
                      <InputText v-model="data.mileage_limit" required="true" autofocus :invalid="submitted && !data.mileage_limit" ></InputText>
                      <small class="p-invalid" v-if="submitted && !data.mileage_limit">Вы не указали Ограничения по пробегу.</small>
                    </div>
                    <template #footer>
                        <Button label="Отмена" icon="pi pi-times" @click="hideDialog" />
                        <Button label="Сохранить" icon="pi pi-check" @click="saveData" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteDataDialog" :style="{ width: '450px' }" header="Подтверждение" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="data"
                            >Вы уверены, что хотите удалить  класс автомобилей <b>{{ data.title }}</b
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
