@extends('layout')
  
@section('content')
<main class="login-form">
  <div class="cotainer">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Login</div>
                  <div class="card-body">
  
                      <form id="login-form">
                          
                          <div class="form-group row">
                              <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                              <div class="col-md-6">
                                  <input type="text" id="email" class="form-control" name="email" required autofocus>
                                  @if ($errors->has('email'))
                                      <span class="text-danger">{{ $errors->first('email') }}</span>
                                  @endif
                              </div>
                          </div>
  
                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                              <div class="col-md-6">
                                  <input type="password" id="password" class="form-control" name="password" required>
                                  @if ($errors->has('password'))
                                      <span class="text-danger">{{ $errors->first('password') }}</span>
                                  @endif
                              </div>
                          </div>
  
                          <div class="form-group row">
                              <div class="col-md-6 offset-md-4">
                                  <div class="checkbox">
                                      <label>
                                          <input type="checkbox" name="remember"> Remember Me
                                      </label>
                                  </div>
                              </div>
                          </div>
  
                          <div class="col-md-6 offset-md-4">
                              <button type="submit" class="btn btn-primary">
                                  Login
                              </button>
                          </div>
                      </form>
                        
                  </div>
              </div>
          </div>        

      </div>      

          <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card">
                    <div class="çard-header">
                        Total Followers Last 30 Days
                    </div>
                    <div id="totRevenue" class="card-body">

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="çard-header">
                        Total Revenue
                    </div>
                    <div id="totIncome" class="card-body">

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="çard-header">
                        Top Sales
                    </div>
                    <div id="topSales" class="card-body">

                    </div>
                </div>
            </div>
          </div>

          <div class="card">
                <div class="card-header">Protected Content</div>
                <div id="protected-content" class="card-body">

                </div>
          </div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
        const loginForm = document.getElementById('login-form');
        const protectedContent = document.getElementById('protected-content');
        const totalIncome = document.getElementById('totIncome');
        var pageNum = 1;
        var endOfRecs = false;
        var totRevenue = 0;
        var subsRevenue = 0;

        const table = document.createElement('table');
                            table.innerHTML = `<thead>
                        <tr>
                            <th>Event Description</th>                        
                            <th>Action</th>                        
                        </tr>
                    </thead>
                    <tbody></tbody>`;

        const tbody = table.querySelector('tbody');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;            

            try {
                const response = await axios.post('api/login', {
                    email: email,
                    password: password
                });

                const token = response.data.token;
                // Store the token in localStorage or a secure storage method
                localStorage.setItem('token', token);

                // Hide the login form and show the protected content
                loginForm.style.display = 'none';
                protectedContent.innerHTML = '<p>You are logged in!</p>';

                // Attach the token to all future Axios requests
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                pageNum = 1;
                displayTotalFollowers();
                displayTopSales();
                /*subsRevenue = getSubscriptionRevenue();
                totalIncome.innerHTML = subsRevenue;*/
                //getDonationRevenue();
                // Chain the execution of the functions
                getDonationRevenue()
                    .then(() => {
                        // After getDonationRevenue is finished, execute getSalesRevenue
                        return getSalesRevenue();
                    })
                    .then(() => {
                        // After getSalesRevenue is finished, execute getSubscriptionRevenue
                        return getSubscriptionRevenue();
                    })
                    .then(() => {
                        // All three functions have completed
                        console.log('All functions have completed.'+ totRevenue);
                        totalIncome.innerHTML = totRevenue;
                    })
                    .catch(error => {
                        // Handle any errors that occurred in any of the functions
                        console.error('An error occurred:', error);
                    });
                displayEventList(pageNum);
            } catch (error) {
                console.error('Login failed:', error);
            }
        });

        function isScrolledToBottom() {
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            const scrollTop = window.scrollY || window.pageYOffset;

            // Check if the user is close to the bottom of the page (you can adjust the threshold)
            return scrollTop + windowHeight >= documentHeight - 100;
        }

        // Function to be called when the user scrolls to the bottom
        function onScrollToBottom() {
            // Your code to handle scroll to bottom here
            console.log('Page count '+pageNum+'Scrolled to the bottom of the page!');
            // Call your JavaScript function here
            displayEventList(pageNum);
        }
        // Attach a scroll event listener to the window
        window.addEventListener('scroll', function() {
            if(!endOfRecs){
                if (isScrolledToBottom()) {
                    pageNum++;
                    displayEventList(pageNum);
                    //onScrollToBottom();
                }
            }
            
        });

        function displayTotalFollowers(){
            // Check if a token is already stored (e.g., user is logged in)
            const storedToken = localStorage.getItem('token');
            if (storedToken) {
                // Attach the token to all Axios requests
                axios.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`;
                const followersBlock = document.getElementById('totRevenue');
                //console.log(storedToken);
                // Optionally, fetch and display protected content                

                axios.get('/api/followers')
                    .then(response => {
                        //console.log(response.data[0]['followercount']);
                        followersBlock.innerHTML = response.data[0]['followercount'];                                               
                    })
                    .catch(error => {
                        console.error('Error fetching protected content:', error);
                    });
            }
        }

        function getDonationRevenue(){
            // Check if a token is already stored (e.g., user is logged in)
            const storedToken = localStorage.getItem('token');
            if (storedToken) {
                // Attach the token to all Axios requests
                axios.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`;
                const followersBlock = document.getElementById('totRevenue');
                //console.log(storedToken);
                // Optionally, fetch and display protected content                

                return axios.get('/api/donationrevenue')
                    .then(response => {
                        //console.log(response.data[0]['followercount']);
                        totRevenue = totRevenue + parseInt(response.data[0]['donTot']); 
                        return Promise.resolve();                                              
                    })
                    .catch(error => {
                        console.error('Error fetching protected content:', error);
                        return Promise.reject(error);
                    });
            }else{
                return Promise.resolve();
            }
        }

        function getSalesRevenue(){
            // Check if a token is already stored (e.g., user is logged in)
            const storedToken = localStorage.getItem('token');
            if (storedToken) {
                // Attach the token to all Axios requests
                axios.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`;
                const followersBlock = document.getElementById('totRevenue');
                //console.log(storedToken);
                // Optionally, fetch and display protected content                

                return axios.get('/api/salesrevenue')
                    .then(response => {
                        //console.log(response.data[0]['followercount']);
                        totRevenue = totRevenue + parseInt(response.data[0]['salesTot']); 
                        return Promise.resolve();                                              
                    })
                    .catch(error => {
                        console.error('Error fetching protected content:', error);
                        return Promise.reject(error);
                    });
            }else{
                return Promise.resolve();
            }
        }

        function getSubscriptionRevenue(){
            // Check if a token is already stored (e.g., user is logged in)
            const storedToken = localStorage.getItem('token');
            var subsTotal = 0.0;

            if(storedToken){
                // Attach the token to all Axios requests
                axios.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`;
                //const topSalesBlock = document.getElementById('topSales');                
                //console.log(storedToken);
                // Optionally, fetch and display protected content                

                return axios.get('/api/subscriptionrevenue')
                    .then(response => {
                        const subsAmounts = response.data;
                        var singleItem = '';
                        //console.log(response.data[0]['followercount']);
                        //followersBlock.innerHTML = response.data[0]['followercount'];   
                        if(Array.isArray(subsAmounts) && subsAmounts.length > 0){  
                                                
                            //const myEventArr = myEventList.data;                        
                            

                            // Loop through the data array and create table rows
                            subsAmounts.forEach(item => {                                
                                //singleItem =  singleItem + item.item_name+`</br>`;
                                //subsTotal = subsTotal + item.totsubs;
                                totRevenue =  totRevenue + parseInt(item.totsubs);                               
                            });
                            return Promise.resolve();                                                   
                        }else{
                            //protectedContent.innerHTML = '<p>No data available.</p>';
                            return Promise.reject(error);
                        }                                            
                    })
                    .catch(error => {
                        console.error('Error fetching protected content:', error);
                    });
            }else{
                return Promise.resolve();
            }            
            
        }

        function displayTopSales(){
            // Check if a token is already stored (e.g., user is logged in)
            const storedToken = localStorage.getItem('token');
            if(storedToken){
                // Attach the token to all Axios requests
                axios.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`;
                const topSalesBlock = document.getElementById('topSales');
                //console.log(storedToken);
                // Optionally, fetch and display protected content                

                axios.get('/api/topsales')
                    .then(response => {
                        const topSalesItem = response.data;
                        var singleItem = '';
                        //console.log(response.data[0]['followercount']);
                        //followersBlock.innerHTML = response.data[0]['followercount'];   
                        if(Array.isArray(topSalesItem) && topSalesItem.length > 0){  
                                                
                            //const myEventArr = myEventList.data;                        
                            

                            // Loop through the data array and create table rows
                            topSalesItem.forEach(item => {
                                console.log('Top Sales '+item.item_name);
                                singleItem =  singleItem + item.item_name+`</br>`;
                            });
                            topSalesBlock.innerHTML = singleItem;                          
                        }else{
                            //protectedContent.innerHTML = '<p>No data available.</p>';
                            topSalesBlock.innerHTML = `-`;
                        }                                            
                    })
                    .catch(error => {
                        console.error('Error fetching protected content:', error);
                    });
            }
        }

        

        function displayEventList(pageNo){
            // Check if a token is already stored (e.g., user is logged in)
            const storedToken = localStorage.getItem('token');
            if (storedToken) {
                // Attach the token to all Axios requests
                axios.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`;
                //console.log(storedToken);
                // Optionally, fetch and display protected content                

                axios.get('/api/endpoint?page='+pageNo)
                    .then(response => {
                        const myEventList = response.data;                    
                        // check if data available to display 
                                        
                        if(Array.isArray(myEventList) && myEventList.length > 0){  
                            //console.log('4 time loading '+response);                    
                            //const myEventArr = myEventList.data;                        
                            

                            // Loop through the data array and create table rows
                            myEventList.forEach(item => {
                                const row = document.createElement('tr');
                                var eventDesc = '';
                                if(item.source_table == 'subscribers'){
                                    eventDesc = item.name + '(Tier' + item.subscription_tier + ') subscribed to you!'; 
                                }else if(item.source_table == 'merch_sales'){
                                    eventDesc = item.name + '&nbsp;&nbsp;bought&nbsp;&nbsp;' + item.subscription_tier + '&nbsp;&nbsp;from you for&nbsp;&nbsp;'+ item.subscription_start + item.read_status +'&nbsp;!';
                                }else if(item.source_table == 'followers'){
                                    eventDesc = item.name + '&nbsp;&nbsp;followed you!';
                                }else if(item.source_table == 'donations'){
                                    eventDesc = item.name + '&nbsp;&nbsp;donated&nbsp;&nbsp;'+ item.subscription_tier + '&nbsp;&nbsp;' + item.subscription_start + '&nbsp;&nbsp;to you!<br>"' + item.read_status + '"';
                                }else{
                                    eventDesc = '';
                                }
                                row.innerHTML = `<td>${eventDesc}</td>                                             
                                                <td>-</td>`;
                                tbody.appendChild(row);
                            });
                            protectedContent.appendChild(table);                            
                        }else{
                            //protectedContent.innerHTML = '<p>No data available.</p>';
                            const lastRow = document.createElement('tr');
                            lastRow.innerHTML = `<td colspan="2">No data available.</td>`;
                            tbody.appendChild(lastRow);
                            endOfRecs = true;
                        }
                        //console.log(response);
                        //protectedContent.innerHTML = `<p>${response.data.message}</p>`;
                        
                    })
                    .catch(error => {
                        console.error('Error fetching protected content:', error);
                    });
            }
        }
        
    </script>
@endsection