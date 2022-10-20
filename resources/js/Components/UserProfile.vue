<template>
    <nav class="navbar navbar-light navbar-expand-lg mb-5">
        <div class="container">
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav m-2">
                    <li class="nav-item me-2">
                        <router-link to="home" type="button" class="btn btn-primary">Back home</router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="center-page w-75">

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img :src="user.image" alt="avatar"
                             class="rounded-circle img-fluid" style="width: 150px; height: 150px;">
                        <h5 class="my-3">{{ user.first_name + ' ' + user.last_name}}</h5>
                        <div class="mb-3">
                            <input
                                class="form-control form-control-sm w-50 me-auto ms-auto"
                                id="addProfilePhoto"
                                type="file"
                                name="addProfilePhoto"
                                @change="addPhoto"
                            >
                        </div>
                        <div class="d-flex justify-content-center mb-2">
                            <button class="btn btn-primary" id="profilePhoto" @click="onUpload">Add photo</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Your balance</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0" id="balance">${{ getBalance(user.balance)}}</p>
                                <p class="text-muted mb-0">
                                    <router-link to="balance">Donate</router-link>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Full Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0" id="name">{{ user.first_name + ' ' + user.last_name}}</p>
                                <p class="text-muted mb-0">
                                    <a class="link" data-bs-target='#changeName' data-bs-toggle='modal'
                                       id="changeNameButton">Change name</a>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0" id="email-text">{{ user.email }}</p>
                                <p class="text-muted mb-0">
                                    <a class="link" data-bs-target='#changeEmail' data-bs-toggle='modal'
                                       id="changeEmailButton">Change E-mail</a>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Phone</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ user.phone }}</p>
                                <p class="text-muted mb-0">
                                    <a class="link" data-bs-target='#changePhone' data-bs-toggle='modal'
                                       id="changePhoneButton">Change phone</a>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Password</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><a class="link" data-bs-target='#changePassword' data-bs-toggle='modal'
                                                              id="changePasswordButton">Change password</a></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Download data</p>
                            </div>
                            <div class="col-sm-9">
                                <a
                                    :href="url + user.id"
                                >
                                    <button class="btn btn-primary" @click="getExport(user.id)">Download</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Popup change email-->
    <div class="modal fade" id="changeEmail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Input old email</label><br>
                    <input
                        class="form-control"
                        type="email"
                        name="commentReply"
                        id="oldEmail"
                        v-model="changeEmailForm.oldEmail"
                    ><br>
                    <label class="form-label">Input new email</label><br>
                    <input
                        class="form-control"
                        type="email"
                        name="commentReply"
                        id="newEmail"
                        v-model="changeEmailForm.newEmail"
                    ><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModalChangeEmail">Close</button>
                    <button type="submit" class="btn btn-primary" @click="changeEmail">Change email</button>
                </div>
            </div>
        </div>
    </div>

    <!--Popup change name-->
    <div class="modal fade" id="changeName" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Input old First name</label><br>
                    <input
                        class="form-control"
                        type="text"
                        name="oldFirstName"
                        id="oldFirstName"
                        v-model="changeNameForm.oldFirstName"
                    ><br>
                    <label class="form-label">Input new First name</label><br>
                    <input
                        class="form-control"
                        type="text"
                        name="newFirstName"
                        id="newFirstName"
                        v-model="changeNameForm.newFirstName"
                    ><br>
                    <label class="form-label">Input old Last name</label><br>
                    <input
                        class="form-control"
                        type="text"
                        name="oldLastName"
                        id="oldLastName"
                        v-model="changeNameForm.oldLastName"
                    ><br>
                    <label class="form-label">Input new Last name</label><br>
                    <input
                        class="form-control"
                        type="text"
                        name="newLastName"
                        id="newLastName"
                        v-model="changeNameForm.newLastName"
                    ><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModalChangeName">Close</button>
                    <button type="submit" class="btn btn-primary" @click="changeName">Change name</button>
                </div>
            </div>
        </div>
    </div>

    <!--Popup change phone-->
    <div class="modal fade" id="changePhone" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change phone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Input old phone</label><br>
                    <input
                        class="form-control"
                        type="text"
                        name="commentReply"
                        id="oldPhone"
                        v-model="changePhoneForm.oldPhone"
                    ><br>
                    <label class="form-label">Input new phone</label><br>
                    <input
                        class="form-control"
                        type="text"
                        name="commentReply"
                        id="newPhone"
                        v-model="changePhoneForm.newPhone"
                    ><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModalChangePhone">Close</button>
                    <button type="submit" class="btn btn-primary" @click="changePhone">Change phone</button>
                </div>
            </div>
        </div>
    </div>

    <!--Popup change password-->
    <div class="modal fade" id="changePassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Input email</label><br>
                    <input
                        class="form-control"
                        type="email"
                        name="commentReply"
                        id="email"
                        v-model="changePasswordForm.email"
                    ><br>
                    <label class="form-label">Input new password</label><br>
                    <input
                        class="form-control"
                        type="password"
                        name="commentReply"
                        id="newPassword"
                        v-model="changePasswordForm.newPassword"
                    ><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModalChangePassword">Close</button>
                    <button type="submit" class="btn btn-primary" @click="changePassword">Change password</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue';
import axios from "axios";

const user = ref([]);
const changeNameForm = ref([]);
const changeEmailForm = ref([]);
const changePhoneForm = ref([]);
const changePasswordForm = ref([]);
const photo = ref([]);
let url = 'userDataExport/';

onMounted(() => {
    axios.get('userdata').then(response => {
        user.value = response.data.user;
    }).catch((error) => {
        console.log(error);
    });
})

const changeName = async () => {
    await axios.post('changeName', {
        oldFirstName: changeNameForm.value.oldFirstName,
        newFirstName: changeNameForm.value.newFirstName,
        oldLastName: changeNameForm.value.oldLastName,
        newLastName: changeNameForm.value.newLastName,
    }).then(res => {
        if (res.data.status === 200) {
            user.value.first_name = changeNameForm.value.newFirstName;
            user.value.last_name = changeNameForm.value.newLastName;
            changeNameForm.value.oldFirstName = '';
            changeNameForm.value.newFirstName = '';
            changeNameForm.value.oldLastName = '';
            changeNameForm.value.newLastName = '';
            document.getElementById('closeModalChangeName').click();
        }
        else {
            alert('Something went wrong');
            changeNameForm.value.oldFirstName = '';
            changeNameForm.value.newFirstName = '';
            changeNameForm.value.oldLastName = '';
            changeNameForm.value.newLastName = '';
            document.getElementById('closeModalChangeName').click();
        }
    })
}

const changeEmail = async () => {
    await axios.post('changeEmail', {
        oldEmail: changeEmailForm.value.oldEmail,
        newEmail: changeEmailForm.value.newEmail,
    }).then(res => {
        if (res.data.status === 200) {
            user.value.email = changeEmailForm.value.newEmail;
            changeEmailForm.value.oldEmail = '';
            changeEmailForm.value.newEmail = '';
            document.getElementById('closeModalChangeEmail').click();
        }
        else {
            alert('Something went wrong');
            changeEmailForm.value.oldEmail = '';
            changeEmailForm.value.newEmail = '';
            document.getElementById('closeModalChangeEmail').click();
        }
    })
}

const changePhone = async () => {
    await axios.post('changePhone', {
        oldPhone: changePhoneForm.value.oldPhone,
        newPhone: changePhoneForm.value.newPhone,
    }).then(res => {
        if (res.data.status === 200) {
            user.value.phone = changePhoneForm.value.newPhone;
            changePhoneForm.value.oldPhone = '';
            changePhoneForm.value.newPhone = '';
            document.getElementById('closeModalChangePhone').click();
        }
        else {
            alert('Something went wrong');
            changePhoneForm.value.oldPhone = '';
            changePhoneForm.value.newPhone = '';
            document.getElementById('closeModalChangePhone').click();
        }
    })
}

const changePassword = async () => {
    await axios.post('changePassword', {
        email: changePasswordForm.value.email,
        newPassword: changePasswordForm.value.newPassword,
    }).then(res => {
        if (res.data.status === 200) {
            user.value.password = changePasswordForm.value.newPassword;
            changePasswordForm.value.oldPassword = '';
            changePasswordForm.value.newPassword = '';
            document.getElementById('closeModalChangePassword').click();
        }
        else {
            alert('Something went wrong');
            changePasswordForm.value.oldPassword = '';
            changePasswordForm.value.newPassword = '';
            document.getElementById('closeModalChangePassword').click();
        }
    })
}

const addPhoto = async (event) => {
    photo.value = event.target.files[0];
}
const onUpload = async () => {
    const fd = new FormData();
    fd.append('image', photo.value, photo.value.name);
    await axios.post('addPhoto', fd).then(res => {
        console.log(res);
    })
}

const getBalance = (balance) => {
    return balance / (-100);
}

const getExport = async (id) => {
    url += id;
    /*await axios.get(url + id).then(res => {
        console.log(res);
    })*/
}
</script>
