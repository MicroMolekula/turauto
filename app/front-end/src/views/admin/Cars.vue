<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import axios from 'axios';
import { FilterMatchMode } from 'primevue/api';
import sourceUrl from '@/config';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const filters = ref({});

function getData() {
    axios.get(sourceUrl() + '/car/').then(function (response) {
        datas.value = response.data;
        datas.value = datas.value.map((el) => {
            el.date = el.date.split('-')[0];
            return el;  
        });
    });
}

function newData(obj) {
    axios({
        method: 'post',
        url: sourceUrl() + '/car/new',
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
        url: sourceUrl() + '/car/' + id + '/edit',
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
        url: sourceUrl() + '/car/' + id + '/delete'
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

function getCarClasses() {
    axios.get(sourceUrl() + '/car_class').then(function (response) {
        console.log(response.data)
        carClasses.value = response.data;
        let tmp = [];
        carClasses.value.forEach((element) => {
            tmp.push(element.title);
        });
        carClasses.value = tmp;
    });
}

function getGearboxes() {
    axios.get(sourceUrl() + '/utils/gearboxes').then(function (response) {
       gearboxes.value = response.data;
       console.log(gearboxes)
    });
}

function getCarBodyType() {
    axios.get(sourceUrl() + '/utils/car_body_type').then(function (response) {
       carBodyType.value = response.data;
       console.log(carBodyType)
    });
}

onMounted(() => {
    getData();
    getStnServ();
    getGearboxes();
    getCarBodyType();
    getCarClasses();
});

onBeforeMount(() => {
    initFilters();
});

let datas = ref();
let data = ref({});
const dataDialog = ref(false);
const deleteDataDialog = ref(false);
const submitted = ref(false);
let status = ref([
    {name: "Удален из каталога", code: 0},
    {name: "В аренде", code: 1},
    {name: "Находится в пункет выдачи", code: 2},
    {name: "В ремонте", code: 3},
]);
const stations_service = ref();
const filtered_station_service = ref();
const gearboxes = ref();
const carBodyType = ref();
const carClasses = ref();
const image = ref();

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
    console.log(data.value);
    deleteDataDialog.value = false;
    datas.value.splice(findIndexById(id), 1);
    deleteDataAx(id);
    console.log(id);
    data.value = {};
    toast.add({ severity: 'success', summary: 'Успешно', detail: 'Автомобиль удален', life: 3000 });
};

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};

const saveData = () => {
    submitted.value = true;
    if (data.value.model) {
        if (checkEdit()) {
            console.log(data.value)
            data.value.image = image.value == undefined ? data.value.image : image.value ;
            data.value.date = typeof data.value.date === 'object' ? data.value.date.toLocaleDateString().split('.')[2] : data.value.date;
            datas.value[findIndexById(data.value.id)] = data.value;
            let response = Object.assign({}, data.value);
            response.date = response.date + "-01-01";
            console.log(response);
            updateData(response, response.id);
            toast.add({ severity: 'success', summary: 'Успешно', detail: 'Данные автомобиля измененны', life: 3000 });
        } else {
            data.value.image = image.value;
            data.value.date = typeof data.value.date === 'object' ? data.value.date.toLocaleDateString().split('.')[2] : data.value.date;
            datas.value.push(data.value);
            let response = Object.assign({}, data.value);
            response.date = response.date + "-01-01";
            console.log(response);
            newData(response);
            toast.add({ severity: 'success', summary: 'Успешно', detail: 'Автомобиль добавлен', life: 3000 });
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

const onUpload = (response) => {
    image.value = JSON.parse(response.xhr.response).path;
    console.log(image);
    toast.add({ severity: 'info', summary: 'Success', detail: 'File Uploaded', life: 3000 });
};

const checkEdit = () => {
    return findIndexById(data.value.id) === -1 ? false : true;
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

                    <template v-slot:end>
                        <Button label="Excel" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>
                <DataView :value="datas" :layout="'grid'" :paginator="true" :rows="9">

                    <template #grid="slotProps">
                        <div class="grid grid-nogutter">
                            <div v-for="(item, index) in slotProps.items" :key="index" class="col-12 sm:col-6 md:col-4 p-2">
                                <div class="p-4 border-1 surface-border surface-card border-round flex flex-column">
                                    <div class="surface-50 flex justify-content-center border-round relative">
                                        <div class="relative">
                                            <img class="border-round w-full" :src="`${sourceUrl()}${item.image}`" :alt="item.model + item.mark" style="max-width: 300px; max-height: 140px;" />
                                        </div>
                                        <Tag :value="status[item.status].name" class="absolute" style="left: 4px; top: 4px"></Tag>
                                    </div>
                                    <div class="pt-4">
                                        <div class="flex flex-row justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="text-lg font-medium text-900 mt-1">{{ item.mark + " " + item.model + ", " + item.date }}</div>
                                            </div>
                                            <div>
                                                <Button icon="pi pi-pencil" class="mr-2" severity="success" rounded text @click="editData(item)" />
                                                <Button icon="pi pi-trash" class="mt-2" severity="danger" rounded text @click="confirmDeleteData(item)" />
                                            </div>
                                        </div>
                                        <div>
                                            <ul>
                                                <li><b>VIN-номер:</b> {{ item.id }}</li>
                                                <li><b>Гос. номер:</b> {{ item.state_number }}</li>
                                                <li><b>Класс авто:</b> {{ item.cls_title }}</li>
                                                <li><b>Цвет:</b> {{ item.color }}</li>
                                                <li><b>Тип кузова:</b> {{ item.body_type }}</li>
                                                <li><b>Коробка передач:</b> {{ item.gearbox }}</li>
                                                <li><b>Пункт обслуживания:</b> {{ item.station_service }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </DataView>

                <!--Диалог изменений-->
                <Dialog v-model:visible="dataDialog" :style="{ width: '450px' }" header="Информация о клиенте" :modal="true" class="p-fluid">
                    <div class="field">
                        <label for="mark">Изображение авто</label>
                        <FileUpload mode="basic" :url="sourceUrl()+'/car/upload'" name="demo[]"  accept="image/*" :maxFileSize="1000000" @upload="onUpload" />
                        <small class="p-invalid" v-if="submitted && !data.mark">Марка обязательно.</small>
                    </div>
                    <div class="field">
                        <label for="mark">Марка</label>
                        <InputText id="mark" v-model.trim="data.mark" required="true" autofocus :invalid="submitted && !data.mark" />
                        <small class="p-invalid" v-if="submitted && !data.mark">Марка обязательно.</small>
                    </div>
                    <div class="field">
                        <label for="model">Модель</label>
                        <InputText id="model" v-model.trim="data.model" required="true" autofocus :invalid="submitted && !data.model" />
                        <small class="p-invalid" v-if="submitted && !data.model">Модель обязательно.</small>
                    </div>
                    <div class="field">
                        <label for="date">Дата выпуска</label>
                        <Calendar v-model.trim="data.date" :dateFormat="'yy'" showButtonBar showIcon required="true" autofocus :invalid="submitted && !data.date" />
                        <small class="p-invalid" v-if="submitted && !data.date">Дата выпуска обязательно.</small>
                    </div>
                    <div class="field">
                        <label for="state_number">Гос. номер</label>
                        <InputText id="state_number" v-model.trim="data.state_number" required="true" autofocus :invalid="submitted && !data.state_number" />
                        <small class="p-invalid" v-if="submitted && !data.state_number">Вы не указали Гос. номер.</small>
                    </div>
                    <div class="field">
                        <label for="id">VIN-номер</label>
                        <InputText :disabled="checkEdit()" id="id" v-model="data.id" required="true" autofocus :invalid="submitted && !data.id" />
                        <small class="p-invalid" v-if="submitted && !data.id">Вы не указали VIN-номер.</small>
                    </div>
                    <div class="field">
                        <label for="cls_title">Класс авто</label>
                        <Dropdown v-model="data.cls_title" :options="carClasses" placeholder="Выберите класс авто" class="w-full md:w-14rem" />
                        <small class="p-invalid" v-if="submitted && !data.cls_title">Вы не указали Класс авто.</small>
                    </div>
                    <div class="field">
                        <label for="body_type">Тип кузова</label>
                        <Dropdown v-model="data.body_type" :options="carBodyType" placeholder="Выберите тип кузова" class="w-full md:w-14rem" />
                        <small class="p-invalid" v-if="submitted && !data.body_type">Вы не указали Тип кузова.</small>
                    </div>
                    <div class="field">
                        <label for="color">Цвет</label>
                        <InputText id="color" v-model="data.color" required="true" autofocus :invalid="submitted && !data.color"/>
                        <small class="p-invalid" v-if="submitted && !data.color">Вы не указали Цвет.</small>
                    </div>
                    <div class="field">
                        <label for="status">Статус</label>
                        <Dropdown v-model="data.status" :options="status" optionLabel="name" optionValue="code" placeholder="Выберите статус" class="w-full md:w-14rem" />
                        <small class="p-invalid" v-if="submitted && !data.status">Вы не указали Статус.</small>
                    </div>
                    <div class="field">
                        <label for="gearbox">Коробка передач</label>
                        <Dropdown v-model="data.gearbox" :options="gearboxes" placeholder="Выберите тип коробки передач" class="w-full md:w-14rem" />
                        <small class="p-invalid" v-if="submitted && !data.gearbox">Вы не указали Коробку передач.</small>
                    </div>
                    <div class="field">
                        <label for="station_service">Пункт обслуживания</label>
                        <AutoComplete v-model="data.station_service" dropdown :suggestions="filtered_station_service" @complete="search"/>
                        <small class="p-invalid" v-if="submitted && !data.station_service">Вы не выбрали пункт обслуживания.</small>
                    </div>

                    <template #footer>
                        <Button label="Отмена" icon="pi pi-times" text="" @click="hideDialog" />
                        <Button label="Сохранить" icon="pi pi-check" text="" @click="saveData" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteDataDialog" :style="{ width: '450px' }" header="Подтверждение" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span
                            >Вы уверены, что хотите удалить автомобиль <b>{{ data.mark + " " + data.model }}</b
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
