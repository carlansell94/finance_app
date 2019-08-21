A web based app designed to keep track of financial information.

Features:  
Exchange rate syncing  
Stock market price syncing  
Stock price syncing  
Basic API  
Mysqli database connector  
Basic installer  

Future features:  
GUI  
Fund price syncing  
Dividend syncing  
Stock portfolio management  
PDO database connector  

Requirements:  
MySQL/MariaDB  
PHP (Tested with 7.2/7.3)  
Apache (With mod_rewrite enabled)  

At present this app is designed for my own personal use, until more features are completed.

How to use this app:
<ol>
    <li>Create an empty database for the app to use. It is recommended to create a new mysql user specifically for this database.</li>
    <li>Navigate to the root folder in your web browser.</li>
    <li>Fill in the setup form.</li>
    <li>If you have chosen against installing the sample data, you will need to add currencies, stocks, stock markets and currencies before each sync can run.</li>
    <li>Run files in the 'sync' folder to run the relevant sync.</li>
</ol>

API calls are outlined in api/index.php.
