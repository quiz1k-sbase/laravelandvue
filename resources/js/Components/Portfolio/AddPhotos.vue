<template>

    <nav class="navbar navbar-light navbar-expand-lg mb-5">
        <div class="container">
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav m-2">
                    <li class="nav-item me-2">
                        <router-link to="portfolio" type="button" class="btn btn-danger">Back</router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="card">
        <div class="d-flex card-header justify-content-center">Add photo</div>
        <div class="card-body">
            <div class="row row-cols-1 g-3">
                <input v-model="title" type="text" class="form-control" placeholder="Title">
                <input v-model="description" type="text" class="form-control" placeholder="Description">
                <div ref="dropzone" class="p-3 bg-secondary btn" style="color: white;">
                    Upload
                </div>
                <input @click.prevent="store" type="submit" class="btn btn-primary" value="Add">
            </div>
        </div>
    </div>
</template>

<script setup>

import {onMounted, ref} from "vue";
import Dropzone from "dropzone";
import axios from "axios";
import router from "../../routes";

let dropzone = ref([]);
let title = ref([]);
let description = ref([]);

onMounted(() => {
    dropzone = new Dropzone(dropzone.value, {
        url: 'store/photo',
        autoProcessQueue: false,
        maxFiles: 1,
        addRemoveLinks: true,
    })
})


const store = () => {
    const data = new FormData();
    const files = dropzone.getAcceptedFiles();
    files.forEach(file => {
        data.append('images[]', file);
        dropzone.removeFile(file)
    });
    data.append('title', title.value);
    data.append('description', description.value);
    title.value = '';
    description.value = '';
    axios.post('store/photo', data).then(res => {
        router.push({name: 'portfolio'});
    })
}
</script>
