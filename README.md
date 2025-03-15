  # Poll Dashboard
  A real-time poll dashboard built with Laravel, React, Websocket, broadcasting and Tailwind CSS. This application allows users to create, share, and vote on polls while viewing live updates 
  
### production link -> [http://159.65.6.23:8000]()

## Features

### Real-Time Poll Updates:
- Live updates using Laravel Reverb (WebSocket server).
- Progress bars and vote counts update dynamically.

### User Authentication:
- Login and registration system.
- Protected routes for authenticated users.

### Poll Management:
- Create polls with multiple options.
- View poll results with percentages and progress bars.

### Docker Support:
- Easy setup using Docker for local development.

## Prerequisites
Before you begin, ensure you have the following installed:

- PHP (>= 8.1)
- Composer
- Node.js (>= 16.x)
- Docker (optional, for Docker setup)
- MySQL or any other supported database

## Installation
1. Clone the Repository
   `git clone https://github.com/your-username/poll-dashboard.git`
    && cd poll-dashboard
2. Install PHP Dependencies
   `composer install`
3. Install JavaScript Dependencies
   `npm install`
4. Set Up Environment Variables
   Copy the `.env.example` file to `.env` and update the database credentials:

`cp .env.example .env` and edit the .env file:

````
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=poll_dashboard
DB_USERNAME=root
DB_PASSWORD=your_password

REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
REVERB_HOST=0.0.0.0
REVERB_PORT=8080
````
5. Generate Application Key
   `php artisan key:generate`
6. Run Migrations
   `php artisan migrate`
7. Compile Assets
   `npm run build`
8. Start the Development Server
   `php artisan serve`
9. Start Laravel Reverb (WebSocket Server)
   `php artisan reverb:start`
10. Generate admin user
    `php artisan app:create-admin`

## Docker Setup (Optional)
   If you prefer using Docker, follow these steps:

1. Build and Start Containers
   `docker-compose up -d --build`
2. App will be running at http://localhost:8000
2. WebSocket Server [ws://localhost:8080]()

## Usage
### Create a Poll
- Log in or register as a admin (default email:`root@gmail.com`, password:`root`).
Navigate to the `/admin/polls` page to go to the poll dashboard.
- Enter the poll question and options.
- Share the poll link with others.

### Vote on a Poll
- Log in or register from the login button, or you can vote as a guest.
- Paste the poll link in the input field on the homepage.
- Select an option and submit your vote.

### View real-time updates on the poll results.
- View Poll Results
- Progress bars show the percentage of votes for each option.
- Total votes are displayed.
- Live updates are displayed as soon as a vote is cast.
- Live updates can be viewed on the poll admin dashboard as well by visiting each pools details page.

## Test Cases
### A User Can Vote Successfully
    Description: This test verifies that an authenticated user can successfully vote on a poll.
#### Steps:
- A poll and an option are created. 
- A user is authenticated. 
- The user submits a vote for the option.

### A User Cannot Vote More Than Once
    Description: This test ensures that a user cannot vote more than once on the same poll.
#### Steps:
- A poll and an option are created.
- A user is authenticated.
- The user submits a vote for the option.
- The user attempts to vote again.

### A Guest Cannot Vote More Than Once Based on IP Address
    Description: This test ensures that a guest user (unauthenticated) cannot vote more than once on the same poll based on their IP address.
#### Steps:
- A poll and an option are created.
- A guest user with a specific IP address submits a vote.
- The same guest user attempts to vote again with the same IP address.

### A User Cannot Vote for an Invalid Option
    Description: This test ensures that a user cannot vote for an option that does not exist.
#### Steps:

- A poll is created. 
- A user is authenticated. 
- The user attempts to vote for an invalid option.

# FAQ

### Q: How do I create a poll?
A: Log in, navigate to the "Create Poll" page, and enter the poll details.

### Q: Can I vote multiple times?
A: No, each user (or IP address) can vote only once per poll.

### Q: How do I share a poll?
A: Copy the poll link using the "Copy Link" button and share it with others.
