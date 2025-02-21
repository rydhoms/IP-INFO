<?php
// details.php
include 'config.php';

$input = $_GET['ip'] ?? '';

if (!$input) {
    die('No IP address or domain provided.');
}

$resolvedIP = '';
$inputType = '';

// Check if the input is a valid IP address
if (filter_var($input, FILTER_VALIDATE_IP)) {
    $resolvedIP = $input;
    $inputType = 'IP Address';
} else {
    // Attempt to resolve as a domain name (IPv4 first)
    $ipv4Records = dns_get_record($input, DNS_A);
    if ($ipv4Records && isset($ipv4Records[0]['ip'])) {
        $resolvedIP = $ipv4Records[0]['ip'];
        $inputType = 'Domain Host (resolved to IPv4)';
    } else {
        // If no IPv4 record, attempt IPv6 resolution
        $ipv6Records = dns_get_record($input, DNS_AAAA);
        if ($ipv6Records && isset($ipv6Records[0]['ipv6'])) {
            $resolvedIP = $ipv6Records[0]['ipv6'];
            $inputType = 'Domain Host (resolved to IPv6)';
        } else {
            die('Invalid IP address or unable to resolve domain name.');
        }
    }
}

$ip = $resolvedIP;

// Functions to get IP details
function getGeoInfo($ip) {
    $response = @file_get_contents("http://ip-api.com/json/{$ip}");
    return json_decode($response, true);
}

function getIPHubInfo($ip) {
    $ch = curl_init("http://v2.api.iphub.info/ip/{$ip}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Key: ' . IPHUB_API_KEY]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Function to get WHOIS server based on domain extension
function getWhoisServer($domain) {
    $tld = substr($domain, strrpos($domain, '.') + 1); // Extract TLD
    switch ($tld) {
        case 'id': return 'whois.pandi.or.id';
        case 'com': case 'net': return 'whois.verisign-grs.com';
        case 'org': return 'whois.pir.org';
        case 'au': return 'whois.auda.org.au';
        case 'uk': return 'whois.nic.uk';
        case 'ca': return 'whois.cira.ca';
        case 'de': return 'whois.denic.de';
        case 'fr': return 'whois.nic.fr';
        case 'jp': return 'whois.jprs.jp';
        case 'cn': return 'whois.cnnic.cn';
        case 'in': return 'whois.registry.in';
        case 'br': return 'whois.registro.br';
        case 'ru': return 'whois.tcinet.ru';
        case 'mx': return 'whois.mx';
        case 'sg': return 'whois.sgnic.sg';
        case 'za': return 'whois.registry.net.za';
        case 'eu': return 'whois.eu';
        case 'nz': return 'whois.dnc.org.nz';
        case 'ch': return 'whois.nic.ch';
        case 'nl': return 'whois.domain-registry.nl';
        case 'it': return 'whois.nic.it';
        case 'es': return 'whois.nic.es';
        case 'info': return 'whois.afilias.info';
        case 'biz': return 'whois.nic.biz';
        case 'edu': return 'whois.educause.edu';
        case 'gov': return 'whois.dotgov.gov';
        case 'xyz': return 'whois.nic.xyz';
        case 'online': return 'whois.nic.online';
        case 'shop': return 'whois.nic.shop';
        case 'app': return 'whois.nic.google';
        default: return null; // Unknown TLD
    }
}

// Function to query WHOIS server
function queryWhoisServer($domain, $whoisServer) {
    $whoisPort = 43; // WHOIS servers typically use port 43

    // Open a connection to the WHOIS server
    $fp = fsockopen($whoisServer, $whoisPort, $errno, $errstr, 10);
    if (!$fp) {
        return "Failed to connect to WHOIS server: {$errstr}";
    }

    // Send the domain query
    fwrite($fp, $domain . "\r\n");

    // Read the response
    $response = '';
    while (!feof($fp)) {
        $response .= fgets($fp, 128);
    }

    // Close the connection
    fclose($fp);

    return $response;
}

// Function to get PTR record (hostname) for an IP
function getPTRRecord($ip) {
    return gethostbyaddr($ip); // Perform reverse DNS lookup
}

// Fetch details
$geoInfo = getGeoInfo($ip);
$ipHubInfo = getIPHubInfo($ip);
$ptrRecord = getPTRRecord($ip); // Get PTR record (hostname)

// Check if the input is a domain and fetch WHOIS information
$isDomain = !filter_var($input, FILTER_VALIDATE_IP);
$whoisInfo = null;
if ($isDomain) {
    $whoisServer = getWhoisServer($input);
    if ($whoisServer) {
        $whoisInfo = queryWhoisServer($input, $whoisServer);
    } else {
        $whoisInfo = 'No WHOIS server found for this domain extension.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($inputType); ?> Details</title>
    <meta name="description" content="Explore detailed information about your IP address or resolved domain including geolocation, ISP details, and IP status.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Font Awesome 6 (latest version) -->
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
        .table td, .table th {
            vertical-align: middle;
        }
        .footer {
            margin-top: 50px;
            padding: 20px 0;
            background-color: #343a40;
            color: #fff;
        }
        .btn-custom {
            background-color: #343a40;
            color: #fff;
            width: 100%;
        }
        .btn-custom:hover {
            background-color: #23272b;
            color: #fff;
        }
        #map {
            height: 300px; /* Set map height */
            width: 100%;
            margin-top: 20px;
            border-radius: 8px;
        }
        @media (max-width: 767.98px) {
            .card-header h4 {
                font-size: 1.25rem;
            }
            .navbar-brand {
                font-size: 1.25rem;
            }
        }
        /* Fix anchor offset for fixed header */
        [id]::before {
            content: "";
            display: block;
            height: 70px;
            margin-top: -70px;
            visibility: hidden;
        }
        /* Hide the header when scrolling down */
        .navbar.hide {
            transform: translateY(-100%);
        }
        /* Show the header when scrolling up */
        .navbar.show {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Fixed Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/">IP Detector</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
    <div class="container mt-5">
        <h1 class="text-center mb-5">Details for <?php echo htmlspecialchars($inputType); ?>: <?php echo htmlspecialchars($input); ?></h1>
        <?php if ($input !== $resolvedIP): ?>
        <p class="text-center mb-3">
            Resolved IP: <?php echo htmlspecialchars($resolvedIP); ?>
        </p>
        <?php endif; ?>
        <!-- IP Information -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-secondary text-white text-center">
                <h4 class="mb-0">IP Information</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped table-responsive-sm">
                    <tr><th>IP Address</th><td><?php echo htmlspecialchars($ip); ?></td></tr>
                    <tr><th>Hostname (PTR Record)</th><td><?php echo htmlspecialchars($ptrRecord); ?></td></tr>
                    <?php if ($geoInfo && $geoInfo['status'] === 'success'): ?>
                    <tr><th>ASN</th><td><?php echo htmlspecialchars($geoInfo['as']); ?></td></tr>
                    <tr><th>Organization</th><td><?php echo htmlspecialchars($geoInfo['org']); ?></td></tr>
                    <tr><th>Network Range</th><td><?php echo htmlspecialchars($geoInfo['query'] . '/' . $geoInfo['as']); ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <!-- Geolocation Information -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white text-center">
                <h4 class="mb-0">Geolocation Information</h4>
            </div>
            <div class="card-body">
                <?php if ($geoInfo && $geoInfo['status'] === 'success'): ?>
                <table class="table table-striped table-responsive-sm">
                    <tr><th>Country</th><td><?php echo htmlspecialchars($geoInfo['country']); ?></td></tr>
                    <tr><th>Region</th><td><?php echo htmlspecialchars($geoInfo['regionName']); ?></td></tr>
                    <tr><th>City</th><td><?php echo htmlspecialchars($geoInfo['city']); ?></td></tr>
                    <tr><th>ZIP</th><td><?php echo htmlspecialchars($geoInfo['zip']); ?></td></tr>
                    <tr><th>Latitude</th><td><?php echo htmlspecialchars($geoInfo['lat']); ?></td></tr>
                    <tr><th>Longitude</th><td><?php echo htmlspecialchars($geoInfo['lon']); ?></td></tr>
                    <tr><th>Timezone</th><td><?php echo htmlspecialchars($geoInfo['timezone']); ?></td></tr>
                    <tr><th>ISP</th><td><?php echo htmlspecialchars($geoInfo['isp']); ?></td></tr>
                </table>
                <!-- Map Visualization -->
                <div id="map"></div>
                <?php else: ?>
                    <p class="text-danger">Geolocation information is not available for this IP.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- IP Status Information -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark text-center">
                <h4 class="mb-0">IP Status Information</h4>
            </div>
            <div class="card-body">
                <?php if ($ipHubInfo && isset($ipHubInfo['block'])): ?>
                <?php
                    $block = $ipHubInfo['block'];
                    $ipType = '';
                    switch ($block) {
                        case 0: $ipType = 'Residential IP'; break;
                        case 1: $ipType = 'Non-Residential IP (Hosting, Proxy, or VPN)'; break;
                        case 2: $ipType = 'Non-Residential & Residential IP'; break;
                        default: $ipType = 'Unknown';
                    }
                ?>
                <table class="table table-striped table-responsive-sm">
                    <tr><th>IP Type</th><td><?php echo htmlspecialchars($ipType); ?></td></tr>
                    <tr><th>ASN</th><td><?php echo htmlspecialchars($ipHubInfo['asn']); ?></td></tr>
                    <tr><th>ISP</th><td><?php echo htmlspecialchars($ipHubInfo['isp']); ?></td></tr>
                </table>
                <?php else: ?>
                    <p class="text-danger">IP status information is not available for this IP.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- WHOIS Information (only for domains) -->
        <?php if ($isDomain && $whoisInfo): ?>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0">WHOIS Information</h4>
            </div>
            <div class="card-body">
                <pre><?php echo htmlspecialchars($whoisInfo); ?></pre>
            </div>
        </div>
        <?php endif; ?>
        <!-- Back to Home Button -->
        <div class="text-center">
            <a href="/" class="btn btn-custom btn-lg"><i class="fas fa-arrow-left"></i> Back to Home</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer text-center mt-5">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> IP Detector. All rights reserved.</p>
        </div>
    </footer>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <!-- Include Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Map Script -->
    <script>
        <?php if ($geoInfo && $geoInfo['status'] === 'success'): ?>
        // Initialize the map
        const map = L.map('map').setView([<?php echo $geoInfo['lat']; ?>, <?php echo $geoInfo['lon']; ?>], 13);

        // Add a tile layer (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add a marker for the IP location
        L.marker([<?php echo $geoInfo['lat']; ?>, <?php echo $geoInfo['lon']; ?>]).addTo(map)
            .bindPopup('<?php echo htmlspecialchars($geoInfo['city'] . ", " . $geoInfo['country']); ?>')
            .openPopup();
        <?php endif; ?>
    </script>
</body>
</html>