<template>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Donate
                    </div>

                    <div class="card-body">

                        <form id="payment-form">

                            <div class="row mb-2">
                                <div class="col-xl-4 col-lg-4 w-100">
                                    <div class="form-group mb-2">
                                        <label class="mb-2">Name</label>
                                        <input type="text" name="name" id="card-holder-name" class="form-control" value="" placeholder="Name on the card">
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-2">Price</label>
                                        <input type="number" name="amount" id="card-price" class="form-control" value="5" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-xl-4 col-lg-4 w-100">
                                    <div class="form-group">
                                        <label class="mb-2">Card details</label>
                                        <div id="card-element"></div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12">
                                    <hr>
                                    <button type="submit" name="_token" class="btn btn-primary" id="card-button" :data-secret="intent.client_secret">Purchase</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {defineProps, onMounted, ref} from "vue";
import axios from "axios";
import { useRouter } from 'vue-router';

const router = useRouter();

const props = defineProps({
    plan: {
        type: Object,
        default() {
            return {};
        }
    }
})
const intent = ref([])

const stripe = Stripe('pk_test_51LtqN6Dn7JPIapKMZ3vJyEZ4ToQ7ehAykj7Y3h3jtswlfyDYkLABiXECAQS2KRx2d1dEI3Ku4hakDHKTFLOIDbMT0079mQllBG')

const elements = stripe.elements()
const cardElement = elements.create('card')
onMounted(() => {
    //getPlanData(props.plan.slug)

    axios.get('getToken').then(res => {
        intent.value = res.data.intent;
    })

    cardElement.mount('#card-element')

    const form = document.getElementById('payment-form')
    const cardBtn = document.getElementById('card-button')
    const cardHolderName = document.getElementById('card-holder-name')
    const cardPrice = document.getElementById('card-price')

    form.addEventListener('submit', async (e) => {
        e.preventDefault()

        cardBtn.disabled = true
        const {setupIntent, error} = await stripe.confirmCardSetup(
            cardBtn.dataset.secret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: cardHolderName.value
                    }
                }
            }
        )

        if (error) {
            cardBtn.disable = false
        } else {
            let token = document.createElement('input')
            token.setAttribute('type', 'hidden')
            token.setAttribute('name', 'payment_method')
            token.setAttribute('value', setupIntent.payment_method)
            form.appendChild(token)
            const data = Object.fromEntries(new FormData(e.target).entries());
            axios.post('donate', data).then(res => {
                if (res.data.success === 200) {
                    router.push({name: 'user'});
                } else {
                    alert('Something went wrong');
                }
            })
        }
    })
})



</script>
