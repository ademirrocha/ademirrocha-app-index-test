
<style>
    .d-block{
        display: block;
    }

    .d-none{
        display: none;
    }

</style>
<html>
    <button onclick="getStatistics();">Statiscs -  DLS site</button>
    
    <button onclick="updateCounts();">Update User Counts</button>
    <button onclick="updateCounts('/sign/update');">Update Sign Counts</button>
    <button onclick="updateCounts('/review/update');">Update Review Counts</button>
    <button onclick="updateCounts('/reply/update');">Update Reply Counts</button>
    <button onclick="login();">Logar no site</button>
    <button onclick="deslogar();">Deslogar do site</button>

    <div id="result_back"></div>
    <div id="spinner"><img src="/images/spinning-loading.gif" alt="Carregando..." title="Carregando..."></div>

</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>

<script>
    var config = {
        headers: {
            'Accept': 'application/json', 
            'Content-Type':'application/x-www-form-urlencoded',
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Headers": "Authorization", 
            "Access-Control-Allow-Methods": "GET, POST, OPTIONS, PUT, PATCH, DELETE" ,
            "Content-Type": "application/json;charset=UTF-8"
            }
    };

    loading = false;

    loadSpinner = () => {
        if(loading){
            $('#result_back').removeClass('d-block').addClass('d-none');
            $('#spinner').removeClass('d-none').addClass('d-block');
        }else{
            $('#result_back').removeClass('d-none').addClass('d-block');
            $('#spinner').removeClass('d-block').addClass('d-none');
        }
    }

    const loadJSON = (callback) => {
        var xobj = new XMLHttpRequest();
        xobj.overrideMimeType("application/json");
        xobj.open('GET', 'environment.json', true);
        xobj.onreadystatechange = function () {
            if (xobj.readyState == 4 && xobj.status == "200") {
                // .open will NOT return a value but simply returns undefined in async mode so use a callback
                callback(xobj.responseText);
            }
        }
        xobj.send(null);
    }

    environment = {};
    loadJSON(function(response) {
        //Do Something with the response e.g.
        environment = JSON.parse(response);
    });

    let api = null;
    
    setTimeout(() => {
        api = axios.create({
            baseURL: environment.api_url
        });
        loadSpinner();
    }, 1000);
   

    async function getStatistics(){
        loading = true;
        loadSpinner();
        api.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
        await api.get('/site/statistics', {
            config
            })
        .then(
            function(response){
            document.getElementById('result_back').innerHTML =  "<pre>" + JSON.stringify(response.data, null, 2) + "</pre>";
            console.log(response.data);
            loading = false;
            loadSpinner();
        })
        .catch(error => {
            console.log(error.response);
            document.getElementById('result_back').innerHTML =  "<pre>" + JSON.stringify(error.response, null, 2) + "</pre>";
            loading = false;
            loadSpinner();
        }); 
    }


    async function updateCounts(path = '/user/update'){
        loading = true;
        loadSpinner();

        api.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
        await api.get('/counts' + path, {
            config
        })
        .then(
            function(response){
                document.getElementById('result_back').innerHTML =  "<pre>" + JSON.stringify(response.data, null, 2) + "</pre>";
                console.log(response.data);
                loading = false;
                loadSpinner();
        })
        .catch(error => {
            console.log(error.response);
            document.getElementById('result_back').innerHTML =  "<pre>" + JSON.stringify(error.response, null, 2) + "</pre>";
            loading = false;
            loadSpinner();
        }); 
    }


    async function login(){

        loading = true;
        loadSpinner();

        await api.post('/auth/login', {
            config,
            user: environment.email,
            password: environment.password
            })
        .then(
            function(response){
                document.getElementById('result_back').innerHTML =  "<pre>" + JSON.stringify(response.data, null, 2) + "</pre>";
                console.log(response.data);
                localStorage.setItem('accessToken', response.data.meta.token);
                loading = false;
                loadSpinner();
                
        })
        .catch(error => {
            console.log(error.response);
            document.getElementById('result_back').innerHTML =  "<pre>" + JSON.stringify(error.response, null, 2) + "</pre>";
            loading = false;
            loadSpinner();
        }); 
    }

    async function deslogar(){

        loading = true;
        loadSpinner();

        api.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
        await api.post('/auth/logout', {
            config
            })
        .then(
            function(response){
                document.getElementById('result_back').innerHTML =  "<pre>" + JSON.stringify(response.data, null, 2) + "</pre>";
                console.log(response.data);
                localStorage.removeItem('accessToken');
                loading = false;
                loadSpinner();
                
        })
        .catch(error => {
            console.log(error.response);
            document.getElementById('result_back').innerHTML =  "<pre>" + JSON.stringify(error.response, null, 2) + "</pre>";
            loading = false;
            loadSpinner();
        }); 
    }

    

</script>