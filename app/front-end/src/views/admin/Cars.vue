<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import axios from 'axios';
import { FilterMatchMode } from 'primevue/api';
import sourceUrl from '@/config';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const filters = ref({});

const limit = ref(9);
const currentPage = ref(0);
const totalItems = ref(0);
let datas = ref();
const dataViewKey = ref(0);


function getData(currentPage) {
    axios.get(sourceUrl() + '/car?page=' + currentPage.toString()).then(function (response) {
        datas.value = response.data.cars.map((el) => {
          el.date = el.date.split('-')[0];
          return el;
        });
        totalItems.value = response.data.pages.totalItems;
        dataViewKey.value++;
    });
}

function onPageChange(event) {
  currentPage.value = event.page;
  datas = ref();
  getData(currentPage.value + 1)
}

function getCarsFCl() {
    axios.get(sourceUrl() + '/car/for_client/').then(function (response) {
        datas.value = response.data;
        datas.value = datas.value.map((el) => {
            el.date = el.date.split('-')[0];
            return el;
        });
    });
}

function newData(obj) {
    return axios({
        method: 'post',
        url: sourceUrl() + '/car/new',
        data: obj
    })
}

function updateData(obj, id) {
    return axios({
        method: 'put',
        url: sourceUrl() + '/car/' + id + '/edit',
        data: obj
    })
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
        console.log(response.data);
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
        console.log(gearboxes);
    });
}

function getCarBodyType() {
    axios.get(sourceUrl() + '/utils/car_body_type').then(function (response) {
        carBodyType.value = response.data;
        console.log(carBodyType);
    });
}

function getAddServices() {
    axios.get(sourceUrl() + '/add_service')
    .then((response) => {
        response.data.forEach(element => {
            addServicesArray.value.push(element.title);
        });
        console.log(addServicesArray);
    });
}

onMounted(() => {
    if(localStorage.getItem('user_role') == 'client'){
        getCarsFCl();
    }
    else {
        getData(currentPage.value + 1);
    }
    getStnServ();
    getGearboxes();
    getCarBodyType();
    getCarClasses();
    getAddServices();
});

onBeforeMount(() => {
    initFilters();
});


let data = ref({});
const dataDialog = ref(false);
const deleteDataDialog = ref(false);
const submitted = ref(false);
let status = ref([
    { name: 'Удален из каталога', code: 0 },
    { name: 'В аренде', code: 1 },
    { name: 'Находится в пункет выдачи', code: 2 },
    { name: 'В ремонте', code: 3 }
]);
const stations_service = ref();
const filtered_station_service = ref();
const gearboxes = ref();
const carBodyType = ref();
const carClasses = ref();
const image = ref();
const visible = localStorage.getItem('user_role') !== 'client';
const op = ref();
const addServices = ref([]);
const addServicesArray = ref([]);
const adminRole = localStorage.getItem('user_role') == 'admin';
const managerRole = localStorage.getItem('user_role') == 'admin' || localStorage.getItem('user_role') == 'manager';
const clientRole = localStorage.getItem('user_role') == 'client';
const orderDialog = ref(false);
const orderData = ref({
    client: localStorage.getItem('user_id'),
    car: '',
    count_day: 1,
});

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

const validateMark = (mark) => {
  // Регулярное выражение: хотя бы одна буква и не только цифры
  const regex = /^(?=.*[a-zA-Zа-яА-Я])[a-zA-Zа-яА-Я0-9\s]+$/;
  return regex.test(mark);
};

const validMark = ref(true)

const saveData = () => {
    submitted.value = true;

  if (!validateMark(data.value.mark)) {
    toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Поле "Марка" должно содержать буквы и может содержать цифры', life: 3000 });
    validMark.value = false
    return; // Прерываем выполнение, если валидация не пройдена
  }

    if (data.value.model) {
        if (checkEdit()) {
            console.log(data.value);
            data.value.image = image.value == undefined ? data.value.image : image.value;
            data.value.date = typeof data.value.date === 'object' ? data.value.date.toLocaleDateString().split('.')[2] : data.value.date;

            let response = Object.assign({}, data.value);
            response.date = response.date + '-01-01';
            console.log(response);
            updateData(response, response.id)
                .then((response) => {
                  datas.value[findIndexById(data.value.id)] = data.value;
                  toast.add({ severity: 'success', summary: 'Успешно', detail: 'Данные автомобиля измененны', life: 3000 });
                })
                .catch((e) => {
                  toast.add({ severity: 'error', summary: 'Ошибка', detail: 'При изменение данных авомобиля произшла ошибка попробуйте позже', life: 3000 });
                });

        } else {
            data.value.image = image.value;
            data.value.date = typeof data.value.date === 'object' ? data.value.date.toLocaleDateString().split('.')[2] : data.value.date;

            let response = Object.assign({}, data.value);
            response.date = response.date + '-01-01';
            console.log(response);
            newData(response)
                .then((response) => {
                  datas.value.push(data.value);
                  toast.add({ severity: 'success', summary: 'Успешно', detail: 'Автомобиль добавлен', life: 3000 });
                })
                .catch((e) => {
                  toast.add({ severity: 'error', summary: 'Ошибка', detail: 'При добовление автомобиля произошла ошибка попробуйте позже', life: 3000 });
                });
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
    toast.add({ severity: 'info', summary: 'Успешно', detail: 'Файл загружен', life: 3000 });
};

const checkEdit = () => {
    return findIndexById(data.value.id) === -1 ? false : true;
};

const toggle = (event) => {
    op.value.toggle(event);
};

const addDataServices = (data) => {
    console.log(data);
    addServices.value = [...data];
};

const orderForm = (id) => {
    orderData.value.car = id;
    orderDialog.value = true;
};

const saveOrder = () => {
    let obj = Object.assign({}, orderData.value);
    console.log(obj);
    axios({
        method: 'post',
        url: sourceUrl() + '/booking/new',
        data: obj
    })
        .then(function (response) {
            console.log(response.data);
            orderDialog.value = false;
        })
        .catch(function (error) {
            console.log(error);
        });
};

const hideOrderDialog = () =>{
    orderDialog.value = false;
};
</script>

<template>
    <div v-if="visible"></div>
    <div v-if="datas == undefined" style="text-align: center">
        <i class="pi pi-spin pi-spinner" style="font-size: 4rem; margin-top: 16rem"></i>
    </div>
    <div class="grid" v-if="datas !== undefined">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <div class="my-2" v-if="adminRole">
                            <Button label="Новый" icon="pi pi-plus" class="mr-2" severity="success" @click="openNew" />
                        </div>
                    </template>
                </Toolbar>
                <DataView :key="dataViewKey" :pageLinkSize="10" :paginatorPosition="'top'" :value="datas" :layout="'grid'" :paginator="true" :rows="limit" :totalRecords="totalItems" @page="onPageChange">
                    <template #grid="slotProps">
                        <div class="grid grid-nogutter">
                            <div v-for="(item, index) in slotProps.items" :key="index" class="col-12 sm:col-6 md:col-4 p-2">
                                <div class="p-4 border-1 surface-border surface-card border-round flex flex-column">
                                    <div class="surface-50 flex justify-content-center border-round relative">
                                        <div class="relative">
                                            <img class="border-round w-full" :src="`${sourceUrl()}${item.image}`" :alt="item.model + item.mark" style="max-width: 300px; max-height: 140px" />
                                        </div>
                                        <Tag v-if="adminRole" :value="status[item.status].name" class="absolute" style="left: 4px; top: 4px"></Tag>
                                    </div>
                                    <div class="pt-4">
                                        <div class="flex flex-row justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="text-lg font-medium text-900 mt-1">{{ item.mark + ' ' + item.model + ', ' + item.date }}</div>
                                            </div>
                                            <div>
                                                <Button class="p-0 mt-3" @click="addDataServices(item.add_services)" text rounded><Button type="button" text rounded icon="pi pi-info" @click="toggle"></Button></Button>
                                                <Button v-if="managerRole" icon="pi pi-pencil" class="mr-2" severity="success" rounded text @click="editData(item)" />
                                                <Button v-if="managerRole" icon="pi pi-trash" class="mt-2" severity="danger" rounded text @click="confirmDeleteData(item)" />
                                            </div>
                                        </div>
                                        <div>
                                            <div v-if="managerRole"><b>VIN-номер:</b> {{ item.id }}</div>
                                            <div v-if="managerRole"><b>Гос. номер:</b> {{ item.state_number }}</div>
                                            <div><b>Класс авто:</b> {{ item.cls_title }}</div>
                                            <div><b>Цвет:</b> {{ item.color }}</div>
                                            <div><b>Тип кузова:</b> {{ item.body_type }}</div>
                                            <div><b>Коробка передач:</b> {{ item.gearbox }}</div>
                                            <div><b>Пункт обслуживания:</b> {{ item.station_service }}</div>
                                            <Button v-if="clientRole" label="Заказать" class="mt-3" @click="orderForm(item.id)"></Button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </DataView>
                <OverlayPanel ref="op">
                    <div>
                        <ul>
                            <li v-for="item in addServices">
                                {{ item }}
                            </li>
                        </ul>
                    </div>
                </OverlayPanel>

                <!--Диалог изменений-->
                <Dialog v-model:visible="dataDialog" :style="{ width: '450px' }" header="Информация о автомобиле" :modal="true" class="p-fluid">
                    <div class="field">
                        <label for="mark">Изображение авто</label>
                        <FileUpload mode="basic" :url="sourceUrl() + '/car/upload'" name="demo[]" accept="image/*" :maxFileSize="1000000" @upload="onUpload" />
                        <small class="p-invalid" v-if="submitted && !data.mark">Марка обязательно.</small>
                    </div>
                    <div class="field">
                        <label for="mark">Марка</label>
                        <InputText id="mark" v-model.trim="data.mark" required="true" autofocus :invalid="submitted && !data.mark" />
                        <small class="p-invalid" v-if="submitted && !data.mark">Марка обязательно.</small>
                        <small style="color: red;" class="p-invalid" v-if="submitted && !validMark">Марка не должна содержать только цифры</small>
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
                        <InputText id="color" v-model="data.color" required="true" autofocus :invalid="submitted && !data.color" />
                        <small class="p-invalid" v-if="submitted && !data.color">Вы не указали Цвет.</small>
                    </div>
                    <div class="field">
                        <label for="status">Статус</label>
                        <Dropdown v-model="data.status" :options="status" optionLabel="name" optionValue="code" placeholder="Выберите статус" class="w-full md:w-14rem" />
                        <small class="p-invalid" v-if="submitted && !data.status">Вы не указали Статус.</small>
                    </div>
                    <div class="field">
                        <label for="add_services">Дополнительные опции</label>
                        <MultiSelect v-model="data.add_services" :options="addServicesArray" placeholder="Дополнительные опции" :maxSelectedLabels="3" class="w-full" />
                        <small class="p-invalid" v-if="submitted && !data.add_services">Вы не указали Дополнительные опции ВУ.</small>
                    </div>
                    <div class="field">
                        <label for="gearbox">Коробка передач</label>
                        <Dropdown v-model="data.gearbox" :options="gearboxes" placeholder="Выберите тип коробки передач" class="w-full md:w-14rem" />
                        <small class="p-invalid" v-if="submitted && !data.gearbox">Вы не указали Коробку передач.</small>
                    </div>
                    <div class="field">
                        <label for="station_service">Пункт обслуживания</label>
                        <AutoComplete v-model="data.station_service" dropdown :suggestions="filtered_station_service" @complete="search" />
                        <small class="p-invalid" v-if="submitted && !data.station_service">Вы не выбрали пункт обслуживания.</small>
                    </div>

                    <template #footer>
                        <Button v-if="adminRole" label="Отмена" icon="pi pi-times" text="" @click="hideDialog" />
                        <Button v-if="adminRole" label="Сохранить" icon="pi pi-check" text="" @click="saveData" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteDataDialog" :style="{ width: '450px' }" header="Подтверждение" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span
                            >Вы уверены, что хотите удалить автомобиль <b>{{ data.mark + ' ' + data.model }}</b
                            >?</span
                        >
                    </div>
                    <template #footer>
                        <Button label="Нет" icon="pi pi-times" text @click="deleteDataDialog = false" />
                        <Button label="Да" icon="pi pi-check" text @click="deleteData" />
                    </template>
                    <template> </template>
                </Dialog>

                <Dialog v-if="clientRole" v-model:visible="orderDialog" :style="{ width: '450px' }" header="Новый заказ" :modal="true" class="p-fluid">
                    <div class="field">
                        <label for="car">Количество дней аренды</label>
                        <InputNumber v-model="orderData.count_day" inputId="horizontal-buttons" showButtons buttonLayout="horizontal" :step="1"></InputNumber>
                        <small class="p-invalid" v-if="submitted && !data.count_day">Вы не указали Количество дней аренды.</small>
                    </div>
                    <template #footer>
                        <Button label="Отмена" icon="pi pi-times" text="" @click="hideOrderDialog" />
                        <Button label="Сохранить" icon="pi pi-check" text="" @click="saveOrder" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>
