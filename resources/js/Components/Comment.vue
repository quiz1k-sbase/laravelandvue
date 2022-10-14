<template>
    <div class="card w-75 mt-2" id="comments" v-if="comment_data.parent_id === null">
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
                        class="btn btn-warning me-2"
                        data-bs-toggle="modal"
                        :data-bs-target="'#addCommentReply-' + comment_data.id"
                    >
                        Reply
                    </button>
                    <button
                        type="button"
                        class="btn btn-warning me-2"
                        data-bs-toggle="modal"
                        :data-bs-target="'#editComment-' + comment_data.id"
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
                :id="'replyComments-' + comment_data.id"
            >
                <teleport v-if="isLoaded" :to="'#replyComments-' + comment_data.id">
                    <CommentReply
                        v-for="comment in comment_data.replies"
                        :key="comment_data.id"
                        :comment_data="comment"
                        @deleteComment="deleteComment"
                    />
                </teleport>
            </div>
        </div>
    </div>

    <!-- Add comment reply -->
    <div class="modal fade" :id="'addCommentReply-' + comment_data.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" :id="'closeAddCommentReply-' + comment_data.id">Close</button>
                    <button type="submit" class="btn btn-primary" v-on:click="addCommentReply(comment_data.id)">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Comment edit -->
    <div class="modal fade" :id="'editComment-' + comment_data.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" :id="'closeEditComment-' + comment_data.id">Close</button>
                    <button type="submit" class="btn btn-primary" @click="updateComment(comment_data.id)">Add</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import {defineProps, defineEmits, ref, onMounted} from "vue";
import moment from "moment";
import axios from "axios";
import CommentReply from './CommentReply.vue';
import Comment from './Comment.vue';


const emits = defineEmits(["readCommentId", "deleteComment"])

const commentReply = ref([]);

const updateCommentReplyText = ref([]);

const isLoaded = ref(false);

const props = defineProps({
    comment_data: {
        type: Object,
        default() {
            return {}
        }
    }
})

onMounted(() => {
    isLoaded.value = true;
})
const updateCommentText = ref(props.comment_data.comment);

const updateComment = (id) => {
    axios.post(`updateComment/${id}`, {comment: updateCommentText.value}).then(res => {
        props.comment_data.comment = updateCommentText.value;
        document.getElementById('closeEditComment-' + id).click()
    }).catch(e => {
        console.log(e);
    });
};
const addCommentReply = async (id) => {
    console.log(id)
    let findElem = props.comment_data
    await axios.post('storeCommentReply', {'post_id': findElem.post_id, 'parent_id': id, 'comment': commentReply.value}).then(res => {

        let elem = res.data.data.find(el => el.id === id).replies;
        props.comment_data.replies = elem;
        console.log(props.comment_data)
        commentReply.value = '';
        document.getElementById('closeAddCommentReply-' + id).click();
    }).catch(e => {
        console.log(e);
    });
};

const updateCommentReply = (id) => {
    axios.post(`updateComment/${id}`, {comment: updateCommentReplyText.value}).then(res => {
        let findElem = props.comment_data.replies.find(el => el.id === replyId);
        findElem.comment = updateCommentReplyText.value;
        updateCommentReplyText.value = '';
        document.getElementById('closeEditCommentReply').click()
    }).catch(e => {
        console.log(e);
    });
};

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

const formatDate = (date) => {
    return moment(String(date)).format('DD MMMM YYYY HH:mm')
}

</script>
