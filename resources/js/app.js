import './bootstrap';

import Alpine from 'alpinejs';

import Swal from 'sweetalert2'
window.Swal = Swal;
import axios from 'axios';
window.axios = axios;

window.Alpine = Alpine;

Alpine.start();
