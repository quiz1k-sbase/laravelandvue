<template>
    <div>
        <h3 class="text-center">Add Post</h3>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="addPost">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" id="text" v-model="form.text_en">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Post</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {reactive, ref} from "vue";
import {useRouter} from "vue-router";
import axios from "axios";

export default {
    setup() {
        const router = useRouter();

        let form = reactive({
            text_en: ''
        });

        let errors = ref('');

        const addPost = async () => {
            await axios.post('store', form).then(res => {
                router.push({name: 'home'});
            }).catch(e => {
                errors.value = e.response.data.message;
            });
        };

        return {
            form,
            addPost,
            errors
        }

    },

    /*data() {
        return {
            post: {}
        }
    },
    methods: {
        addPost() {

            this.axios
                .post('api/post/store', this.post)
                .then(response => (
                    this.$router.push({name: 'home'})
                    // console.log(response.data)
                ))
                .catch(error => console.log(error))
                .finally(() => this.loading = false)
        }
    }*/
}
</script>
