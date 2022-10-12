<template>
    <div class="col" v-bind:id="'post-' + post_data.id">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="card-text" v-bind:id="'changeText-' + post_data.id">{{ post_data.text_en }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <small class="text-muted me-2">{{ post_data.username}}</small>
                    </div>
                    <small class="text-muted">{{ formatDate(post_data.created_at) }}</small>
                    <button type="button" class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#addComment"
                            @click="$emit('sendPostId', post_data.id)"
                    >
                        Add comment
                    </button>
                    <button type='button' class='btn btn-warning'
                            data-bs-toggle='modal'
                            data-bs-target='#editPost'
                            @click="$emit('sendPostId', post_data.id)"
                    >
                        Edit
                    </button>
                    <button type='button' class='btn btn-danger' v-on:click="deletePost(post_data.id)">Delete</button>
                </div>
<!--                <div class="container g-3" v-if="comments?.length">

                    <div  v-for="comment in comments" :key="comment.id">
                        <Comment
                            v-if="post_data.id === comment.post_id && comment.parent_id == null"
                            :key="comment.id"
                            :comment_data="comment"
                            :post_data="post_data"
                        />
                    </div>
                </div>-->
            </div>
        </div>
    </div>

</template>

<!--<script setup>
import {defineProps, onMounted, reactive, ref} from "vue";
import moment from "moment";
import axios from "axios";
import Comment from './Comment.vue';
const props = defineProps({
    post_data: {
        type: Object,
        default() {
            return {}
        }
    },
    comments: {
        type: Object,
        default() {
            return {}
        }
    },
    counterOfComments: {
        type: Object,
        default() {
            return {}
        }
    }
})


const comments = ref(props.comments)

const post_data = ref(props.post_data)

let globalId;
let errors = ref('');

let updateText = reactive({
    text_en: ''
});

let formComment = reactive({
    comment: '',
    post_id: ''
});

function getId(id) {
    globalId = id;
}

function formatDate(date) {
    return moment(String(date)).format('DD MMMM YYYY HH:mm')
}


// POSTS

const updatePost = () =>
{
    axios.post(`update/${globalId}`, {text_en: updateText.text_en}).then(res => {
        document.getElementById('changeText-' + globalId).innerHTML = updateText.text_en;
        updateText.text_en = '';
        document.getElementById('closeEditPost').click()
    }).catch(e => {
        console.log(e);
        errors.value = e;
    });
}

const addComment = async () => {
    formComment.post_id = globalId;
    await axios.post('storeComment', formComment).then(res => {
        //$emit('sendComments', res.data.data);
        //this.comments.value = res.data.data;
        formComment.comment = '';
        document.getElementById('closeAddComment').click();
    }).catch(e => {
        console.log(e);
        errors.value = e;
    });
};


function deletePost(id) {
    if (confirm('Do you want delete this post?')) {
        axios.delete(`delete/${id}`)
            .then(response => {
                document.getElementById('post-' + id).remove();
            }).catch((error) => {
            console.log(error);
        });
    }
}
</script>-->

<script setup>
import {defineProps, onMounted, ref} from "vue";
import moment from "moment";
const props = defineProps({
    post_data: {
        type: Object,
        default() {
            return {};
        }
    }
})

function formatDate(date) {
    return moment(String(date)).format('DD MMMM YYYY HH:mm')
}
console.log(props.post_data)


</script>

<style scoped>

</style>
