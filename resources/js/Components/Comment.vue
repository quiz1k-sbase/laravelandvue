<template>
    <div class="card w-75 mt-2" v-bind:id="'comment-' + comment_data.id">
        <div class="card-body" id="commentBody">
            <p class="card-text" v-bind:id="'comment-text-' + comment_data.id">{{ comment_data.comment }}</p>
            <small class="text-muted">{{ comment_data.username }}</small>
            <small class="text-muted ms-1 me-2">{{ formatDate(comment_data.created_at) }}</small>
            <button
                type="button"
                class="btn btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#addCommentReply"
                @click="getCommentReplyId(comment_data.id)"
            >
                Reply
            </button>
            <button
                type="button"
                class="btn btn-warning ms-2 me-2"
                data-bs-toggle="modal"
                data-bs-target="#editComment"
                @click="$emit('readCommentId', comment_data.id)"
            >
                Edit
            </button>
            <button type="button"
                    class="btn btn-danger"
                    @click="$emit('deleteComment', comment_data.id)"
            >
                Delete
            </button>
            <div
                class="container g-3"
                v-bind:id="'commentsReplyContainer-' + comment_data.id"
                v-if="comment_data.parent_id != null"
            >
                <CommentReply
                    :comment_data="comment_data"
                />
            </div>
        </div>
    </div>

    <!-- Add comment reply -->
    <div class="modal fade" id="addCommentReply" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add comment reply</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Input new text</label><br>
                    <textarea class="form-control" type="text" name="addComment" rows="3" v-model="commentReply"></textarea><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeAddCommentReply">Close</button>
                    <button type="submit" class="btn btn-primary" v-on:click="addCommentReply">Add</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {defineProps, defineEmits, ref} from "vue";
import moment from "moment";
import axios from "axios";
import CommentReply from './CommentReply.vue';

let replyId = null;

const emits = defineEmits(["readCommentId", "deleteComment"])

const commentReply = ref([]);

const props = defineProps({
    comment_data: {
        type: Object,
        default() {
            return {}
        }
    }
})

const getCommentReplyId = (data) => {
    return replyId = data;
}

const addCommentReply = async () => {

    let findElem = props.comment_data
    console.log(findElem.post_id)
    await axios.post('storeCommentReply', {'post_id': findElem.post_id, 'parent_id': replyId, 'comment': commentReply.value}).then(res => {

        console.log(commentReply.value)
        console.log(replyId)
        //comments.value = res.data.data;
        commentReply.value = '';
        document.getElementById('closeAddCommentReply').click();
    }).catch(e => {
        console.log(e);
    });
};

function formatDate(date) {
    return moment(String(date)).format('DD MMMM YYYY HH:mm')
}

</script>
