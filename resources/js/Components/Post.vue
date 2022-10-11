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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addComment" v-on:click="getId(post_data.id)">
                        Add comment
                    </button>
                    <button type='button' class='btn btn-warning' data-bs-toggle='modal'
                            data-bs-target='#editPost' v-on:click="getId(post_data.id)">Edit</button>
                    <button type='button' class='btn btn-danger' v-on:click="deletePost(post_data.id)">Delete</button>
                </div>
                <div class="container g-3" v-if="comments?.length">

                    <div  v-for="comment in comments" :key="comment.id">
                    <Comment
                        v-if="post_data.id === comment.post_id && comment.parent_id == null"
                        :comment_data="comment"
                        :post_data="post_data"
                    />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Post edit -->
    <div class="modal fade" id="editPost" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Input new text</label><br>
                    <textarea class="form-control" type="text" name="editedPost" rows="3" v-model="updateText.text_en"></textarea><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeEditPost">Close</button>
                    <button type="submit" class="btn btn-primary" v-on:click="updatePost">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add comment -->
    <div class="modal fade" id="addComment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Input new text</label><br>
                    <textarea class="form-control" type="text" name="addComment" rows="3" v-model="formComment.comment"></textarea><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeAddComment">Close</button>
                    <button type="submit" class="btn btn-primary" v-on:click="addComment">Add</button>
                </div>
            </div>
        </div>
    </div>


</template>

<!--<script>
import moment from "moment";
import axios from "axios";
import Comment from "./Comment.vue";
import {computed, onMounted, reactive, ref} from "vue";

export default {
    name: "Post",
    components: {Comment},
    props: {
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
    },
    created() {
        console.log(this.comments);
    },
    setup() {

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

        function updatePost()
        {
            axios.post(`update/${globalId}`, {text_en: this.updateText.text_en}).then(res => {
                document.getElementById('changeText-' + globalId).innerHTML = this.updateText.text_en;
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
                this.$emit('sendComments', res.data.data);
                //this.comments.value = res.data.data;
                formComment.comment = '';
                document.getElementById('closeAddComment').click();
            }).catch(e => {
                console.log(e);
                errors.value = e;
            });
        };


        return {
            updateText,
            formComment,
            getId,
            formatDate,
            updatePost,
            addComment,
        }
    },
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
        checkComment() {
            let temp = [];
            this.comments.forEach((value) => {
                if (this.post_data.id === value.post_id && value.parent_id)
                {
                    temp.push(value);
                }
            })
            return temp;
        }
    }
}
</script>-->

<script setup>
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
</script>

<style scoped>

</style>
