import axios from "axios";

window.axios = axios;

axios.defaults.headers.common["X-CSRF-TOKEN"] = Veles.csrfToken;
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
