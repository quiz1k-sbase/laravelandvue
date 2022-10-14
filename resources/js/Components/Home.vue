<template>
    <nav class="navbar navbar-light navbar-expand-lg mb-5">
        <div class="container">
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav m-2">
                    <li class="nav-item me-2">
                        <button type="button" class="btn btn-danger" v-on:click="logout">Logout</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!--Add post-->
    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="d-flex col-lg-6 col-md-8 mx-auto flex-column flex-wrap align-content-center justify-content-center">
                    <label class="form-label">Input text</label><br>
                    <textarea class="form-control w-100" type="text" name="text" rows="3" id="text" v-model="form" ref="postText"></textarea>
                    <button class="btn btn-outline-primary w-100 mt-2" type="submit" id="submitButton" v-on:click="addPost">Add</button>
            </div>
        </div>
    </section>

    <div class="card">
        <div class="d-flex card-header justify-content-center">Posts:</div>
        <div class="card-body">
            <div class="row row-cols-1 g-3" id="all_comments">

                <Post
                v-for="post in posts"
                :key="post.id"
                v-bind:post_data="post"
                @deletePost="deletePost"
                />
            </div>
        </div>
    </div>

</template>

<script setup>
import {useRouter} from 'vue-router';
import {ref, onMounted} from "vue";
import axios from "axios";
import Post from "./Post.vue";
const router = useRouter();


// Variables for Posts

const posts = ref([]);

let form = ref([]);

// Variables for comments

const comments = ref([]);

let errors = ref('');

onMounted(() => {
    axios.get('posts').then(response => {
        posts.value = response.data;
    }).catch((error) => {
        console.log(error);
    });
})

// POSTS

const addPost = async () => {
    await axios.post('store', {text_en: form.value}).then(res => {
        posts.value = res.data.data;
        form.value = '';
    }).catch(e => {
        console.log(e);
        errors.value = e;
    });
};


const deletePost = (id) => {
    if (confirm('Do you want delete this post?')) {
        axios.delete(`delete/${id}`)
            .then(response => {
                posts.value.splice(posts.value.find(el => el.id === id), 1);
            }).catch((error) => {
            console.log(error);
        });
    }
}

const logout = () => {
    localStorage.removeItem('token');
    router.push({name: 'login'});
}
</script>

<style scoped>

</style>
