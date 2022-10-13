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
                v-if="comment_data.parent_id === comment_data.id"
            >
                <h1>Here is a sub-comment</h1>
                <CommentReply
                    v-bind:comment_data="comment_data"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import {defineProps} from "vue";
import moment from "moment";

const props = defineProps({
    comment_data: {
        type: Object,
        default() {
            return {}
        }
    }
})

console.log(props.comment_data)

function formatDate(date) {
    return moment(String(date)).format('DD MMMM YYYY HH:mm')
}

</script>

