import axios from 'axios';
import config from '@/config';

axios.defaults.baseURL = config.endpoints.baseURL;
axios.defaults.headers.common.Authorization = '';
export default axios;
