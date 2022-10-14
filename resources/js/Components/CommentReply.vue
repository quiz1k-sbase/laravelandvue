<template>
    <div class="card w-75 mt-2" v-bind:id="'comment-' + comment_data.id">
        <div class="card-body" id="commentBody">
            <p class="card-text" v-bind:id="'comment-text-' + comment_data.id">{{ comment_data.comment }}</p>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted me-1">{{ comment_data.user.username }}</small>
                    <small class="text-muted">{{ formatDate(comment_data.created_at) }}</small>
                </div>
                <div>
                    <button
                        type="button"
                        class="btn btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#addCommentReply"
                        @click="$emit('getCommentReplyId', comment_data.id)"
                    >
                        Reply
                    </button>
                    <button
                        type="button"
                        class="btn btn-warning ms-2 me-2"
                        data-bs-toggle="modal"
                        data-bs-target="#editCommentReply"
                        @click="$emit('getCommentReplyId', comment_data.id)"
                    >
                        Edit
                    </button>
                    <button type="button"
                            class="btn btn-danger"
                            @click="$emit('deleteComment', comment_data.id)"
                    >
                        Delete
                    </button>
                </div>
            </div>
            <div
                class="container g-3"
                id="commentsReplyContainer"
                v-if="comment_data.replies.length"
            >
                <teleport v-if="isLoaded" to="#commentsReplyContainer">
                    <CommentReply
                        v-for="comment in comment_data.replies"
                        :key="comment_data.id"
                        v-bind:comment_data="comment"
                        @getCommentReplyId="getCommentReplyId"
                        @deleteComment="deleteComment"
                        @readCommentId="readCommentId"
                    />
                </teleport>
            </div>
        </div>
    </div>
</template>

<script setup>
import {defineEmits, defineProps, onMounted, ref} from "vue";
import moment from "moment";
import axios from "axios";
import CommentReply from "./CommentReply.vue"

const props = defineProps({
    comment_data: {
        type: Object,
        default() {
            return {}
        }
    }
})

let replyId = null;

const emits = defineEmits(["readCommentId", "deleteComment", "getCommentReplyId"])

const commentReply = ref([]);

const updateCommentReplyText = ref([]);

//console.log(props.comment_data)

const isLoaded = ref(false);

onMounted(() => {
    isLoaded.value = true;
})


const deleteComment = (id) => {
    if (confirm('Do you want delete this comment?')) {
        axios.delete(`deleteComment/${id}`)
            .then(response => {
                let findElem = props.comment_data.replies.find(el => el.id === id)
                let spliceElem = props.comment_data.replies.indexOf(findElem);
                props.comment_data.replies.splice(spliceElem, 1)
            }).catch((error) => {
            console.log(error);
        });
    }
}

const readCommentId = (data) => {
    emits('readCommentId', data);
}

const updateCommentReply = () => {
    console.log(replyId);
    axios.post(`updateComment/${replyId}`, {comment: updateCommentReplyText.value}).then(res => {
        let findElem = props.comment_data.replies.find(el => el.id === replyId);
        findElem.comment = updateCommentReplyText.value;
        updateCommentReplyText.value = '';
        document.getElementById('closeEditCommentReply').click()
    }).catch(e => {
        console.log(e);
    });
};

const formatDate = (date) => {
    return moment(String(date)).format('DD MMMM YYYY HH:mm')
}

</script>

