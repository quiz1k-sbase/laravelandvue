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
                    <button
                        type="button" class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#addComment"
                        @click="$emit('sendPostId', post_data.id)"
                    >
                        Add comment
                    </button>
                    <button
                        type='button' class='btn btn-warning'
                        data-bs-toggle='modal'
                        data-bs-target='#editPost'
                        @click="$emit('sendPostId', post_data.id)"
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
                <div class="container g-3" v-if="post_data.comment?.length">
                    <Comment
                        v-for="comment in post_data.comment"
                        :key="post_data.comment.id"
                        v-bind:comment_data="comment"
                        @deleteComment="deleteComment"
                        @readCommentId="readCommentId"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- Comment edit -->
        <div class="modal fade" id="editComment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit comment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Input new text</label><br>
                        <textarea class="form-control" type="text" name="editedPost" rows="3" v-model="updateCommentText"></textarea><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeEditComment">Close</button>
                        <button type="submit" class="btn btn-primary" @click="updateComment">Add</button>
                    </div>
                </div>
            </div>
        </div>

</template>

<script setup>
import {defineEmits, defineProps, ref} from "vue";
import moment from "moment";
import Comment from "./Comment.vue";
import axios from "axios";


const emits = defineEmits(["sendPostId", "deletePost"])

const updateCommentText = ref([]);

let comment_id = null;

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

const readCommentId = (data) => {
    return comment_id = data;
}

const updateComment = () => {
    console.log(updateCommentText.value);
    console.log(comment_id)
    axios.post(`updateComment/${comment_id}`, {comment: updateCommentText.value}).then(res => {
        let findElem = props.post_data.comment.find(el => el.id === comment_id);
        findElem.comment = updateCommentText.value;
        updateCommentText.value = '';
        document.getElementById('closeEditComment').click()
    }).catch(e => {
        console.log(e);
    });
};

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


</script>

<style scoped>

</style>
