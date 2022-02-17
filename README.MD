# Ticket Generating App
Ticket generating app using php

## Instalation
1. Clone or download this project
2. Import ticket_db.sql to your localhost database
3. Edit connection.php with your server address, username and password
4. Install PHP > 7.3.29 version
5. Open the terminal and goto this project folder and run this command

## Generate Event
```bash
php generate-event.php "Event Name"
```

## Generate Ticket
```bash
php generate-event.php {event_id} {total_ticket}
```

# API Access
## Check Ticket [GET]
http://{your_local_dir}/check-ticket.php?event_id={id}&ticket_code={code}

Example : 
http://localhost/ticket/api/check-ticket.php?event_id=1&ticket_code=DTK299EA8A

## Update Ticket [POST]
http://{your_local_dir}/update-ticket.php

Param : 
- event_id
- ticket_code
- status

Note : 
Status => 1 = Available, 2 = Claimed

