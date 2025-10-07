
# Hairlytics

## Frontend
The frontend is made in PHP Laravel Framework. It runs trough ddev in a Docker container.


#### Deployment
Go to project folder from your project root

```
  cd frontend
```

To deploy this project run

```
  ddev launch
```

When project is loaded in docker run this command to make sure vite is loaded
```
  npm run dev
```

## Backend
Backend is a Node.js server. 

#### Deployment
Go to project folder from your project root

```
  cd backend
```

Install packages
```
  npm install
```

To deploy this project run

```
  node predict_app.js
```

