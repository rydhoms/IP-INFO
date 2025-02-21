# IP Info

This project is a PHP-based web service similar to WhatIsMyIP. It allows users to easily discover their public IP address and obtain detailed information about it, including geolocation data, ISP details, and WHOIS records for domain. The tool supports both IPv4 and IPv6, and features a responsive interface that makes it accessible on any device.

# Project Overview

The project consists of three main files:

- **index.php**  
  The landing page that:
  - Displays the visitor’s public IPv4 and IPv6 addresses.
  - Offers a search form to lookup custom IP addresses or domain names.
  - Provides API usage documentation and example code snippets in various programming languages.

- **config.php**  
  A simple configuration file that stores the IPHub API key used to classify IP addresses.  
  **Note:** Replace `'your_key_here'` with your actual API key.

- **details.php**  
  A detailed view page that:
  - Resolves an input (IP or domain) to an IP address.
  - Retrieves geolocation data from [ip-api.com](http://ip-api.com).
  - Uses IPHub to determine if the IP is residential or non-residential.
  - Performs reverse DNS lookups.
  - Queries WHOIS information for domain inputs.

# Features

- **IP Info:** Automatically retrieves and displays your public IPv4 and IPv6 addresses.
- **Custom IP/Domain Lookup:** Enter any IP address or domain name to view in-depth details.
- **Geolocation Information:** Displays country, region, city, ZIP, latitude, longitude, timezone, and ISP.
- **IP Classification:** Determines if an IP is residential or associated with hosting, proxy, or VPN services using IPHub.
- **Reverse DNS Lookup:** Retrieves the hostname (PTR record) for the IP address.
- **WHOIS Data for Domains:** Fetches WHOIS information by determining the appropriate server based on the domain’s TLD.
- **Map Visualization:** Uses Leaflet to display the IP location on an interactive map.
- **API Endpoints:** Provides multiple endpoints to access IP and geolocation data in various formats (plain text, JSON, JSONP).

# Server Requirements

- **Web Server:**  
  - Apache, Nginx, or any other server capable of running PHP.
- **PHP Version:**  
  - PHP 7.x or higher.
- **PHP Extensions:**  
  - cURL (for making API requests to IPHub).  
  - fsockopen (for performing WHOIS queries).  
  - JSON (usually enabled by default) for decoding API responses.

# Installation

Follow these steps to download the latest release of the project and deploy it to your web server's public HTML directory.

## 1. Download the Latest Release

- Go to the [Releases](https://github.com/rydhoms/ip-info/releases) page of the repository.
- Download the ZIP file for the latest release.

## 2. Extract the Files

- Once the ZIP file is downloaded, extract its contents to a local directory on your computer.

## 3. Upload to Your Web Server

- **Access Your Server:**
   - Use an FTP client (e.g., FileZilla), SFTP, or your hosting control panel's file manager to connect to your web server.
- **Navigate to the Public HTML Directory:**
   - Locate your server's `public_html` (or equivalent web root) directory.
- **Upload Files:**
   - Upload all extracted files and folders from the release into the `public_html` directory.

## 4. Configure the Application

- Open the `config.php` file in the uploaded project.
- Replace `'your_key_here'` with your actual IPHub API key:
   ```php
   define('IPHUB_API_KEY', 'your_actual_api_key_here');
- Open the `index.php` file in the uploaded project, and replace domain `example.com` with your own IP-API public service.

## API & External Service Requirements

- **IPHub API Key:**  
  - Obtain an API key from [IPHub](https://iphub.info/).
  - Configure the key in the `config.php` file by replacing `'your_key_here'` with your actual API key.
- **Geolocation API:**  
  - The project uses [ip-api.com](http://ip-api.com) to retrieve geolocation data.
- **WHOIS Services:**  
  - The application performs WHOIS lookups by connecting to appropriate WHOIS servers based on domain extensions.
- **IP-API Service**
  - You must have IP-API for user IP detection, you can use project [IP-API](https://github.com/rydhoms/ip-api/) to create simple IP-API service with PHP, or you can use public IP service like [ipfy.org](https://www.ipify.org/).



# Frontend Dependencies

- **Bootstrap:**  
  - Version 4.5.0 (used for responsive design and layout).
- **jQuery:**  
  - Version 3.5.1 (used for AJAX requests and DOM manipulation).
- **Font Awesome:**  
  - Version 6.4.0 (used for icons in the UI).
- **Leaflet:**  
  - Version 1.9.4 (used for interactive map visualization of IP locations).

# Additional Notes

- Ensure that your server's firewall settings allow outgoing connections on port 43 (required for WHOIS lookups).
- The project supports both IPv4 and IPv6 addresses.
- Verify that your PHP configuration allows external HTTP requests (e.g., via `allow_url_fopen` for `file_get_contents`).

# License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

# Contributing

Contributions are welcome! If you'd like to help improve this project, please follow these guidelines:

1. **Fork the Repository:**  
   Create your own fork of the project.

2. **Create a New Branch:**  
   Use a descriptive name for your branch (e.g., `feature/new-feature` or `fix/issue-description`).

3. **Commit Your Changes:**  
   Make sure your commit messages are clear and descriptive.

4. **Submit a Pull Request:**  
   Once your changes are complete, open a pull request detailing your modifications and the issues they address.

For major changes, please open an issue first to discuss what you would like to change.

Thank you for your contributions!

# Acknowledgments

- **ip-api.com:** Thanks for providing the geolocation API used in this project.
- **IPHub:** Appreciation for the IP classification API and Proxy / VPN detection.
- **Bootstrap, jQuery, Font Awesome, and Leaflet:** Gratitude to the developers of these tools for making excellent front-end development libraries.