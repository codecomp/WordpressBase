# Use a Node.js base image
FROM node:latest

# Set the working directory
WORKDIR /app

# Copy package.json and package-lock.json (or yarn.lock)
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the entire project
COPY . .

# Expose the port for Webpack Dev Server
EXPOSE 8080

# Start Webpack Dev Server
CMD ["npm", "run", "build"]