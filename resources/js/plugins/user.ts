import _Vue from 'vue';
import User from '@/models/user';

export function UserPlugin(Vue: typeof _Vue): void {
    Vue.prototype.$user = new User();
}
