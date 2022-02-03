<html>
    <button onclick="getStatistics();">Statiscs -  DLS site</button>
    <button onclick="login();">Logar no site</button>
    <button onclick="userCounts();">Update User Counts</button>

    <div id="result_back"></div>
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

    const api = axios.create({
        baseURL: environment.api_url
    });

    async function getStatistics(){
        api.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
        await api.get('/site/statistics', {
            config
            })
        .then(
            function(response){
            document.getElementById('result_back').innerHTML =  "<pre>" + JSON.stringify(response.data, null, 2) + "</pre>";
            console.log(response.data);
        })
        .catch(error => {
            console.log(error.response);
            document.getElementById('result_back').innerHTML =  "<pre>" + JSON.stringify(error.response, null, 2) + "</pre>";
        }); 
    }

    async function login(){

        console.log(environment)
        
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
                
        })
        .catch(error => {
            console.log(error.response);
            document.getElementById('result_back').innerHTML =  "<pre>" + JSON.stringify(error.response, null, 2) + "</pre>";
        }); 
    }

    


    async function userCounts(){

        api.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('accessToken');
        await api.get('/counts/user/update', {
            config
        })
        .then(
            function(response){
                document.getElementById('result_back').innerHTML =  "<pre>" + JSON.stringify(response.data, null, 2) + "</pre>";
                console.log(response.data);
        })
        .catch(error => {
            console.log(error.response);
            document.getElementById('result_back').innerHTML =  "<pre>" + JSON.stringify(error.response, null, 2) + "</pre>";
        }); 
    }



</script>