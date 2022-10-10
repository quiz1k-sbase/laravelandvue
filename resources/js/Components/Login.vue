<template>
    <div class="row justify-content-center center-page w-100">
        <div class="col-md-4">
            <p class="text-danger" v-if="error">{{ error }}</p>
            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <form @submit.prevent="login">
                        <div class="form-group mb-3">
                            <input class="form-control" type="text" name="email" placeholder="Email" id="email" v-model="form.email">
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="password" name="password" placeholder="Password" id="password" v-model="form.password">
                        </div>
                        <button class="btn btn-outline-primary w-100" type="submit">Login</button>
                        <div class="col-md-12 d-flex justify-content-evenly">
                            <a class="link-primary" href="/registration">Registration</a>
                            <a class="link-primary" href="/forgot-password">Reset password</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { reactive, ref } from "vue";
import { useRouter } from 'vue-router';
export default {
    name: "Login",
    components: {},
    setup() {
        const router = useRouter();

        let form = reactive({
            email: '',
            password: ''
        });

        let error = ref('');

        const login = async() => {
            await axios.post('/login', form).then(res => {
                if(res.data.success) {
                    localStorage.setItem('token', res.data.data.token);
                    router.push({name: 'home'});
                } else {
                    error.value = res.data.message;
                }
            })
        }

        return {
            form,
            login,
            error
        }
    }
}
</script>

<style scoped>

</style>
