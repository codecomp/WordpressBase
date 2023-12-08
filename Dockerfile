FROM node:11.15.0
RUN npm install -g gulp-cli@2.3.0
WORKDIR /app
COPY package*.json ./
RUN npm install