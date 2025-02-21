<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>IP Address Info | Discover Your IP & Domain Details</title>
  <meta name="description" content="Use our free IP Address Info tool to instantly find your public IPv4 and IPv6 addresses, view geolocation data, and convert domains to IP addresses.">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- External CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
  
  <!-- Custom Styles -->
  <style>
    body {
      background-color: #f8f9fa;
      padding-top: 70px; /* Reserve space for fixed header */
    }
    .navbar {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1030;
      transition: transform 0.3s ease-in-out; /* Smooth transition */
    }
    .navbar.hide {
      transform: translateY(-100%);
    }
    .navbar.show {
      transform: translateY(0);
    }
    .ip-card {
      min-height: 150px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .btn-custom {
      background-color: #17a2b8;
      color: #fff;
      width: 100%;
    }
    .btn-custom:hover {
      background-color: #138496;
      color: #fff;
    }
    .footer {
      margin-top: 50px;
      padding: 20px 0;
      background-color: #343a40;
      color: #fff;
    }
    .spinner-border {
      margin-right: 10px;
    }
    #custom-ip-form .form-control {
      width: 300px;
    }
    #custom-ip-form .btn {
      width: auto;
    }
    @media (max-width: 767.98px) {
      .ip-card h5 {
        font-size: 1rem;
      }
      .card-title {
        font-size: 1.25rem;
      }
      .navbar-brand {
        font-size: 1.25rem;
      }
      #custom-ip-form .form-control {
        width: 100%;
        margin-bottom: 10px;
      }
      #custom-ip-form .btn {
        width: 100%;
      }
    }
    .api-usage-table {
      margin-top: 50px;
      margin-bottom: 50px;
    }
    .api-usage-table th, .api-usage-table td {
      vertical-align: middle;
    }
    #example-code pre {
      background-color: #e9ecef;
      padding: 15px;
      border-radius: 4px;
      overflow-x: auto;
    }
    [id]::before {
      content: "";
      display: block;
      height: 70px; /* Height of fixed header */
      margin-top: -70px;
      visibility: hidden;
      pointer-events: none;
    }
    #refresh-btn, #ipv4-button-container button, #ipv6-button-container button {
      position: relative;
      z-index: 1;
      touch-action: manipulation;
    }
    .container {
      padding-top: 10px; /* Matches the height of the fixed header */
    }
    #refresh-btn, #ipv4-button-container, #ipv6-button-container {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <!-- Fixed Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark show">
    <a class="navbar-brand" href="/">IP Info</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Collapsible Menu -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="/"><i class="fas fa-home"></i> Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/#ip-search"><i class="fas fa-search"></i> IP Search</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/#ip-info"><i class="fas fa-info-circle"></i> IP Info</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/#api-usage"><i class="fas fa-code"></i> API Usage</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/#example-code"><i class="fas fa-laptop-code"></i> Example Code</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container">
    <!-- IP Search Section -->
    <section id="ip-search">
      <div class="row">
        <div class="col">
          <h1 class="text-center mb-3">Discover Your IP Information</h1>
        </div>
      </div>
      <div class="row mb-5">
        <div class="col-md-8 offset-md-2">
          <form id="custom-ip-form" class="form-inline justify-content-center">
            <div class="form-group mx-2">
              <input type="text" class="form-control" id="custom-ip" placeholder="Enter IP or Domain" required>
            </div>
            <button type="submit" class="btn btn-custom"><i class="fas fa-search"></i> Search</button>
          </form>
        </div>
      </div>
    </section>

    <!-- IP Info Section -->
    <section id="ip-info">
      <div class="row">
        <!-- IPv4 Card -->
        <div class="col-md-6 mb-4">
          <div class="card border-primary h-100">
            <div class="card-header bg-primary text-white text-center">
              <h4 class="card-title mb-0">IPv4 Address</h4>
            </div>
            <div class="card-body ip-card">
              <h5 class="card-text text-center" id="ipv4-address">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading IPv4...
              </h5>
            </div>
          </div>
        </div>
        <!-- IPv6 Card -->
        <div class="col-md-6 mb-4">
          <div class="card border-success h-100">
            <div class="card-header bg-success text-white text-center">
              <h4 class="card-title mb-0">IPv6 Address</h4>
            </div>
            <div class="card-body ip-card">
              <h5 class="card-text text-center" id="ipv6-address">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading IPv6...
              </h5>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Refresh Button -->
    <div class="row mb-3">
      <div class="col text-center">
        <button id="refresh-btn" class="btn btn-custom"><i class="fas fa-sync-alt"></i> Refresh IPs</button>
      </div>
    </div>

    <!-- Details Buttons -->
    <div class="row">
      <div class="col-12 col-md-6 mb-3">
        <div id="ipv4-button-container"></div>
      </div>
      <div class="col-12 col-md-6 mb-3">
        <div id="ipv6-button-container"></div>
      </div>
    </div>
  </div>

  <!-- API Usage Section -->
  <section id="api-usage">
    <div class="container my-3">
      <h2 class="text-center mb-4">API Usage</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="thead-dark">
            <tr>
              <th>Type</th>
              <th>Format</th>
              <th>URL Example</th>
              <th>Response</th>
            </tr>
          </thead>
          <tbody>
            <!-- IPv4 & IPv6 (Default) -->
            <tr>
              <td rowspan="5">IPv4 & IPv6 (Default)</td>
              <td>Plain Text</td>
              <td><code>https://api.example.com</code></td>
              <td><code>123.45.67.89</code> or <code>2001:db8::123.123.123.123</code></td>
            </tr>
            <tr>
              <td>JSON</td>
              <td><code>https://api.example.com/?format=json</code></td>
              <td><code>{ "ip": "123.45.67.89" }</code> or <code>{ "ip": "2001:db8::123.123.123.123" }</code></td>
            </tr>
            <tr>
              <td>JSONP</td>
              <td><code>https://api.example.com/?format=jsonp&amp;callback=myFunction</code></td>
              <td><code>myFunction({ "ip": "123.45.67.89" });</code> or <code>myFunction({ "ip": "2001:db8::123.123.123.123" });</code></td>
            </tr>
            <tr>
              <td>Full (IP + Geolocation)</td>
              <td><code>https://api.example.com/?format=full</code></td>
              <td><code>IP Address: 123.45.67.89 Country: Indonesia Region: Jawa Tengah City: Solo Latitude: -7.5666 Longitude: 110.8167 ISP: Telkom Indonesia</code></td>
            </tr>
            <tr>
              <td>Full JSON (IP + Geolocation)</td>
              <td><code>https://api.example.com/?format=full-json</code></td>
              <td><code>{ "ip": "123.45.67.89", "country": "Indonesia", "region": "Jawa Tengah", "city": "Solo", "latitude": -7.5666, "longitude": 110.8167, "isp": "Telkom Indonesia" }</code></td>
            </tr>
            <!-- IPv4 (Only) -->
            <tr>
              <td rowspan="5">IPv4 (Only)</td>
              <td>Plain Text</td>
              <td><code>https://api4.example.com</code></td>
              <td><code>123.45.67.89</code></td>
            </tr>
            <tr>
              <td>JSON</td>
              <td><code>https://api4.example.com/?format=json</code></td>
              <td><code>{ "ip": "123.45.67.89" }</code></td>
            </tr>
            <tr>
              <td>JSONP</td>
              <td><code>https://api4.example.com/?format=jsonp&amp;callback=myFunction</code></td>
              <td><code>myFunction({ "ip": "123.45.67.89" });</code></td>
            </tr>
            <tr>
              <td>Full (IP + Geolocation)</td>
              <td><code>https://api4.example.com/?format=full</code></td>
              <td><code>IP Address: 123.45.67.89 Country: Indonesia Region: Jawa Tengah City: Solo Latitude: -7.5666 Longitude: 110.8167 ISP: Telkom Indonesia</code></td>
            </tr>
            <tr>
              <td>Full JSON (IP + Geolocation)</td>
              <td><code>https://api4.example.com/?format=full-json</code></td>
              <td><code>{ "ip": "123.45.67.89", "country": "Indonesia", "region": "Jawa Tengah", "city": "Solo", "latitude": -7.5666, "longitude": 110.8167, "isp": "Telkom Indonesia" }</code></td>
            </tr>
            <!-- IPv6 (Only) -->
            <tr>
              <td rowspan="5">IPv6 (Only)</td>
              <td>Plain Text</td>
              <td><code>https://api6.example.com</code></td>
              <td><code>2001:db8::123.123.123.123</code></td>
            </tr>
            <tr>
              <td>JSON</td>
              <td><code>https://api6.example.com/?format=json</code></td>
              <td><code>{ "ip": "2001:db8::123.123.123.123" }</code></td>
            </tr>
            <tr>
              <td>JSONP</td>
              <td><code>https://api6.example.com/?format=jsonp&amp;callback=myFunction</code></td>
              <td><code>myFunction({ "ip": "2001:db8::123.123.123.123" });</code></td>
            </tr>
            <tr>
              <td>Full (IP + Geolocation)</td>
              <td><code>https://api6.example.com/?format=full</code></td>
              <td><code>IP Address: 2001:db8::123.123.123.123 Country: Indonesia Region: Jawa Tengah City: Solo Latitude: -7.5666 Longitude: 110.8167 ISP: Telkom Indonesia</code></td>
            </tr>
            <tr>
              <td>Full JSON (IP + Geolocation)</td>
              <td><code>https://api6.example.com/?format=full-json</code></td>
              <td><code>{ "ip": "2001:db8::123.123.123.123", "country": "Indonesia", "region": "Jawa Tengah", "city": "Solo", "latitude": -7.5666, "longitude": 110.8167, "isp": "Telkom Indonesia" }</code></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Example Code Section -->
  <section id="example-code">
    <div class="container my-3">
      <h2 class="text-center mb-4">Example Code</h2>
      
      <h5>Bash (cURL)</h5>
      <pre><code>curl api.example.com</code></pre>
      
      <h5>Bash (wget)</h5>
      <pre><code>wget -qO- api.example.com</code></pre>
      
      <h5>PHP</h5>
      <pre><code>&lt;?php
$ip = file_get_contents("https://api.example.com/?format=json");
echo json_decode($ip, true)["ip"];
?&gt;</code></pre>
      
      <h5>JavaScript (Browser)</h5>
      <pre><code>fetch('https://api.example.com/?format=json')
    .then(response =&gt; response.json())
    .then(data =&gt; console.log(data.ip));</code></pre>
      
      <h5>Python</h5>
      <pre><code>import requests
response = requests.get("https://api.example.com/?format=json")
print(response.json()["ip"])</code></pre>
      
      <h5>Node.js</h5>
      <pre><code>const https = require('https');
https.get('https://api.example.com/?format=json', (res) =&gt; { 
    let data = '';
    res.on('data', chunk =&gt; { data += chunk; });
    res.on('end', () =&gt; { console.log(JSON.parse(data).ip); });
});</code></pre>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer text-center mt-5">
    <div class="container">
      <p>&copy; <?php echo date('Y'); ?> IP Info. All rights reserved.</p>
    </div>
  </footer>

  <!-- External JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>

  <!-- Custom Script -->
  <script>
    // Global variables for IP addresses and their load status
    let ipv4 = null;
    let ipv6 = null;
    let ipv4Loaded = false;
    let ipv6Loaded = false;

    // Function to check if both IPs have been processed
    function checkIfIPsLoaded() {
      if (ipv4Loaded && ipv6Loaded) {
        // IPv4 Details Button
        if (ipv4) {
          $('#ipv4-button-container').html(
            $('<button>')
              .addClass('btn btn-custom')
              .html('<i class="fas fa-info-circle"></i> View IPv4 Details')
              .click(function() {
                console.log('IPv4 Details button clicked'); // Debugging
                $('.navbar-collapse').collapse('hide');
                window.location.href = 'details.php?ip=' + encodeURIComponent(ipv4);
              })
          );
        } else {
          $('#ipv4-button-container').html('');
        }

        // IPv6 Details Button
        if (ipv6) {
          $('#ipv6-button-container').html(
            $('<button>')
              .addClass('btn btn-custom')
              .html('<i class="fas fa-info-circle"></i> View IPv6 Details')
              .click(function() {
                console.log('IPv6 Details button clicked'); // Debugging
                $('.navbar-collapse').collapse('hide');
                window.location.href = 'details.php?ip=' + encodeURIComponent(ipv6);
              })
          );
        } else {
          $('#ipv6-button-container').html('');
        }

        // If no IPs are detected
        if (!ipv4 && !ipv6) {
          $('#ipv4-button-container').html('<p class="text-danger">No IP addresses detected.</p>');
        }
      }
    }

    // Function to load IP addresses using AJAX
    function loadIPs() {
      // Reset variables and display loading messages
      ipv4 = null;
      ipv6 = null;
      ipv4Loaded = false;
      ipv6Loaded = false;
      $('#ipv4-address').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading IPv4...');
      $('#ipv6-address').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading IPv6...');

      // Fetch IPv4 address
      $.ajax({
        url: 'https://api4.example.com?format=json',
        dataType: 'json',
        timeout: 5000,
        success: function(data) {
          if (data && data.ip) {
            ipv4 = data.ip;
            $('#ipv4-address').html('<i class="fas fa-globe"></i> ' + ipv4);
          } else {
            ipv4 = null;
            $('#ipv4-address').text('IPv4 Address Not Detected');
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          ipv4 = null;
          $('#ipv4-address').text('IPv4 Address Not Detected');
          console.error('IPv4 Error:', textStatus, errorThrown);
        },
        complete: function() {
          ipv4Loaded = true;
          checkIfIPsLoaded();
        }
      });

      // Fetch IPv6 address
      $.ajax({
        url: 'https://api6.example.com?format=json',
        dataType: 'json',
        timeout: 5000,
        success: function(data) {
          if (data && data.ip) {
            ipv6 = data.ip;
            $('#ipv6-address').html('<i class="fas fa-globe"></i> ' + ipv6);
          } else {
            ipv6 = null;
            $('#ipv6-address').text('IPv6 Address Not Detected');
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          ipv6 = null;
          $('#ipv6-address').text('IPv6 Address Not Detected');
          console.error('IPv6 Error:', textStatus, errorThrown);
        },
        complete: function() {
          ipv6Loaded = true;
          checkIfIPsLoaded();
        }
      });
    }

    // Initialize IP loading and event handlers
    $(document).ready(function() {
      loadIPs();

      // Refresh button click (collapse mobile navbar if open)
      $('#refresh-btn').click(function() {
        console.log('Refresh button clicked'); // Debugging
        $('.navbar-collapse').collapse('hide');
        loadIPs();
      });

      // Handle custom IP search form submission (collapse mobile navbar if open)
      $('#custom-ip-form').submit(function(event) {
        event.preventDefault();
        $('.navbar-collapse').collapse('hide');
        const customIP = $('#custom-ip').val().trim();
        if (customIP) {
          window.location.href = 'details.php?ip=' + encodeURIComponent(customIP);
        } else {
          alert('Please enter a valid IP address or domain.');
        }
      });

      // Collapse mobile navbar when any nav link is clicked
      $('.navbar-nav>li>a').on('click', function(){
        $('.navbar-collapse').collapse('hide');
      });
    });
  </script>
</body>
</html>