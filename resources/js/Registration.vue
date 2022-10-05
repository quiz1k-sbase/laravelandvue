<template>
    <div class="row justify-content-center center-page w-100">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Registration</div>
                <div class="card-body">
                        <div class="form-group mb-3">
                            <input class="form-control" type="text" name="username" placeholder="Username" v-model="username">
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="text" name="email" placeholder="Email" v-model="email">
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="text" name="first_name" placeholder="First name" v-model="firstName">
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="text" name="last_name" placeholder="Last name" v-model="lastName">
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="text" name="phone" onsubmit="return checkForm()" placeholder="Phone number" v-model="phone">
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="password" name="password" placeholder="Password" v-model="password">
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="password" name="confirm_password" placeholder="Confirm password">
                            <span class="text-danger"></span>
                        </div>
                        <button class="btn btn-outline-primary w-100" type="submit" v-on:click="registration()">Register me</button>

                    <div class="col-md-12 d-flex justify-content-center">
                        <a class="link-primary" href="">Have you got an account? Sign in!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    name: "Registration",
    data()
    {
        return {
            username: '',
            email: '',
            firstName: '',
            lastName: '',
            phone: '',
            password: ''

        };
    },
    methods: {
        async registration()
        {
            let result = await axios.post("api/users", {
               username: this.username,
               email: this.email,
               firstName: this.firstName,
               lastName: this.lastName,
               phone: this.phone,
               password: this.password
            });
        }
    }
}
</script>

<style scoped>

</style>
