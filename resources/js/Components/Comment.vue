<template>
        <div class="card w-75 mt-2" v-bind:id="'comment-' + comment_data.id" >
            <div class="card-body" id="commentBody">
                <p class="card-text" v-bind:id="'comment-text-' + comment_data.id">{{ comment_data.comment }}</p>
                <input type="hidden" id="post_id" v-bind:value="post_data.id">
                <small class="text-muted">{{ comment_data.username }}</small>
                <small class="text-muted ms-1 me-2">{{ formatDate(comment_data.created_at) }}</small>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#addCommentReply" v-on:click="getId(comment_data.id)"
                        v-bind:id="'addReply-' + comment_data.id">Reply</button>
                <button type="button" class="btn btn-warning ms-2 me-2" data-bs-toggle="modal"
                        data-bs-target="#editComment" @click="getId(comment_data.id)">Edit{{comment_data.id}}</button>
                <button type="button" class="btn btn-danger" v-on:click="deleteComment(comment_data.id)">Delete</button>
                <div class="container g-3" v-bind:id="'commentsReplyContainer-' + comment_data.id">
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
                    <textarea class="form-control" type="text" name="editedPost" rows="3" v-model="comment_data.comment"></textarea><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeEditComment">Close</button>
                    <button type="submit" class="btn btn-primary" @click="updateComment">Add</button>
                </div>
            </div>
        </div>
    </div>

</template>

<!--<script>
import moment from "moment";
import axios from "axios";
import {reactive, ref, defineProps} from "vue";

export default {
    name: "Comment",
    /*setup() {

        const props = defineProps({
            comment_data: {
                type: Object,
                default() {
                    return {};
                }
            },
            post_data: {
                type: Object,
                default() {
                    return {};
                }
            }
        })

        let globalId = null;
        let errors = ref('');

        //const emit = defineEmits(['update']);

        let updateCommentText = reactive({
            comment: ''
        });

        function getId(id) {
            globalId = id;

            console.log('id: ' + id + '\nGlobal id: ' + globalId);
        }

        function formatDate(date) {
            return moment(String(date)).format('DD MMMM YYYY HH:mm')
        }

        function updateComment()
        {
            console.log(updateCommentText)
            console.log('Update global id = ' + props.comment_data.id);
            axios.post(`updateComment/${globalId}`, {comment: this.updateCommentText.comment}).then(res => {
                document.getElementById('comment-text-' + globalId).innerHTML = this.updateCommentText.comment;
                updateCommentText.comment = '';
                document.getElementById('closeEditComment').click()
            }).catch(e => {
                console.log(e);
                errors.value = e;
            });
        };

        return {
            updateCommentText,
            getId,
            formatDate,
            updateComment,
            props,
        }
    },*/
    methods: {
        deletePost(id) {
            if (confirm('Do you want delete this post?')) {
                axios.delete(`delete/${id}`)
                    .then(response => {
                        document.getElementById('post-' + id).remove();
                    }).catch((error) => {
                    console.log(error);
                });
            }
        },
        deleteComment(id) {
            if (confirm('Do you want delete this comment?')) {
                axios.delete(`deleteComment/${id}`)
                    .then(response => {
                        document.getElementById('comment-' + id).remove();
                    }).catch((error) => {
                    console.log(error);
                });
            }
        },
    }
}
</script>-->

<script setup>
import {defineProps, onMounted, reactive, ref} from "vue";
import moment from "moment";
import axios from "axios";

const props = defineProps({
    comment_data: {
        type: Object,
        default() {
            return {};
        }
    },
    post_data: {
        type: Object,
        default() {
            return {};
        }
    }
})

onMounted(() => {
    console.log(props.comment_data)
})

let globalId = null;
let errors = ref('');

//const emit = defineEmits(['update']);



function getId(id) {
    globalId = id;

    console.log('id: ' + id + '\nGlobal id: ' + globalId);
}

function formatDate(date) {
    return moment(String(date)).format('DD MMMM YYYY HH:mm')
}

function updateComment()
{
    console.log(props.comment_data);
    /*console.log(updateCommentText.value)
    console.log('Update global id = ' + props.comment_data.id);
    axios.post(`updateComment/${props.comment_data.id}`, {comment: updateCommentText.value}).then(res => {
        //document.getElementById('comment-text-' + globalId).innerHTML = updateCommentText.value;
        props.comment_data.comment = updateCommentText.value;
        updateCommentText.value = '';
        document.getElementById('closeEditComment').click()
    }).catch(e => {
        console.log(e);
        errors.value = e;
    });*/
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
function deleteComment(id) {
    if (confirm('Do you want delete this comment?')) {
        axios.delete(`deleteComment/${id}`)
            .then(response => {
                document.getElementById('comment-' + id).remove();
            }).catch((error) => {
            console.log(error);
        });
    }
}

</script>

<style scoped>

</style>
