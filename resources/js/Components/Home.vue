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
                    <textarea class="form-control w-100" type="text" name="text" rows="3" id="text" v-model="form.text_en" ref="postText"></textarea>
                    <button class="btn btn-outline-primary w-100 mt-2" type="submit" id="submitButton" v-on:click="addPost">Add</button>
            </div>
        </div>
    </section>

    <div class="card">
        <div class="d-flex card-header justify-content-center">Posts:</div>
        <div class="card-body">
            <div class="row row-cols-1 g-3" id="all_comments">
                <div class="col" v-for="post in posts" :key="post.id">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <p class="card-text" v-bind:id="'changeText-' + post.id">{{ post.text_en }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <small class="text-muted me-2">{{ post.username}}</small>
                                </div>
                                <small class="text-muted">{{ formatDate(post.created_at) }}</small>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addComment" v-on:click="getId(post.id)">
                                    Add comment
                                </button>
                                <button type='button' class='btn btn-warning' data-bs-toggle='modal'
                                        data-bs-target='#editPost' v-on:click="getId(post.id)">Edit</button>
                                <button type='button' class='btn btn-danger' v-on:click="deletePost(post.id)">Delete</button>
                            </div>
                           <div class="container g-3" v-if="counterOfComments > 0">
                               <template v-for="comment in comments" :key="comment.id">
                                    <div class="card w-75 mt-2" id="comment-" v-if="post.id == comment.post_id && comment.parent_id == null">
                                        <div class="card-body" id="commentBody">
                                            <p class="card-text" v-bind:id="'comment-text-' + comment.id">{{ comment.comment }}</p>
                                            <input type="hidden" id="post_id" v-bind:value="post.id">
                                            <small class="text-muted">{{ comment.username }}</small>
                                            <small class="text-muted ms-1 me-2">{{ formatDate(comment.created_at) }}</small>
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#addCommentReply" v-on:click="getId(comment.id)"
                                                    v-bind:id="'addReply-' + comment.id">Reply</button>
                                            <button type="button" class="btn btn-warning ms-2 me-2" data-bs-toggle="modal"
                                                    data-bs-target="#editComment" v-on:click="getId(comment.id)"
                                                    v-bind:id="'editComm-' + comment.id">Edit</button>
                                            <button type="button" class="btn btn-danger" v-on:click="deleteComment(comment.id)">Delete</button>
                                            <div class="container g-3" v-bind:id="'commentsReplyContainer-' + comment.id">
                                            </div>
                                        </div>
                                    </div>
                               </template>
                            </div>
                        </div>
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
                    <textarea class="form-control" type="text" name="editedPost" rows="3" v-model="updateCommentText.comment"></textarea><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeEditComment">Close</button>
                    <button type="submit" class="btn btn-primary" v-on:click="updateComment">Add</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Add comment -->
    <div class="modal fade" id="addCommentReply" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add comment reply</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Input new text</label><br>
                    <textarea class="form-control" type="text" name="addComment" rows="3" v-model="formCommentReply.comment"></textarea><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeAddCommentReply">Close</button>
                    <button type="submit" class="btn btn-primary" v-on:click="addCommentReply">Add</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {useRouter} from 'vue-router';
import {reactive, ref, onMounted} from "vue";
import {computed} from "vue";
import moment from 'moment';
import axios from "axios";
export default {
    name: "Home",
    setup() {
        const router = useRouter();

        let globalId;

        function getId(id) {
            globalId = id;
        }

        function formatDate(date) {
            return moment(String(date)).format('DD MMMM YYYY HH:mm')
        }

        // Variables for Posts

        const posts = ref([]);

        let form = reactive({
            text_en: ''
        });

        let updateText = reactive({
            text_en: ''
        });

        // Variables for comments

        const comments = ref([]);

        let formComment = reactive({
            comment: '',
            post_id: ''
        });

        let updateCommentText = reactive({
            comment: ''
        });

        let formCommentReply = reactive({
            comment: '',
            post_id: '',
            parent_id: ''
        });

        let errors = ref('');

        onMounted(() => {
            axios.get('posts').then(response => {
                posts.value = response.data;
            }).catch((error) => {
                console.log(error);
            });
            axios.get('comments').then(res => {
                comments.value = res.data;
            }).catch((error) => {
                console.log(error);
            });
        })

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

        const addPost = async () => {
            await axios.post('store', form).then(res => {
                posts.value = res.data.data;
                form.text_en = '';
            }).catch(e => {
                console.log(e);
                errors.value = e;
            });
        };

        // COMMENTS

        const addComment = async () => {
            formComment.post_id = globalId;
            await axios.post('storeComment', formComment).then(res => {
                comments.value = res.data.data;
                formComment.comment = '';
                document.getElementById('closeAddComment').click();
            }).catch(e => {
                console.log(e);
                errors.value = e;
            });
        };

        function updateComment()
        {
            axios.post(`updateComment/${globalId}`, {comment: this.updateCommentText.comment}).then(res => {
                document.getElementById('comment-text-' + globalId).innerHTML = this.updateCommentText.comment;
                updateCommentText.comment = '';
                document.getElementById('closeEditComment').click()
            }).catch(e => {
                console.log(e);
                errors.value = e;
            });
        };

        const addCommentReply = async () => {
            formCommentReply.parent_id = globalId;
            formCommentReply.post_id = $("#post_id").val();
            console.log(formCommentReply)
            await axios.post('storeCommentReply', formCommentReply).then(res => {
                comments.value = res.data.data;
                formCommentReply.comment = '';
                document.getElementById('closeAddCommentReply').click();
            }).catch(e => {
                console.log(e);
                errors.value = e;
            });
        };

        function logout() {
            localStorage.removeItem('token');
            router.push({name: 'login'});
        }

        let counterOfComments = computed(() => comments.value.length);

        return {
            form,
            posts,
            comments,
            updateText,
            updateCommentText,
            counterOfComments,
            formComment,
            formCommentReply,
            addPost,
            logout,
            updatePost,
            updateComment,
            getId,
            formatDate,
            addComment,
            addCommentReply,
        }
    },
    methods: {
        deletePost(id) {
            if (confirm('Do you want delete this post?')) {
                axios.delete(`delete/${id}`)
                    .then(response => {
                        let i = this.posts.map(item => item.id).indexOf(id); // find index of object
                        this.posts.splice(i, 1)
                    }).catch((error) => {
                    console.log(error);
                });
            }
        },
        deleteComment(id) {
            if (confirm('Do you want delete this comment?')) {
                axios.delete(`deleteComment/${id}`)
                    .then(response => {
                        let i = this.comments.map(item => item.id).indexOf(id); // find index of object
                        this.comments.splice(i, 1)
                    }).catch((error) => {
                    console.log(error);
                });
            }
        },
    }
}
</script>

<style scoped>

</style>
