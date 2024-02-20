# Add Node Runtime trom docker hub
FROM node:20-alpine

# Directory from where command will be run (standard convension is "/app" )
WORKDIR /app

# Copy everything from my current directory to the docker image
# first . is the current directory on our machine and 2nd . is the the  current directory on the container
COPY . .

# specify the command to run the app
CMD node hello.js