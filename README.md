# TODO APP

## Start the application
The project use docker compose, so you only need to run:
```bash
docker-compose up --build
```
 **Note**
 Make sure to have open the necessary ports available, or edit the docker-compose file enabling others. The default ports binding is:
 service|port
 -|-
 api|8001
 frontend|8000
 
## The backend
To the backend development I choose to use the symfony framework on php 8. 

All the resources are under the prefix api, it means that the base URL would be `http://localhost:8001/api`:

### The resources
#### Tasks
The tasks are under the resource todo, with the following endpoints:

##### List tasks 
```http request
GET /todo
```
**availables query params**

name | values | description
- | - | -
completed | 1 o 0 | Filter the task by completed or not completed (1 is complete 0 is uncompleted)
  
**Example response**
```json
[
    {
        "id": 1,
        "title": "hacer las compras",
        "completed": true,
        "createdAt": "2021-10-17T16:05:40+00:00",
        "updatedAt": null
    },
    {
        "id": 2,
        "title": "hacer las compras",
        "completed": true,
        "createdAt": "2021-10-17T16:05:41+00:00",
        "updatedAt": null
    },
    {
        "id": 3,
        "title": "hacer las compras 3",
        "completed": true,
        "createdAt": "2021-10-17T16:05:47+00:00",
        "updatedAt": "2021-10-17T16:05:50+00:00"
    }
]
```

#### Show tasks
```http request
GET /todo/{id}
```

**Example response**
```json
{
    "id": 1,
    "title": "hacer las compras",
    "completed": true,
    "createdAt": "2021-10-17T16:05:40+00:00",
    "updatedAt": null
}
```

#### Create task
```http request
POST /todo
```

*Request body*
```json
{
    "title": "hacer las compras",
    "completed": true // optional, default false
}
```

*Example response*
```json
{
    "id": 3,
    "title": "hacer las compras",
    "completed": true,
    "createdAt": "2021-10-17T16:05:47+00:00",
    "updatedAt": null
}
```

#### Update task
```http request
PUT /todo
```

*Request body*
```json
{
   "title": "hacer las compras 3",
   "completed": true // optional, default override to false
}
```

*Example response*
```json
{
    "id": 3,
    "title": "hacer las compras 3",
    "completed": true,
    "createdAt": "2021-10-17T16:05:47+00:00",
    "updatedAt": "2021-10-17T16:05:50+00:00"
}
```

#### Delete task
```http request
DELETE /todo/{id}
```

*Example response*
```json
{
    "deleted": true
}
```

### Report
The changelog reportsare under the resource `report`

#### General report
```http request
GET /report
```

**availables query params**

name | values | description
  - | - | - 
  action | "create", "update" o "delete" | Filter the record by the given action (without this filter only the recors related to the existent tasks are shown)
    sortByTask | 1 o -1 | Sort by task id, 1 is for ascendant order and -1 for descendant
    
**Example response**
```json
[
    {
        "id": 2,
        "taskId": 4,
        "action": "create",
        "description": "Created the task \"hacer las compras\" at 23:08",
        "performedAt": "2021-10-15T23:08:56+00:00"
    },
    {
        "id": 3,
        "taskId": 5,
        "action": "create",
        "description": "Created the task \"hacer las compras\" at 23:09",
        "performedAt": "2021-10-15T23:09:20+00:00"
    },
    {
        "id": 4,
        "taskId": 6,
        "action": "create",
        "description": "Created the task \"hacer las compras\" at 23:09",
        "performedAt": "2021-10-15T23:09:49+00:00"
    },
    {
        "id": 5,
        "taskId": 7,
        "action": "create",
        "description": "Created the task \"hacer las compras\" at 23:10",
        "performedAt": "2021-10-15T23:10:18+00:00"
    },
    {
        "id": 15,
        "taskId": 8,
        "action": "create",
        "description": "Created the task \"hacer las compras\" at 23:47",
        "performedAt": "2021-10-15T23:47:33+00:00"
    }
]
```

#### Task report
```http request
GET /report/{id}
```
**Example response**
```json
[
    {
        "id": 3,
        "taskId": 3,
        "action": "create",
        "description": "Created the task \"hacer las compras\" at 16:05",
        "performedAt": "2021-10-17T16:05:47+00:00"
    },
    {
        "id": 4,
        "taskId": 3,
        "action": "update",
        "description": "Updated the task \"hacer las compras 3\" at 16:05",
        "performedAt": "2021-10-17T16:05:50+00:00"
    },
    {
        "id": 5,
        "taskId": 3,
        "action": "delete",
        "description": "Deleted the task \"hacer las compras 3\" at 17:37",
        "performedAt": "2021-10-17T17:37:16+00:00"
    }
]
```

## The frontend 
For the frontend I didn't use a framework only the libs jQuery and Sweet Alert 2.

The page is accessible in the port [8000](http://localhost:8000)

## Tests
The backend has test written with phpUnit, for run them you can use the script `runtests.sh` (The project must be running).

## Author
Any doubt you can contact me!
Name: Pablo Cha
LinkedIn: [pablocha](https://www.linkedin.com/in/pablocha/)
Mail: [pablocha1992@gmail.com](mailto:pablocha1992@gmail.com)