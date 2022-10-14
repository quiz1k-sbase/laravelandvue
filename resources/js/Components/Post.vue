<template>
    <div class="col" v-bind:id="'post-' + post_data.id">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="card-text" v-bind:id="'changeText-' + post_data.id">{{ post_data.text_en }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="btn-group">
                            <small class="text-muted me-1">{{ post_data.username}}</small>
                        </div>
                        <small class="text-muted">{{ formatDate(post_data.created_at) }}</small>
                    </div>
                    <div>
                        <button
                            type="button" class="btn btn-primary me-2"
                            data-bs-toggle="modal"
                            :data-bs-target="'#addComment-' + post_data.id"
                        >
                            Add comment
                        </button>
                        <button
                            type="button" class="btn btn-warning me-2"
                            data-bs-toggle="modal"
                            :data-bs-target="'#editPost-' + post_data.id"
                        >
                            Edit
                        </button>
                        <button
                            type='button'
                            class='btn btn-danger'
                            @click="$emit('deletePost', post_data.id)"
                        >
                            Delete
                        </button>
                    </div>
                </div>
                <div class="container g-3" v-if="post_data.comment?.length">
                    <Comment
                        v-for="comment in post_data.comment"
                        :key="post_data.comment.id"
                        v-bind:comment_data="comment"
                        @deleteComment="deleteComment"
                    />
                </div>
            </div>
        </div>
    </div>


    <!-- Post edit -->
    <div class="modal fade" :id="'editPost-' + post_data.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Input new text</label><br>
                    <textarea class="form-control" type="text" name="editedPost" rows="3" v-model="updateText"></textarea><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" :id="'closeEditPost-' + post_data.id">Close</button>
                    <button type="submit" class="btn btn-primary" v-on:click="updatePost(post_data.id)">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add comment -->
    <div class="modal fade" :id="'addComment-' + post_data.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Input new text</label><br>
                    <textarea class="form-control" type="text" name="addComment" rows="3" v-model="addCommentForm" ></textarea><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" :id="'closeAddComment-' + post_data.id">Close</button>
                    <button type="submit" class="btn btn-primary" @click="addComment(post_data.id)">Add</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import {defineEmits, defineProps, onMounted, ref} from "vue";
import moment from "moment";
import Comment from "./Comment.vue";
import axios from "axios";


const emits = defineEmits(["sendPostId", "deletePost"])

const addCommentForm = ref([]);

const props = defineProps({
    post_data: {
        type: Object,
        default() {
            return {};
        }
    }
})

const post = ref(props.post_data);

const updateText = ref(post.value.text_en);

onMounted(() => {
})

const formatDate = (date) => {
    return moment(String(date)).format('DD MMMM YYYY HH:mm')
}

const deleteComment = (id) => {

    if (confirm('Do you want delete this comment?')) {
        axios.delete(`deleteComment/${id}`)
            .then(response => {
                let findElem = props.post_data.comment.find(el => el.id === id);
                let spliceElem = props.post_data.comment.indexOf(findElem);
                props.post_data.comment.splice(spliceElem, 1);
            }).catch((error) => {
            console.log(error);
        });
    }
}

const addComment = async (id) => {
    await axios.post('storeComment', {'post_id' : id, 'comment' : addCommentForm.value}).then(res => {
        post.value.comment = res.data.data.find(el => el.id === id).comment;
        addCommentForm.value = '';
        document.getElementById('closeAddComment-' + id).click();
    }).catch(e => {
        console.log(e);
    });
}

const updatePost = (id) =>
{
    axios.post(`update/${id}`, {text_en: updateText.value}).then(res => {
        post.value.text_en = res.data.data;
        document.getElementById('closeEditPost-' + id).click()
    }).catch(e => {
        console.log(e);
    });
}


</script>

<style scoped>

</style>
