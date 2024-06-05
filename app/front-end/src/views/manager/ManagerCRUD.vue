<template>
  <div>
    <v-data-table
      hide-default-footer
      items-per-page="1000"
      :headers="headers"
      :items="data"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Менеджеры</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <!--Диалог изменения-->
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ props }">
              <v-btn class="mb-2" color="primary" dark v-bind="props">
                Новый элемент
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="text-h5">{{ formTitle() }}</span>
              </v-card-title>
              <v-card-text>
                <!--Диалог изменения-->
                <v-container>
                  <v-row>
                    <v-text-field v-model="editedItem.surname" label="Фамилия">
                    </v-text-field>
                  </v-row>
                  <v-row>
                    <v-text-field v-model="editedItem.name" label="Имя">
                    </v-text-field>
                  </v-row>
                  <v-row>
                    <v-text-field
                      v-model="editedItem.middlename"
                      label="Отчество"
                    >
                    </v-text-field>
                  </v-row>
                  <v-row>
                    <v-autocomplete
                      label="Пункт обслживания"
                      v-model="editedItem.station_service"
                      :items="stations_service"
                    ></v-autocomplete>
                  </v-row>
                  <v-row>
                    <v-text-field
                      v-model="editedItem.passport_details"
                      label="Паспортные данные"
                    >
                    </v-text-field>
                  </v-row>
                  <v-row>
                    <v-text-field
                      :disabled="editedIndex !== -1"
                      v-model="editedItem.password"
                      label="Пароль"
                      :type="show1 ? 'text' : 'password'"
                      @click:append="show1 = !show1"
                      :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                    >
                    </v-text-field>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-darken-1" variant="text" @click="close">
                  Отмена
                </v-btn>
                <v-btn color="blue-darken-1" variant="text" @click="save">
                  Cохранить
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

          <!--Диалог удаления-->
          <v-dialog v-model="dialogDelete" max-width="400px">
            <v-card>
              <v-card-title class="text-center text-h5"
                >Вы уверенны, что хотите <br />
                удалить этот элемент?</v-card-title
              >
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-darken-1" variant="text" @click="closeDelete"
                  >Отмена</v-btn
                >
                <v-btn
                  color="blue-darken-1"
                  variant="text"
                  @click="deleteItemConfirm"
                  >Да</v-btn
                >
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon class="me-2" size="small" @click="editItem(item)">
          mdi-pencil
        </v-icon>
        <v-icon size="small" @click="deleteItem(item)"> mdi-delete </v-icon>
      </template>
      <template v-slot:no-data>
        <div class="h-40">
          <v-progress-circular
            class="mt-5"
            indeterminate
            :size="40"
          ></v-progress-circular>
        </div>
      </template>
    </v-data-table>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import sourceUrl from "@/config";
import { toRaw } from "vue";

let show1 = ref(false);

let dialog = ref(false);
let dialogDelete = ref(false);
let headers = ref([
  {
    title: "Фамилия",
    align: "start",
    key: "surname",
  },
  { title: "Имя", key: "name" },
  { title: "Отчество", key: "middlename" },
  { title: "Пункт Обслуживания", key: "station_service" },
  { title: "Паспортные данные", key: "passport_details" },
  { title: "Должность", key: "role" },
  { title: "Действия", key: "actions", sortable: false },
]);

let data = ref();
let stations_service = ref();

getData();
getStnServ();

let editedIndex = ref();
let elIndex = ref();

let editedItem = ref({
  id: null,
  surname: "",
  name: "",
  middlename: "",
  station_service: "",
  passport_details: "",
  role: "",
  password: "",
});

let defaultItem = ref({
  id: null,
  surname: "",
  name: "",
  middlename: "",
  station_service: "",
  passport_details: "",
  role: "",
  password: "",
});

function formTitle() {
  return editedIndex.value === -1 ? "Новый элемент" : "Изменить элемент";
}

function editItem(item) {
  editedIndex.value = data.value.indexOf(item);
  editedItem.value = Object.assign({}, item);
  dialog.value = true;
}

function deleteItem(item) {
  editedIndex.value = data.value.indexOf(item);
  elIndex.value = item.id;
  editedItem.value = Object.assign({}, item);
  dialogDelete.value = true;
}

function deleteItemConfirm() {
  data.value.splice(editedIndex.value, 1);
  deleteData(elIndex.value);
  closeDelete();
}

function close() {
  dialog.value = false;
  editedItem.value = Object.assign({}, defaultItem.value);
  editedIndex.value = -1;
}

function closeDelete() {
  dialogDelete.value = false;
  editedItem.value = Object.assign({}, defaultItem.value);
  editedIndex.value = -1;
}

function save() {
  if (editedIndex.value > -1) {
    Object.assign(data.value[editedIndex.value], editedItem.value);
    updateData(toRaw(editedItem.value), editedItem.value.id);
  } else {
    data.value.push(editedItem.value);
    newData(toRaw(editedItem.value));
  }
  close();
}

function getData() {
  axios.get(sourceUrl() + "/manager").then(function (response) {
    data.value = response.data;
  });
}

function getStnServ() {
  axios.get(sourceUrl() + "/station_service").then(function (response) {
    stations_service.value = response.data;
    let tmp = [];
    stations_service.value.forEach((element) => {
      tmp.push(element.address);
    });
    stations_service.value = tmp;
  });
}

function newData(obj) {
  axios({
    method: "post",
    url: sourceUrl() + "/manager/new",
    data: obj,
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
    method: "put",
    url: sourceUrl() + "/manager/" + id + "/edit",
    data: obj,
  })
    .then(function (response) {
      console.log(response.data);
    })
    .catch(function (error) {
      console.log(error);
    });
}

function deleteData(id) {
  axios({
    method: "delete",
    url: sourceUrl() + "/manager/" + id + "/delete",
  })
    .then(function (response) {
      console.log(response.data);
    })
    .catch(function (error) {
      console.log(error);
    });
}
</script>

<style scoped></style>
