<template>

    <nav class="navbar navbar-light navbar-expand-lg mb-5">
        <div class="container">
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav m-2">
                    <li class="nav-item me-2">
                        <router-link to="user" type="button" class="btn btn-primary">User profile</router-link>
                    </li>
                    <li class="nav-item me-2">
                        <router-link to="home" type="button" class="btn btn-primary">Back home</router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="row">
        <div class="col-md-4">
            <router-link to="photos" class="btn btn-primary me-2">Add photo</router-link>
            <router-link to="videos" class="btn btn-primary">Add video link</router-link>
        </div>
    </div>

    <div class="card mt-5" v-if="photos">
        <div class="d-flex card-header justify-content-center">Output</div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <div class="col d-flex justify-content-center" v-for="photo in photos" :key="photo.id">
                    <button
                        class="btn"
                        data-bs-toggle="modal"
                        :data-bs-target="'#photo-' + photo.id"
                    >
                    <img :src="photo.preview_url" class="w-75 img-height">
                    </button>

                    <!-- Open photo description -->
                    <div class="modal fade modal-xl" :id="'photo-' + photo.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5
                                        class="modal-title"
                                        id="exampleModalLabel"
                                        contenteditable
                                        @input="updateContent($event, 'heading', photo.id)"
                                    >
                                        {{ photo.title }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Description</label><br>
                                    <p
                                        contenteditable
                                        @input="updateContent($event, 'paragraph', photo.id)"
                                    >
                                        {{ photo.description }}
                                    </p>
                                    <div class="d-flex justify-content-center">
                                        <img :src="photo.url">
                                    </div>
                                    <button
                                        class="btn btn-primary"
                                        data-bs-toggle="modal"
                                        :data-bs-target="'#changeImage-' + photo.id"
                                    >
                                        Change image
                                    </button>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" :id="'closePhotoDescription-' + photo.id">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Change image -->
                    <div class="modal fade modal-xl" :id="'changeImage-' + photo.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Change image</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeChange"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Upload file</label><br>
                                    <input
                                        class="form-control form-control-sm w-50 me-auto ms-auto"
                                        id="addProfilePhoto"
                                        type="file"
                                        name="addProfilePhoto"
                                        @change="addPhoto"
                                    >
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" :id="'closePhoto-' + photo.id">Close</button>
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        data-bs-toggle="modal"
                                        :data-bs-target="'#photo-' + photo.id"
                                    >
                                        Back
                                    </button>
                                    <button type="submit" class="btn btn-primary" @click="onUpload(photo.id)">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-5" v-if="videos">
        <div class="d-flex card-header justify-content-center">Output</div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <div class="col d-flex justify-content-center" v-for="video in videos" :key="video.id">
                    <button
                        class="btn"
                        data-bs-toggle="modal"
                        :data-bs-target="'#video-' + video.id"
                    >
                    <img :src="video.image_url" class="w-75 img-height">
                    </button>

                    <!-- Open video description -->
                    <div class="modal fade modal-xl" :id="'video-' + video.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5
                                        class="modal-title"
                                        contenteditable
                                        @input="updateVideoContent($event, 'heading', video.id)"
                                    >
                                        {{ video.title }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Description</label><br>
                                    <p
                                        contenteditable
                                        @input="updateVideoContent($event, 'paragraph', video.id)"
                                    >
                                        {{ video.description }}
                                    </p>
                                    <div class="d-flex justify-content-center">
                                        <iframe
                                            width="560"
                                            height="315"
                                            :src="'https://www.youtube.com/embed/' + video.url"
                                            title="YouTube video player"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media;
                                            gyroscope; picture-in-picture"
                                            allowfullscreen
                                        />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" :id="'closeVideoDescription-' + video.id">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>

<script setup>

import {onMounted, ref} from "vue";
import axios from "axios";

let user = ref([]);
let photos = ref([]);
let videos = ref([]);
const photo = ref([]);

const getData = () => {
    axios.get('userdata').then(response => {
        user.value = response.data.user;
        axios.get(`get/photos/${user.value.id}`).then(res => {
            photos.value = res.data.photos;
        })
    }).catch((error) => {
        console.log(error);
    });
}

const getVideoData = () => {
    axios.get('userdata').then(response => {
        user.value = response.data.user;
        axios.get(`get/videos/${user.value.id}`).then(res => {
            videos.value = res.data.videos;
        })
    }).catch((error) => {
        console.log(error);
    });
}

onMounted(() => {
    getData();
    getVideoData();
    console.log(videos.value)
})

const updateContent = (e, contentType, photoId) => {
    const inputText = e.target.innerText;
    switch (contentType) {
        case "heading":
            let title = photos.value.find(el => el.id === photoId).title;
            title = inputText;
            axios.post('update/photo/title', {id: photoId, title: title})
            break;
        case "paragraph":
            let description = photos.value.find(el => el.id === photoId).description;
            description = inputText;
            axios.post('update/photo/description', {id: photoId, description: description})
            break;
        default:
            break;
    }
}


const updateVideoContent = (e, contentType, videoId) => {
    const inputText = e.target.innerText;
    switch (contentType) {
        case "heading":
            let title = videos.value.find(el => el.id === videoId).title;
            title = inputText;
            axios.post('update/video/title', {id: videoId, title: title})
            break;
        case "paragraph":
            let description = videos.value.find(el => el.id === videoId).description;
            description = inputText;
            axios.post('update/video/description', {id: videoId, description: description})
            break;
        default:
            break;
    }
}

const addPhoto = async (event) => {
    photo.value = event.target.files[0];
}
const onUpload = async (id) => {
    const fd = new FormData();
    fd.append('image', photo.value, photo.value.name);
    fd.append('photo_id', id);
    await axios.post('update/photo/image', fd).then(res => {
        getData();
        document.getElementById('closePhoto-' + id).click();
    })
}
</script>
