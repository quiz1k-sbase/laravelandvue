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
                    <hr>
                    <form @submit.prevent="googleAuth">
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-outline-primary w-100">
                                    <img src="../../../public/images/icons8-google.svg">
                                </button>

                            </div>
                        </div>
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
    email: '',
    password: '',
    _token: window.appConfig.csrf
});

let error = ref('');

const login = async() => {
    await axios.post('/login', {
        email: form.value.email,
        password: form.value.password,
    }).then(res => {
        if(res.data.success) {
            localStorage.setItem('token', res.data.data.token);
            router.push({name: 'home'});
        } else {
            error.value = res.data.message;
        }
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
