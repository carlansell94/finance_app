<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <base href="/finance/" target="_blank">
    <title>Setup - Finance App</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <script>
        function submit() {
            const base = document.getElementsByTagName('base')[0].getAttribute('href');
            const hostname = document.getElementsByName('hostname')[0].value;
            const database = document.getElementsByName('database')[0].value;
            const username = document.getElementsByName('username')[0].value;
            const password = document.getElementsByName('password')[0].value;
            const start_date = document.getElementsByName('start_date')[0].value;
            const install_data = document.querySelector('[name="install_data"]').checked;
            let button = document.getElementsByClassName('controls')[0];

            button.innerHTML = '<img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHdpZHRoPSIzMnB4IiBoZWlnaHQ9IjMycHgiIHZpZXdCb3g9IjAgMCAxMjggMTI4IiB4bWw6c3BhY2U9InByZXNlcnZlIj48Zz48cGF0aCBkPSJNOTcuNjMgOC4yM2E3LjM4IDcuMzggMCAwIDEgMi43IDEwLjA3TDg5LjIgMzcuNTdhNy4zOCA3LjM4IDAgMSAxLTEyLjc3LTcuMzdsMTEuMTItMTkuMjdhNy4zOCA3LjM4IDAgMCAxIDEwLjA4LTIuN3oiIGZpbGw9IiMwMDAwMDAiIGZpbGwtb3BhY2l0eT0iMSIvPjxwYXRoIGQ9Ik05Ny42MyA4LjIzYTcuMzggNy4zOCAwIDAgMSAyLjcgMTAuMDdMODkuMiAzNy41N2E3LjM4IDcuMzggMCAxIDEtMTIuNzctNy4zN2wxMS4xMi0xOS4yN2E3LjM4IDcuMzggMCAwIDEgMTAuMDgtMi43eiIgZmlsbD0iI2U1ZTVlNSIgZmlsbC1vcGFjaXR5PSIwLjEiIHRyYW5zZm9ybT0icm90YXRlKDMwIDY0IDY0KSIvPjxwYXRoIGQ9Ik05Ny42MyA4LjIzYTcuMzggNy4zOCAwIDAgMSAyLjcgMTAuMDdMODkuMiAzNy41N2E3LjM4IDcuMzggMCAxIDEtMTIuNzctNy4zN2wxMS4xMi0xOS4yN2E3LjM4IDcuMzggMCAwIDEgMTAuMDgtMi43eiIgZmlsbD0iI2U1ZTVlNSIgZmlsbC1vcGFjaXR5PSIwLjEiIHRyYW5zZm9ybT0icm90YXRlKDYwIDY0IDY0KSIvPjxwYXRoIGQ9Ik05Ny42MyA4LjIzYTcuMzggNy4zOCAwIDAgMSAyLjcgMTAuMDdMODkuMiAzNy41N2E3LjM4IDcuMzggMCAxIDEtMTIuNzctNy4zN2wxMS4xMi0xOS4yN2E3LjM4IDcuMzggMCAwIDEgMTAuMDgtMi43eiIgZmlsbD0iI2U1ZTVlNSIgZmlsbC1vcGFjaXR5PSIwLjEiIHRyYW5zZm9ybT0icm90YXRlKDkwIDY0IDY0KSIvPjxwYXRoIGQ9Ik05Ny42MyA4LjIzYTcuMzggNy4zOCAwIDAgMSAyLjcgMTAuMDdMODkuMiAzNy41N2E3LjM4IDcuMzggMCAxIDEtMTIuNzctNy4zN2wxMS4xMi0xOS4yN2E3LjM4IDcuMzggMCAwIDEgMTAuMDgtMi43eiIgZmlsbD0iI2NlY2VjZSIgZmlsbC1vcGFjaXR5PSIwLjE5IiB0cmFuc2Zvcm09InJvdGF0ZSgxMjAgNjQgNjQpIi8+PHBhdGggZD0iTTk3LjYzIDguMjNhNy4zOCA3LjM4IDAgMCAxIDIuNyAxMC4wN0w4OS4yIDM3LjU3YTcuMzggNy4zOCAwIDEgMS0xMi43Ny03LjM3bDExLjEyLTE5LjI3YTcuMzggNy4zOCAwIDAgMSAxMC4wOC0yLjd6IiBmaWxsPSIjYjdiN2I3IiBmaWxsLW9wYWNpdHk9IjAuMjgiIHRyYW5zZm9ybT0icm90YXRlKDE1MCA2NCA2NCkiLz48cGF0aCBkPSJNOTcuNjMgOC4yM2E3LjM4IDcuMzggMCAwIDEgMi43IDEwLjA3TDg5LjIgMzcuNTdhNy4zOCA3LjM4IDAgMSAxLTEyLjc3LTcuMzdsMTEuMTItMTkuMjdhNy4zOCA3LjM4IDAgMCAxIDEwLjA4LTIuN3oiIGZpbGw9IiM5ZjlmOWYiIGZpbGwtb3BhY2l0eT0iMC4zOCIgdHJhbnNmb3JtPSJyb3RhdGUoMTgwIDY0IDY0KSIvPjxwYXRoIGQ9Ik05Ny42MyA4LjIzYTcuMzggNy4zOCAwIDAgMSAyLjcgMTAuMDdMODkuMiAzNy41N2E3LjM4IDcuMzggMCAxIDEtMTIuNzctNy4zN2wxMS4xMi0xOS4yN2E3LjM4IDcuMzggMCAwIDEgMTAuMDgtMi43eiIgZmlsbD0iIzg5ODk4OSIgZmlsbC1vcGFjaXR5PSIwLjQ2IiB0cmFuc2Zvcm09InJvdGF0ZSgyMTAgNjQgNjQpIi8+PHBhdGggZD0iTTk3LjYzIDguMjNhNy4zOCA3LjM4IDAgMCAxIDIuNyAxMC4wN0w4OS4yIDM3LjU3YTcuMzggNy4zOCAwIDEgMS0xMi43Ny03LjM3bDExLjEyLTE5LjI3YTcuMzggNy4zOCAwIDAgMSAxMC4wOC0yLjd6IiBmaWxsPSIjNzI3MjcyIiBmaWxsLW9wYWNpdHk9IjAuNTUiIHRyYW5zZm9ybT0icm90YXRlKDI0MCA2NCA2NCkiLz48cGF0aCBkPSJNOTcuNjMgOC4yM2E3LjM4IDcuMzggMCAwIDEgMi43IDEwLjA3TDg5LjIgMzcuNTdhNy4zOCA3LjM4IDAgMSAxLTEyLjc3LTcuMzdsMTEuMTItMTkuMjdhNy4zOCA3LjM4IDAgMCAxIDEwLjA4LTIuN3oiIGZpbGw9IiM1YzVjNWMiIGZpbGwtb3BhY2l0eT0iMC42NCIgdHJhbnNmb3JtPSJyb3RhdGUoMjcwIDY0IDY0KSIvPjxwYXRoIGQ9Ik05Ny42MyA4LjIzYTcuMzggNy4zOCAwIDAgMSAyLjcgMTAuMDdMODkuMiAzNy41N2E3LjM4IDcuMzggMCAxIDEtMTIuNzctNy4zN2wxMS4xMi0xOS4yN2E3LjM4IDcuMzggMCAwIDEgMTAuMDgtMi43eiIgZmlsbD0iIzQ0NDQ0NCIgZmlsbC1vcGFjaXR5PSIwLjczIiB0cmFuc2Zvcm09InJvdGF0ZSgzMDAgNjQgNjQpIi8+PHBhdGggZD0iTTk3LjYzIDguMjNhNy4zOCA3LjM4IDAgMCAxIDIuNyAxMC4wN0w4OS4yIDM3LjU3YTcuMzggNy4zOCAwIDEgMS0xMi43Ny03LjM3bDExLjEyLTE5LjI3YTcuMzggNy4zOCAwIDAgMSAxMC4wOC0yLjd6IiBmaWxsPSIjMmUyZTJlIiBmaWxsLW9wYWNpdHk9IjAuODIiIHRyYW5zZm9ybT0icm90YXRlKDMzMCA2NCA2NCkiLz48YW5pbWF0ZVRyYW5zZm9ybSBhdHRyaWJ1dGVOYW1lPSJ0cmFuc2Zvcm0iIHR5cGU9InJvdGF0ZSIgdmFsdWVzPSIwIDY0IDY0OzMwIDY0IDY0OzYwIDY0IDY0OzkwIDY0IDY0OzEyMCA2NCA2NDsxNTAgNjQgNjQ7MTgwIDY0IDY0OzIxMCA2NCA2NDsyNDAgNjQgNjQ7MjcwIDY0IDY0OzMwMCA2NCA2NDszMzAgNjQgNjQiIGNhbGNNb2RlPSJkaXNjcmV0ZSIgZHVyPSIxMDgwbXMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIj48L2FuaW1hdGVUcmFuc2Zvcm0+PC9nPjwvc3ZnPg==" />';

            const url = 'setup/run.php';
            const params = '?hostname=' + hostname + '&database=' + database + '&username=' + username + '&password=' + password + '&start_date=' + start_date + '&install_data=' + install_data;

            const http = new XMLHttpRequest();
            let response;

            http.open('post', url + params);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            http.onreadystatechange = function() {
                if (http.readyState == 4) {
                    dialog.getElementsByTagName('p')[0].innerHTML = http.responseText;
                    document.getElementsByTagName('dialog')[0].showModal();

                    if (http.status !=200) {
                        button.innerHTML = '<button onclick="submit()">Submit</button>';
                    } else {
                        dialog.getElementsByClassName('controls')[0].innerHTML = '<button type="button" onclick="window.open(' + base + ', \'_self\')">OK</button>';
                    }
                }
            }

            http.send();
        }
    </script>
</head>
<body>
<div id="content">
    <div id="top-bar">
        <h1>Finance App</h1>
        <h2>Initial Setup</h2>
    </div>
    <div id="data-list">
        <table>
            <colgroup>
                <col style="width: 50%">
                <col style="width: 50%">
            </colgroup>
            <thead>
                <th colspan="2">Database Settings:</th>
            </thead>
            <tbody>
                <tr>
                    <td><label>Hostname:</label></td>
                    <td><input name="hostname" title="Hotname of the server to store the database." /></td>
                </tr>
                <tr>
                    <td><label>Database:</label></td>
                    <td><input name="database" title="Name of the database. The specified database must already exist." /></td>
                </tr>
                <tr>
                    <td><label>Username:</label></td>
                    <td><input name="username" title="Username of MySQL user to use for database connection." /></td>
                </tr>
                <tr>
                    <td><label>Password:</label></td>
                    <td><input type="password" name="password" title="Password of MySQL user to use for database connection." /></td>
                </tr>
            </tbody>
            <thead>
                <th colspan="2">Sync Settings:</th>
            </thead>
            <tbody>
                <tr>
                    <td><label>Start Date:</label></td>
                    <td><input type="date" value="2019-01-01" title="Date from which to start syncing data. Recommended to be set a maximum of 5 years in the past, due to limited historical data availability." name="start_date" /></td>
                </tr>
            </tbody>
            <thead>
                <th colspan="2">Sample Data:</th>
            </thead>
            <tbody>
                <tr>
                    <td><label>Install Sample Data:</label></td>
                    <td><input type="checkbox" title="Installs currencies, stock symbols, stock markets and stock exchanges. If left unchecked, the sync will not run until entires have been added manually." name="install_data" checked /></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="controls">
    <button onclick="submit()">Submit</button>
</div>
<dialog id="dialog">
    <div id="top-bar">
        <h1>Finance App</h1>
        <h2>Initial Setup</h2>
    </div>
    <p></p>
    <div class="controls">
        <button type="button" onclick="dialog.close()">Close</button>
    </div>
</dialog>
