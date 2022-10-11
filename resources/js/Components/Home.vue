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
                <Post
                v-for="post in posts"
                :key="post.id"
                v-bind:post_data="post"
                v-bind:comments="comments"
                v-bind:counter-of-comments="counterOfComments">
                </Post>
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
import axios from "axios";
import Post from "./Post.vue";
import Comment from "./Comment.vue";
export default {
    name: "Home",
    components: {Comment, Post},
    setup() {
        const router = useRouter();


        // Variables for Posts

        const posts = ref([]);

        let form = reactive({
            text_en: ''
        });

        // Variables for comments

        const comments = ref([]);

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
            counterOfComments,
            formCommentReply,
            addPost,
            logout,
            addCommentReply,
        }
    },
    methods: {
        getComment(data) {
            this.comments.value = data;
        }
    }
}
</script>

<style scoped>

</style>
