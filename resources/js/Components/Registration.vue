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
                    <hr>
                    <form @submit.prevent="googleAuth">
                        <button class="btn btn-outline-primary w-100" type="submit" >
                            <img src="../../../storage/app/public/images/icons8-google.svg">
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import axios from "axios";
import {onBeforeUnmount, onMounted, reactive, ref} from "vue";
import { useRouter } from 'vue-router';

const router = useRouter();

let form = ref({
    username: '',
    email: '',
    firstName: '',
    lastName: '',
    phone: '',
    password: '',
    confirm_password: '',
    _token: window.appConfig.csrf
})

let errors = ref([]);

const register = async() => {
    console.log(form.value)
    await axios.post('register', {
        username: form.value.username,
        email: form.value.email,
        firstName: form.value.firstName,
        lastName: form.value.lastName,
        phone: form.value.phone,
        password: form.value.password,
        confirm_password: form.value.confirm_password,
    }).then(res => {
        if(res.data.success) {
            localStorage.setItem('token', res.data.data.token);
            router.push({name: 'home'});
        }
    }).catch(e => {
        errors.value = e.response.data.message;
    })
}

onMounted(() => {
    window.addEventListener('message', onMessage, false)
})

onBeforeUnmount(() => {
    window.removeEventListener('message', onMessage)
})
const googleAuth = async () => {
    const newWindow = openWindow('', 'message')
    await axios.get('auth/google').then(res => {
        newWindow.location.href = res.data.url;
    })
}

const onMessage = (e) => {
    if (e.origin !== window.origin || !e.data.token) {
        return
    }
    localStorage.setItem('user',e.data.name)
    localStorage.setItem('token',e.data.token)

    router.push({name: 'home'});
}

function openWindow (url, title, options = {}) {
    if (typeof url === 'object') {
        options = url
        url = ''
    }

    options = { url, title, width: 600, height: 720, ...options }

    const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screen.left
    const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screen.top
    const width = window.innerWidth || document.documentElement.clientWidth || window.screen.width
    const height = window.innerHeight || document.documentElement.clientHeight || window.screen.height

    options.left = ((width / 2) - (options.width / 2)) + dualScreenLeft
    options.top = ((height / 2) - (options.height / 2)) + dualScreenTop

    const optionsStr = Object.keys(options).reduce((acc, key) => {
        acc.push(`${key}=${options[key]}`)
        return acc
    }, []).join(',')

    const newWindow = window.open(url, title, optionsStr)

    if (window.focus) {
        newWindow.focus()
    }

    return newWindow
}

</script>
<style scoped>

</style>
