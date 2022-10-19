<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Select Plane:</div>

                    <div class="card-body">

                        <div
                            class="row"
                            v-for="plan in plans"
                            :key="plan.id"
                        >
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        ${{ plan.price }}/Mo
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ plan.name }}</h5>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>

                                        <a
                                            data-bs-toggle="modal"
                                            :data-bs-target="'#' + plan.slug"
                                            class="btn btn-primary pull-right"
                                        >
                                            Choose
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Popup payment -->
                            <div class="modal modal-lg fade" :id="plan.slug" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Payment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" :id="'close-' + plan.slug"></button>

                                        </div>
                                        <div class="modal-body">
                                            <Subscription
                                                :plan="plan"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script setup>
import {onMounted, ref} from "vue";
import axios from "axios";
import { useRouter } from 'vue-router';
import Subscription from './Subscription.vue'

const router = useRouter();
const plans = ref([]);

onMounted(() => {
    axios.get('plansData').then(res => {
        plans.value = res.data.plans
    })
})

</script>

