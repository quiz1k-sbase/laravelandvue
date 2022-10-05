import { createApp} from "vue";
import Welcome from "./Welcome.vue";
import Registration from "./Registration.vue";

createApp(Welcome).mount('#app');
createApp(Registration).mount('#registration');
