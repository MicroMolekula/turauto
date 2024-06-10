<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import axios from 'axios';
import { FilterMatchMode } from 'primevue/api';
import sourceUrl from '@/config';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const filters = ref({});

function getData() {
  axios.get(sourceUrl() + '/add_service/').then(function (response) {
    datas.value = response.data;
  });
}

function newData(obj) {
  axios({
    method: 'post',
    url: sourceUrl() + '/add_service/new',
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
    url: sourceUrl() + '/add_service/' + id + '/edit',
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
    url: sourceUrl() + '/add_service/' + id + '/delete'
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
  toast.add({ severity: 'success', summary: 'Успешно', detail: 'Опция удалена', life: 3000 });
};

const initFilters = () => {
  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
  };
};

const saveData = () => {
  submitted.value = true;
  if (data.value.title) {
    if (checkEdit()) {
      datas.value[findIndexById(data.value.title)] = data.value;
      let response = Object.assign({}, data.value);
      console.log(response);
      updateData(response, response.title);
      toast.add({ severity: 'success', summary: 'Успешно', detail: 'Данные Опции изменнены', life: 3000 });
    } else {
      datas.value.push(data.value);
      let response = Object.assign({}, data.value);
      newData(response);
      console.log(response);
      toast.add({ severity: 'success', summary: 'Успешно', detail: 'Опция добавлена', life: 3000 });
    }
    dataDialog.value = false;
    data.value = {};
  }
};

const findIndexById = (title) => {
  let index = -1;
  for (let i = 0; i < datas.value.length; i++) {
    if (datas.value[i].title == title) {
      index = i;
      break;
    }
  }
  return index;
};

const checkEdit = () => {
  return findIndexById(data.value.title) !== -1;
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
              <h5 class="m-0">Дополнительные опции</h5>
            </div>
          </template>
          <Column field="type" header="Название" :sortable="true">
            <template #body="slotProps">
              <span class="p-column-title">Название</span>
              {{ slotProps.data.title }}
            </template>
          </Column>
          <Column field="cost" header="Цена" :sortable="true">
            <template #body="slotProps">
              <span class="p-column-title">Цена</span>
              {{ slotProps.data.cost + '₽' }}
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
        <Dialog v-model:visible="dataDialog" :style="{ width: '450px' }" header="Информация о дополнительной опции" :modal="true" class="p-fluid">
          <div class="field">
            <label for="title">Название опции</label>
            <InputText :disabled="findIndexById(data.title) !== -1" id="title" v-model.trim="data.title" required="true" autofocus :invalid="submitted && !data.title" />
            <small class="p-invalid" v-if="submitted && !data.title">Название опции обязательно.</small>
          </div>
          <div class="field">
            <label for="cost">Цена (₽)</label>
            <InputText id="cost" v-model="data.cost" required="true" autofocus :invalid="submitted && !data.cost" />
            <small class="p-invalid" v-if="submitted && !data.cost">Вы не указали Цену.</small>
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
            >Вы уверены, что хотите удалить  опции <b>{{ data.title }}</b
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
