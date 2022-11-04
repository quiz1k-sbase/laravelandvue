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
        <div class="d-flex card-header justify-content-center">Add video link</div>
        <div class="card-body">
            <div class="row row-cols-1 g-3">
                <input v-model="title" type="text" class="form-control" placeholder="Title">
                <input v-model="description" type="text" class="form-control" placeholder="Description">
                <input v-model="url" type="url" class="form-control" placeholder="Video url">
                <input @click.prevent="store" type="submit" class="btn btn-primary" value="Add">
            </div>
        </div>
    </div>
</template>

<script setup>

import {ref} from "vue";
import axios from "axios";
import router from "../../routes";
import Swal from "sweetalert2";

let url = ref([]);
let title = ref([]);
let description = ref([]);

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

const store = () => {
    const data = new FormData();
    data.append('title', title.value);
    data.append('description', description.value);
    data.append('url', youtube_parser(url.value));
    title.value = '';
    description.value = '';
    url.value = '';

    axios.post('store/video', data).then(res => {
        router.push({name: 'portfolio'});
        if(res.data.message == 'success') {
            Toast.fire({
                icon: 'success',
                title: 'Video added successfully'
            })
        }
        else {
            Toast.fire({
                icon: 'error',
                title: 'Something went wrong'
            })
        }
    })
}

const youtube_parser = (url) => {
    let regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
    let match = url.match(regExp);
    return (match&&match[7].length==11)? match[7] : false;
}
</script>
