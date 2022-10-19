<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        You will be charged ${{ plan.price }} for {{ plan.name }} Plan
                    </div>

                    <div class="card-body">

                        <form id="payment-form">
                            <input type="hidden" name="plan" id="plan" :value="plan.id">

                            <div class="row mb-2">
                                <div class="col-xl-4 col-lg-4 w-100">
                                    <div class="form-group">
                                        <label class="mb-2">Name</label>
                                        <input type="text" name="name" id="card-holder-name" class="form-control" value="" placeholder="Name on the card">
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
                                    <button type="submit" class="btn btn-primary" id="card-button" :data-secret="intent.client_secret">Purchase</button>
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

const props = defineProps({
    plan: {
        type: Object,
        default() {
            return {};
        }
    }
})
const planData = ref([])
const intent = ref([])

const getPlanData = async (slug) => {
    await axios.get(`plansData/${slug}`).then(res => {
        planData.value = res.data.plan;
        intent.value = res.data.intent;
    })
}

const stripe = Stripe('pk_test_51LtqN6Dn7JPIapKMZ3vJyEZ4ToQ7ehAykj7Y3h3jtswlfyDYkLABiXECAQS2KRx2d1dEI3Ku4hakDHKTFLOIDbMT0079mQllBG')

const elements = stripe.elements()
const cardElement = elements.create('card')
onMounted(() => {
    getPlanData(props.plan.slug)

    cardElement.mount('#card-element')

    const form = document.getElementById('payment-form')
    const cardBtn = document.getElementById('card-button')
    const cardHolderName = document.getElementById('card-holder-name')

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
            token.setAttribute('name', 'token')
            token.setAttribute('value', setupIntent.payment_method)
            form.appendChild(token)
            const data = Object.fromEntries(new FormData(e.target).entries());
            axios.post('subscriptionCreate', data).then(res => {
                if (res.data.success === 200) {
                    document.getElementById('close-' + props.plan.slug).click();
                }
            })
        }
    })
})
</script>
