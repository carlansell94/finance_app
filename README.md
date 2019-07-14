A web based app designed to keep track of financial information.

Features:  
Exchange rate syncing  
Stock market price syncing  
Stock price syncing  
Basic API  
Mysqli database connector

Future features:  
GUI  
Fund price syncing  
Dividend syncing  
Stock portfolio management  
PDO database connector  

At present this app is designed for my own personal use, until more features are completed.

How to use this app:
<ol>
    <li>Use the included config/schema.sql file to create the required database. This database includes a small amount of data, in order to provide a usable system out of the box.</li>
    <li>Add your database credentials to the config/db.php file.</li>
    <li>Add a sync start date to the config/sync.php file, in YYYY-MM-DD format.</li>
    <li>Run files in the 'sync' folder to run the relevant sync.</li>
</ol>

API calls are outlined in api/index.php.
