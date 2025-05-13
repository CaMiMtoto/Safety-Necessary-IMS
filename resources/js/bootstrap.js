import 'bootstrap';
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.bootstrap = bootstrap;
import Swal from 'sweetalert2/dist/sweetalert2.js';
window.Swal = Swal;
