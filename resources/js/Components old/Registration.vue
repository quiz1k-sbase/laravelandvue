<template>
    <div class="row justify-content-center center-page w-100">
        <div class="col-md-4">
            <p class="text-danger" v-for="error in errors" :key="error">
                <span v-for="err in error" :key="err">{{ err }}</span>
            </p>
            <div class="card">
                <div class="card-header">Registration</div>
                <div class="card-body">
                    <form @submit.prevent="register">
                        <div class="form-group mb-3">
                            <input class="form-control" type="text" name="username" placeholder="Username" v-model="form.username">
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="text" name="email" placeholder="Email" v-model="form.email">
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="text" name="first_name" placeholder="First name" v-model="form.firstName">
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="text" name="last_name" placeholder="Last name" v-model="form.lastName">
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="text" name="phone" placeholder="Phone number" v-model="form.phone">
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="password" name="password" placeholder="Password" v-model="form.password">
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="password" name="confirm_password" placeholder="Confirm password" v-model="form.confirm_password">
                            <span class="text-danger"></span>
                        </div>
                        <button class="btn btn-outline-primary w-100" type="submit" >Register me</button>

                        <div class="col-md-12 d-flex justify-content-center">
                            <a class="link-primary" href="/login">Have you got an account? Sign in!</a>
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
    name: "Registration",
    components: {},
    setup() {
        const router = useRouter();

        let form = reactive({
            username: '',
            email: '',
            firstName: '',
            lastName: '',
            phone: '',
            password: '',
            confirm_password: ''
        })

        let errors = ref([]);

        const register = async() => {
            await axios.post('register', form).then(res => {
                if(res.data.success) {
                    localStorage.setItem('token', res.data.data.token);
                    router.push({name: 'home'});
                }
            }).catch(e => {
                errors.value = e.response.data.message;
            })
        }

        return {
            form,
            register,
            errors
        }
    }
}
</script>

<style scoped>

</style>
